<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReferralMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('ref')) {
            $referralCode = $request->input('ref');
            
            // Store referral code in cookie for 30 days
            return $next($request)->cookie('referred_by', $referralCode, 60 * 24 * 30);
        }

        return $next($request);
    }
}
