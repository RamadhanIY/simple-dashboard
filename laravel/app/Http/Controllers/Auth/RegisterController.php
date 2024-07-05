<?php
namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegistRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Notifications\Notifiable;

class RegisterController extends Controller{
    
    
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    public function showRegistrationForm(){
        return view('auth.register');
    }

    public function register(RegistRequest $request){

        $data = $request->only(['name', 'email', 'password']);
        
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'verification_code' => Str::random(6),
        ]);
    
        // Mail::to($user->email)->send(new VerifyEmail($user, $user->verification_code));
    
        return $user;

    }
    protected function registered(Request $request, $user)
    {
        $user->sendEmailVerificationNotification();

        return redirect($this->redirectPath())->with('verified', true);
    }

}
