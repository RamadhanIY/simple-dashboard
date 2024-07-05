<?php
namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller{

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email','password');

        if(Auth::attempt(['email' => $email, 'password' => $password])){
            return redirect()->intended('/dashboard');
        }

        $user = User::where('email', $request->email)->first();

        // if authentication failed

        if(!$user){
            return redirect()->back()->withErrors([
            'email'=>'This email do not match our records!',
            ])->withInput($request->only('email'));
        }

        return back()->withErrors([
            'password'=>'The password does not match with this email!',
        ])->withInput($request->only('password')); 
   }
}
