<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\VerifyUser;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegistRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()->withErrors([
                'email' => 'These credentials do not match our records.',
            ])->withInput($request->only('email'));
        }

        if (!Auth::attempt($credentials)) {
            return redirect()->back()->withErrors([
                'password' => 'The password does not match with this email.',
            ])->withInput($request->only('email'));
        }

        return redirect()->intended('/dashboard');
    }
    public function logout(Request $request)
    {
        Auth::logout();

        return response()->json(['message' => 'Logged out successfully']);
        
        return redirect()->route('login.form');
    }
    public function showRegistrationForm(){
        return view('auth.register');
    }

    public function register(RegistRequest $request){

        $data = $request->only(['name', 'email', 'password']);
        
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = Str::random(64);
        VerifyUser::create([
            'user_id' => $user->id, 
            'token' => $token
          ]);
        
        
        Mail::send('emails.verify', ['token' => $token], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Email Verification Mail');
        });

        return response()->json(['message' => 'Please check your email for verification.']);

    
    }

    public function verifyAccount($token)
    {
        $verifyUser = VerifyUser::where('token', $token)->first();

        if (!$verifyUser) {
            return redirect()->route('login.form')->with('error', 'Invalid verification token.');
        }
    
        $user = $verifyUser->user;
    
        if (!$user->email_verified_at) { 
            $user->email_verified_at = now(); 
            $user->save();
    
    
            $message = "Your e-mail is verified. You can now login.";
        } else {
            $message = "Your e-mail is already verified. You can now login.";
        }
    
        return redirect()->route('login.form')->with('message', $message);
    }

    protected function registered(Request $request, $user)
    {
        $user->sendEmailVerificationNotification();

        return redirect($this->redirectPath())->with('verified', true);
    }


}
