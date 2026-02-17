<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPasswordMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Http\Requests\SendResetLinkRequest;
use App\Http\Requests\ResetPasswordRequest;

class ForgotPasswordController extends Controller
{
    /**
     * Show forgot password page
     */
    public function showForgotPasswordForm()
    {
        return view('forgot_password');
    }

    /**
     * Generate token & send reset mail
     */
    public function sendResetLink(SendResetLinkRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email not found'
            ]);
        }

        // Generate token
        $token = Str::random(64);

        // Save token in users table
        $user->forgot_token = $token;
        $user->forgot_token_created_at = now();
        $user->save();


        // Reset link
        $resetLink = url('/reset-password/' . $token . '/' . $user->email);

        // Send mail
        Mail::to($user->email)->send(
            new ForgotPasswordMail($resetLink)
        );

        return back()->with('status', 'Password reset link sent to your email!');
    }

    /**
     * Show reset password form
     */
    public function showResetPasswordForm($token, $email)
    {
        $user = User::where('email', $email)
            ->where('forgot_token', $token)
            ->first();

        $expiry = Carbon::parse($user->forgot_token_created_at)->addMinutes(5)->isPast();

        if (!$user ) {
            return view('forgot_password')->with('error', "User not Found / Invalid User" );
        } elseif ( !$user->forgot_token_created_at) {
            return view('forgot_password')->with('error',"Invalid Reset Link");
        } elseif ($expiry) {
           return view('forgot_password')->with('error',"Reset link has expired. Please request a new one");
            
        } else {

            return view('reset_password', compact('token', 'email'));
        }
    }


    /**
     * Reset password
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = User::where('email', $request->email)
            ->where('forgot_token', $request->token)
            ->first();

        $expiry = Carbon::parse($user->forgot_token_created_at)->addMinutes(5)->isPast();


        if (!$user || $expiry) {
            return back()->withErrors([
                'email' => 'Reset link expired'
            ]);
        }


        // Update password & clear token
        $user->password = Hash::make($request->password);
        $user->forgot_token = null;
        $user->forgot_token_created_at = null;
        $user->save();


        return redirect()
            ->route('login.form')
            ->with('status', 'Password reset successfully!');
    }
}
