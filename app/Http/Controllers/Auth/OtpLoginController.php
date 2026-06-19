<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\OtpToken;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OtpLoginController extends Controller
{
    public function showRequestForm(): View
    {
        return view('auth.otp-request');
    }

    public function sendOtp(Request $request): RedirectResponse
    {
        $request->validate(['email' => 'required|email|max:255']);

        $otp = OtpToken::generateFor($request->email);

        Mail::to($request->email)->send(new OtpMail($otp->otp, $request->email));

        session(['otp_email' => $request->email]);

        $msg = 'A 6-digit OTP has been sent to ' . $request->email . '. It expires in 10 minutes.';

        if (config('app.debug')) {
            $msg .= ' [DEV] OTP: ' . $otp->otp;
        }

        return redirect()->route('otp.verify.form')->with('info', $msg);
    }

    public function showVerifyForm(): View|RedirectResponse
    {
        if (! session('otp_email')) {
            return redirect()->route('otp.request.form')
                ->with('error', 'Please enter your email first.');
        }

        return view('auth.otp-verify', ['email' => session('otp_email')]);
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'otp'   => 'required|digits:6',
        ]);

        $token = OtpToken::where('email', $request->email)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->where('otp', $request->otp)
            ->latest()
            ->first();

        if (! $token) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP. Please request a new one.'])
                ->withInput();
        }

        $token->update(['used_at' => now()]);
        session()->forget('otp_email');

        $user = User::firstOrCreate(
            ['email' => $request->email],
            [
                'name'     => Str::title(str_replace(['.', '_', '-'], ' ', explode('@', $request->email)[0])),
                'password' => bcrypt(Str::random(24)),
                'role'     => User::ROLE_CUSTOMER,
            ]
        );

        Auth::login($user, true);
        $request->session()->regenerate();

        $destination = $user->isCustomer()
            ? route('shop.index')
            : route('dashboard');

        return redirect($destination)
            ->with('success', 'Welcome back, ' . $user->name . '! You are now signed in.');
    }

    public function resendOtp(Request $request): RedirectResponse
    {
        $email = session('otp_email');

        if (! $email) {
            return redirect()->route('otp.request.form');
        }

        $otp = OtpToken::generateFor($email);
        Mail::to($email)->send(new OtpMail($otp->otp, $email));

        return redirect()->route('otp.verify.form')
            ->with('info', 'A new OTP has been sent to ' . $email . '.');
    }
}
