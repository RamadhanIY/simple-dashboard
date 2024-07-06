<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class VerificationController extends Controller
{
    public function verifyEmail($code)
    {
        $user = User::where('verification_code', $code)->first();

        if ($user) {
            $user->is_verified = true;
            $user->verification_code = null;
            $user->save();

            return redirect('/dashboard')->with('success', 'Email verified successfully.');
        }

        return redirect('/')->with('error', 'Invalid verification code.');
    }
}
