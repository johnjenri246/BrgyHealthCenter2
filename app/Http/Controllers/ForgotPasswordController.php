<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    // 1. Show form to enter email
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    // 2. Process email and send OTP
    public function sendResetCode(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();

        // Generate OTP using the helper from TwoFactorController (or duplicate logic)
        $otp = rand(100000, 999999);
        
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        // Send Email with a simple, unclipped layout (avoid Gmail dropdown/ellipsis)
        $html = <<<HTML
        <div style="margin:0; padding:24px 0; background:#0b1224; font-family:'Inter', Arial, sans-serif;">
          <div style="max-width:640px; margin:0 auto; background:#0f162d; border-radius:18px; border:1px solid rgba(255,255,255,0.06); box-shadow:0 16px 40px rgba(0,0,0,0.35); overflow:visible;">
            <div style="background:linear-gradient(135deg,#0f4af2,#1d9bf0); padding:20px 24px; color:#fff;">
              <div style="font-size:12px; letter-spacing:0.08em; text-transform:uppercase; opacity:0.9;">Secure Access</div>
              <div style="font-size:22px; font-weight:800; margin-top:6px;">Password Reset Code</div>
            </div>
            <div style="padding:22px 24px; color:#e2e8f0; line-height:1.6;">
              <p style="margin:0 0 10px; font-size:15px;">Hello {$user->name},</p>
              <p style="margin:0 0 14px; font-size:15px; color:#cbd5e1;">Use this code to reset your password:</p>
              <div style="display:inline-block; background:#1d9bf0; color:#fff; padding:12px 22px; border-radius:12px; font-size:28px; font-weight:900; letter-spacing:2.4px; box-shadow:0 10px 28px rgba(29,155,240,0.35);">
                {$otp}
              </div>
              <p style="margin:18px 0 8px; font-size:14px; color:#94a3b8;">This code expires in 10 minutes.</p>
              <p style="margin:0 0 12px; font-size:14px; color:#94a3b8;">If you didn't request this, you can safely ignore this email.</p>
              <p style="margin:16px 0 0; font-size:13px; color:#94a3b8;">Barangay Health Center • Health & Records</p>
            </div>
          </div>
        </div>
        HTML;

        Mail::send([], [], function ($message) use ($user, $otp, $html) {
            $message->to($user->email)
                    ->subject('Password Reset Code - ' . $otp)
                    ->html($html);
        });

        // Store email in session to pass to the next step
        session(['reset_email' => $user->email]);

        return redirect()->route('password.reset.form');
    }

    // 3. Show form to enter OTP and new password
    public function showResetForm()
    {
        if (!session()->has('reset_email')) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password', ['email' => session('reset_email')]);
    }

    // 4. Verify OTP and Reset Password
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|numeric',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();

        // Verify OTP logic
        if ($user->otp_code === $request->otp && now()->lessThan($user->otp_expires_at)) {
            
            // Update Password and Clear OTP
            $user->update([
                'password' => Hash::make($request->password),
                'otp_code' => null,
                'otp_expires_at' => null,
            ]);

            session()->forget('reset_email');

            return redirect()->route('login')->with('success', 'Password reset successfully! Please login.');
        }

        return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
    }
}