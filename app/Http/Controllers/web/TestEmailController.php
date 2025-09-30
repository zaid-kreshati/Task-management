<?php

// app/Http/Controllers/TestEmailController.php
namespace App\Http\Controllers\web;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class TestEmailController extends Controller
{
    public function sendEmail()
    {

         dd(env('MAIL_USERNAME'));
        $to = 'zed.kreshati.2001@gmail.com'; // Replace with a valid email address

        Mail::raw('This is a test email.', function ($message) use ($to) {
            $message->to($to)
                    ->subject('Test Email from Laravel');
        });

        return 'Test email sent!';
    }
}
