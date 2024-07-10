<?php
namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\VerifyUser;
use Illuminate\Support\Facades\Session;

class RegisterForm extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $emailTaken = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
    ];

    public function updatedEmail()
    {
        $this->emailTaken = User::where('email', $this->email)->exists();
        $this->validateOnly('email');
    }

    public function register()
    {
        $this->validate();

        if ($this->emailTaken) {
            session()->flash('error', 'The email has already been taken.');
            return;
        }

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $token = Str::random(64);
        VerifyUser::create([
            'user_id' => $user->id,
            'token' => $token,
        ]);

        Mail::send('emails.verify', ['token' => $token], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Email Verification Mail');
        });

        Session::put('user_id', $user->id);
        return redirect()->route('verification.notice');
    }

    public function render()
    {
        return view('livewire.register-form');
    }
}

