<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Find user via biodata relation
        $user = \App\Models\User::whereHas('biodata', function($q) use ($request) {
            $q->where('email', $request->email);
        })->first();

        if (!$user) {
            return back()->withErrors(['email' => __('passwords.user')]);
        }

        // Use username credential for finding the user record inside the password broker
        $status = \Illuminate\Support\Facades\Password::sendResetLink([
            'username' => $user->username
        ]);

        return $status === \Illuminate\Support\Facades\Password::RESET_LINK_SENT
                    ? back()->with(['success' => __($status)])
                    : back()->withErrors(['email' => __($status)]);
    }
}
