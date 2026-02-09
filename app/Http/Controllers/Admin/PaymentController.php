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
        } else {
            $user = User::where('email', $request->email)->first();
            $waNumber = WhatsappService::normalizeNumber($request->whatsapp);
            
            if (!$user) {
                // Generate a friendly readable temporary password
                $tempPassword = 'Guru' . rand(100, 999);
                
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'whatsapp' => $waNumber,
                    'password' => Hash::make($tempPassword),
                    'package' => 'basic'
                ]);
            } else {
                // Update WA for existing user if provided
                $user->update(['whatsapp' => $waNumber]);
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
        return view('admin.payment.success');
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
        $status = $data->status;

        if ($status === 'PAID') {
            $transaction = Transaction::where('merchant_ref', $merchantRef)->first();
            if ($transaction && $transaction->status !== 'PAID') {
                $transaction->update(['status' => 'PAID']);
                
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
                    
                    $message .= "\n🔗 Login di: " . url('/login') . "\n\n";
                    $message .= "Selamat berkarya dengan asisten AI terhebat untuk guru!";
                    
                    $this->whatsappService->sendMessage($user->whatsapp, $message);
                }
                
                Log::info("Payment Success & WA Sent: User {$user->email}");
            }
        }

        return response()->json(['success' => true]);
    }
}
