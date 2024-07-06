<?php
namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\VerifyUser;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegistRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Notifications\Notifiable;

class RegisterController extends Controller{
    
    
    
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
        
        // Mail::send('emails.verify', ['token' => $token], function($message) use($request){
        //     $message->to($request->email);
        //     $message->subject('Email Verification Mail');
        // });
        Mail::send('emails.verify', ['token' => $token], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Email Verification Mail');
        });
       
      return redirect("/dashboard")->withSuccess('Great! You have Successfully loggedin');
      
    }

    public function verifyAccount($token)
    {
        $verifyUser = VerifyUser::where('token', $token)->first();
  
        $message = 'Sorry your email cannot be identified.';
  
        if(!is_null($verifyUser) ){
            $user = $verifyUser->user;
              
            if(!$user->is_email_verified) {
                $verifyUser->user->is_email_verified = 1;
                $verifyUser->user->save();
                $message = "Your e-mail is verified. You can now login.";
            } else {
                $message = "Your e-mail is already verified. You can now login.";
            }
        }
  
      return redirect()->route('/')->with('message', $message);
    }

    protected function registered(Request $request, $user)
    {
        $user->sendEmailVerificationNotification();

        return redirect($this->redirectPath())->with('verified', true);
    }

}
