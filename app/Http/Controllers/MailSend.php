<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MailSend extends Controller
{

    public function contact_us(Request $request) {
        $request->validate([
            'g-recaptcha-response' => 'required|captcha'
        ]);
        $contactfirst_name = $request->input('firstname');
        $contactemail = $request->input('email');
        $contactsurname = $request->input('surname');
        $contactsubject = $request->input('subject');
        $contactmessage = $request->input('message');

        DB::table('contacts')->insert(
            ['firstname' => $contactfirst_name, 'surname' => $contactsurname, 'email' => $contactemail, 'subject' => $contactsubject, 'message' => $contactmessage]
        );

        // $details = array(
        //     'title' => 'Contact Message from '.$firstname.' '.$surname,
        //     'email' => $email,
        //     'subject' => $subject,
        //     'body' => $body
        // );

        \Mail::send('email.sendMail', [

            'body' => $request->input('message')
        ], function ($mail) use ($request) {
            $mail->from($request->email, $request->firstname);
            $mail->to('harleymdev@gmail.com')->subject($request->subject);
            $mail->replyTo($request->email);

        });
        if( count(\Mail::failures()) > 0) {
        $request->session()->flash('error', 'Something went wrong');
        return view('pages.contact');
        } else {
        $request->session()->flash('success', 'Thanks. Your message has been sent');
        return view('pages.contact');
        }
    }

    public function contact_response(Request $request, $id)
    {
        $messageID = $id;
        $userID = Auth::user()->id;
        $response = $request->response;
        DB::table('contact_response')
        ->insert(['message_id' => $messageID, 'response' => $response, 'respondee_user_id' => $userID]);

        $email = $request->from;

        \Mail::send('email.sendMail', [

            'body' => $request->input('response')
        ], function ($mail) use ($request) {
            $mail->from('harleymdev@gmail.com');
            $mail->to($request->from)->subject('RE: '.$request->subject);
            $mail->replyTo('harleymdev@gmail.com');

        });
        if( count(\Mail::failures()) > 0) {
        $request->session()->flash('error', 'Something went wrong');
        return redirect()->back();
        } else {
        $request->session()->flash('success', 'Your response has been sent');
        return redirect()->back();
        }
    }

    public function subscribe(Request $request)
    {
        $email = $request->input('email');

        \Mail::send('email.subConfirm', [
            'body' => 'Hello! You are receiving this email as you have subscribed to the PCA newsletter. If this was 
            not you then please simply ignore this email. To confirm you wish to be subscribed to the PCA newsletter, 
            please click on the link below'
        ], function ($mail) use ($request) {
            $mail->from('harleymdev@gmail.com', 'PCA Newsletter');
            $mail->to($request->email)->subject('PCA Newsletter Subscription');
        });
        return view('email.subscribed');
    }
}
