<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ReferralEarning;
use Illuminate\Support\Facades\Auth;

class ReferralController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $earnings = ReferralEarning::where('referrer_id', $user->id)
            ->with('referee')
            ->latest()
            ->get();
        
        $referralLink = url('/paket-masterpiece') . '?ref=' . $user->referral_code;

        return view('admin.referral.index', compact('user', 'earnings', 'referralLink'));
    }
}
