<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\TestEmail;
use Illuminate\Support\Facades\Mail;

class TestEmailController extends Controller
{
    public function sendTestEmail()
    {
        $testEmailRecipient = 'recipient@example.com';

        // Debugging output
        \Log::info('Recipient: ' . $testEmailRecipient);

        Mail::to($testEmailRecipient)->send(new TestEmail());

        return 'Test email sent!';
    }
}
