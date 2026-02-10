<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\TripayService;
use App\Services\WhatsappService;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected $tripayService;
    protected $whatsappService;

    // Source of Truth for Packages
    private function getPackages()
    {
        return [
            'standard' => [
                'name' => 'PAKET STANDAR',
                'amount' => 99000,
            ],
            'premium' => [
                'name' => 'PAKET PREMIUM',
                'amount' => 150000,
            ],
        ];
    }

    public function __construct(TripayService $tripayService, WhatsappService $whatsappService)
    {
        $this->tripayService = $tripayService;
        $this->whatsappService = $whatsappService;
    }

    public function checkout($package)
    {
        $packages = $this->getPackages();

        if (!isset($packages[$package])) {
            return redirect()->route('paket-masterpiece')->with('error', 'Paket tidak valid');
        }

        $channels = $this->tripayService->getPaymentChannels();
        $selectedPackage = $packages[$package];

        return view('admin.payment.checkout', compact('channels', 'selectedPackage', 'package'));
    }

    public function process(Request $request)
    {
        $packages = $this->getPackages();
        
        $packageId = $request->package;
        if (!isset($packages[$packageId])) {
            return back()->with('error', 'Paket tidak valid');
        }

        $rules = [
            'package' => 'required',
            'method' => 'required',
        ];

        if (!Auth::check()) {
            $rules['name'] = 'required|string|max:255';
            $rules['email'] = 'required|email|max:255';
            $rules['whatsapp'] = 'required|string|max:20';
        }

        $request->validate($rules);

        $method = $request->method;
        $activePackage = $packages[$packageId];
        $tempPassword = null;

        if (Auth::check()) {
            $user = Auth::user();
            
            // SECURITY: Trust only backend status. If already paid, block payment.
            if ($user->package !== 'basic') {
                return back()->with('error', 'Akun Anda sudah memiliki paket aktif (' . strtoupper($user->package) . '). Tidak dapat melakukan pembayaran baru.');
            }
        } else {
            $waNumber = WhatsappService::normalizeNumber($request->whatsapp);
            
            // SECURITY: Trust only backend data. Check if email or WhatsApp already exists and has paid.
            $existingUser = User::where('email', $request->email)
                               ->orWhere('whatsapp', $waNumber)
                               ->first();
            
            if ($existingUser) {
                // If user exists and has a paid package, block payment.
                if ($existingUser->package !== 'basic') {
                    return back()->with('error', 'Email atau nomor WhatsApp ini sudah terdaftar dengan paket aktif. Silakan login untuk menggunakan layanan atau hubungi admin.');
                }
                
                // If user exists but still basic, allow them to continue (retrying payment).
                // REGENERATE password so the one sent via WA remains valid and synchronized.
                $tempPassword = 'Guru' . rand(100, 999);
                $user = $existingUser;
                $user->update([
                    'name' => $request->name,
                    'whatsapp' => $waNumber,
                    'password' => Hash::make($tempPassword)
                ]);
            } else {
                // Generate a friendly readable temporary password
                $tempPassword = 'Guru' . rand(100, 999);
                
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'whatsapp' => $waNumber,
                    'password' => Hash::make($tempPassword),
                    'package' => 'basic'
                ]);
            }
        }

        $merchantRef = 'GM-' . time() . '-' . Str::random(5);
        
        $data = [
            'method'         => $method,
            'merchant_ref'   => $merchantRef,
            'amount'         => $activePackage['amount'],
            'customer_name'  => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => $user->whatsapp,
            'order_items'    => [
                [
                    'sku'      => $packageId,
                    'name'     => $activePackage['name'],
                    'price'    => $activePackage['amount'],
                    'quantity' => 1,
                ]
            ],
        ];

        $result = $this->tripayService->createTransaction($data);

        if (isset($result['success']) && $result['success']) {
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'reference' => $result['data']['reference'],
                'merchant_ref' => $merchantRef,
                'amount' => $activePackage['amount'],
                'temporary_password' => $tempPassword, // Store to send via WA later
                'package' => $packageId,
                'payment_method' => $method,
                'payment_name' => $result['data']['payment_name'],
                'status' => 'UNPAID',
                'checkout_url' => $result['data']['checkout_url']
            ]);

            return redirect($result['data']['checkout_url']);
        }

        return back()->with('error', $result['message'] ?? 'Gagal membuat transaksi ke TriPay.');
    }

    public function success(Request $request)
    {
        $reference = $request->reference;
        $transaction = null;

        if ($reference) {
            $transaction = Transaction::where('reference', $reference)->first();
        } elseif (Auth::check()) {
            $transaction = Transaction::where('user_id', Auth::id())->latest()->first();
        }

        if (!$transaction) {
            return redirect()->route('dashboard');
        }

        return view('admin.payment.success', compact('transaction'));
    }

    public function callback(Request $request)
    {
        $callbackSignature = $request->header('X-Callback-Signature');
        $json = $request->getContent();
        $signature = hash_hmac('sha256', $json, config('services.tripay.private_key'));

        if ($callbackSignature !== $signature) {
            return response()->json(['success' => false, 'message' => 'Invalid signature']);
        }

        $data = json_decode($json);
        $merchantRef = $data->merchant_ref;
        $status = strtoupper($data->status); // Normalize to uppercase

        Log::info("TriPay Callback Received: {$merchantRef} with status {$status}");

        $transaction = Transaction::where('merchant_ref', $merchantRef)->first();
        if (!$transaction) {
            Log::error("TriPay Callback Error: Transaction {$merchantRef} not found");
            return response()->json(['success' => false, 'message' => 'Transaction not found']);
        }

        // Only process if status is different to avoid double processing
        if ($transaction->status !== $status) {
            Log::info("Updating Transaction {$merchantRef} status from {$transaction->status} to {$status}");
            $transaction->update(['status' => $status]);

            if ($status === 'PAID') {
                $user = $transaction->user;
                $user->update(['package' => $transaction->package]);
                
                // Send WhatsApp Notification
                if ($user->whatsapp) {
                    $packageName = $this->getPackages()[$transaction->package]['name'];
                    $message = "*PEMBAYARAN BERHASIL!* 🚀\n\n";
                    $message .= "Halo *{$user->name}*,\n";
                    $message .= "Terima kasih telah berlangganan *{$packageName}* di Guru Masterpiece.\n\n";
                    $message .= "*Detail Akun Anda:*\n";
                    $message .= "📧 Email: `{$user->email}`\n";
                    
                    if ($transaction->temporary_password) {
                        $message .= "🔑 Password: `{$transaction->temporary_password}`\n";
                        $message .= "\n_Silakan login dan segera ganti password Anda demi keamanan._\n";
                    } else {
                        $message .= "🔑 Password: (Gunakan password yang sudah Anda buat sebelumnya)\n";
                    }
                    
                    $message .= "\n🎁 *Kode Referral Anda:* `{$user->referral_code}`\n";
                    $message .= "_Bagikan kode ini ke teman guru lainnya!_\n";
                    
                    $message .= "\n🔗 Login di: " . url('/login') . "\n\n";
                    $message .= "Selamat berkarya dengan asisten AI terhebat untuk guru!";
                    
                    $this->whatsappService->sendMessage($user->whatsapp, $message);
                }
                
                Log::info("Payment Success & WA Sent: User {$user->email}");
            } else {
                Log::info("Payment Status Update: {$merchantRef} to {$status}");
            }
        }

        return response()->json(['success' => true]);
    }
}
