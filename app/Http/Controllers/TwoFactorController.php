<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TwoFactorController extends Controller
{
    // Display the OTP Form
    public function show()
    {
        if (!session()->has('auth_otp_user_id')) {
            return redirect()->route('login');
        }
        return view('auth.otp');
    }

    // Verify the Input Code
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
        ]);

        $userId = session()->get('auth_otp_user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login')->withErrors(['email' => 'Session expired. Please login again.']);
        }

        // Check if OTP matches and is not expired
        if ($user->otp_code === $request->otp && now()->lessThan($user->otp_expires_at)) {
            
            // Clear OTP fields
            $user->update([
                'otp_code' => null, 
                'otp_expires_at' => null,
                'email_verified_at' => now() // Auto-verify email if not already
            ]);

            // Log the user in
            Auth::login($user);
            session()->forget('auth_otp_user_id');
            session()->regenerate();

            // ... inside verify method, after Auth::login($user) ...

            Auth::login($user);
            session()->forget('auth_otp_user_id');
            session()->regenerate();

            // Redirect based on Role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('home');
        }

        return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
    }

    // Helper: Generate and Send OTP (Static for reuse)
    public static function generateAndSendOTP($user)
    {
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
              <div style="font-size:22px; font-weight:800; margin-top:6px;">Your One-Time Password</div>
            </div>
            <div style="padding:22px 24px; color:#e2e8f0; line-height:1.6;">
              <p style="margin:0 0 10px; font-size:15px;">Hello {$user->name},</p>
              <p style="margin:0 0 14px; font-size:15px; color:#cbd5e1;">Use this code to complete your login:</p>
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
                    ->subject('Your One-Time Password - ' . $otp)
                    ->html($html);
        });

        // Store user ID in session to track them during OTP step
        session(['auth_otp_user_id' => $user->id]);
    }
}