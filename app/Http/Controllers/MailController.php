<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Mail\TestMail;

class MailController extends Controller
{
    public function send(Request $request)
    {
        // $name = 'テスト ユーザー';
        // $email = 'kitahiro1223f@gmail.com';

        // $test_mail = new TestMail;

        // Mail::send($test_mail->build($name, $email));
        echo "読み込まれています。";
        $to = "kitahiro1223f@gmail.com";
        $subject = "TEST";
        $message = "This is TEST.\r\nHow are you?";
        $headers = "From: from@example.com";
        mail($to, $subject, $message, $headers);

        return view('welcome');
    }    

    public function sendMail(Request $request)
    {
        $name = 'テスト ユーザー';
        $email = 'kitahiro1223f@gmail.com';

        Mail::send('mail', [
            'name' => $name,
        ], function ($message) use ($email) {
            $message->to($email)
                ->subject('テストタイトル');
        });

        return view('welcome');
    }
}
