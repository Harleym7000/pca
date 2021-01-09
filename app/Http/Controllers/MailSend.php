<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\SendMail;
use App\Rules\Name_Validation;
use App\Rules\Script_Validation;
use App\Rules\Phone_Validation;
use Faker\Guesser\Name;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use App\News;
use App\Event;
use App\Rules\Email_Validation;
use App\User;

class MailSend extends Controller
{

    public function contact_us(Request $request) {
        $request->validate([
            'g-recaptcha-response' => 'required|captcha',
            'firstname' => ['required', 'max:255', 'min:2', new Script_Validation, new Name_Validation],
            'surname' => ['required', 'max:255', 'min:2', new Script_Validation, new Name_Validation],
            'email' => ['required', 'email', 'min:8', 'max:255', new Script_Validation, new Email_Validation],
            'subject' => ['required', 'min:2', 'max:255', new Script_Validation],
            'message' => ['required', 'max:510', new Script_Validation]
        ],
    $messages = [
        'firstname.required' => 'Please provide a First Name',
        'surname.required' => 'Please provide a Surname',
        'email.required' => 'Please provide your email address',
        'subject.required' => 'Please enter a message subject',
        'message.required' => 'Please provide us with a brief description of why you are contacting us',
        'g-recaptcha-response.required' => 'Please verify you are not a robot'
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
        $request->validate([
            'sub_email' => ['required', 'email', 'min:8', 'max:255', new Script_Validation, new Email_Validation]
        ],
    $messages = [
        'sub_email.required' => 'Please provide your email address'
    ]);


        $email = $request->input('sub_email');
        $is_Subscribed = DB::table('subs')
        ->where('email', $email)
        ->get();

        if(count($is_Subscribed) > 0) {
            $request->session()->flash('error', 'You have already subscribed to our newsletter');
            return redirect()->back();
        }
        $token = Str::random(60);
        DB::table('subs')
        ->insert([
            'email' => $email,
            'token' => $token
            ]);

        \Mail::send('email.subConfirm', [
            'body' => 'You are receiving this email as you have subscribed to the PCA newsletter. To confirm you wish to be subscribed to the PCA newsletter, 
            please click on the link below.',
            'token' => $token
        ], function ($mail) use ($request) {
            $mail->from('harleymdev@gmail.com', 'PCA Newsletter');
            $mail->to($request->sub_email)->subject('PCA Newsletter Subscription');
        });
        if( count(\Mail::failures()) > 0) {
            $request->session()->flash('error', 'Something went wrong');
            return redirect()->back();
            } else {
            $request->session()->flash('success', 'An email has been sent to your address. Please confirm you wish to subscribe through the link in the email');
            return redirect()->back();
            }
    }

    public function verified(Request $request) {
        $token = $_GET['token'];

        $isNotVerified = DB::table('subs')
        ->where('token', $token)
        ->where('token_verified', 1)
        ->get();

        if(count($isNotVerified) > 0) {
            $request->session()->flash('error', 'This token has already been verified');
            return view('pages.notSubscribed');
        }
        
        $tokenValid = DB::table('subs')
        ->where('token', $token)
        ->get();
        
        if(count($tokenValid) < 1) {
            $request->session()->flash('error', 'Invalid token');
            return view('pages.notSubscribed');
        }
        elseif(count($tokenValid) === 1) {
            DB::table('subs')
            ->where('token', $token)
            ->update(['token_verified' => 1]);
            $request->session()->flash('success', 'You have successfully verified your email address. You will now be added to the mailing list when a new newsletter is sent out');
            return view('pages.verifySubscribed');
        }
    }
    public function registerEventGuest(Request $request)
    {

        $validatedData = $request->validate([
            'g-recaptcha-response' => 'required|captcha',
            'forename' => ['required', 'min:2', 'max:255', new Name_Validation, new Script_Validation],
            'surname' => ['required', new Name_Validation, 'min:2', 'max:255', new Script_Validation],
            'email' => ['required', 'email', new Script_Validation, new Email_Validation],
            'phone' => new Phone_Validation,
        ],
        $messages = [
            'forename.required' => 'Please provide your first name',
            'surname.reuired' => 'Please provide your surname',
            'email.required' => 'Please provide your email address',
            'g-recaptcha-response.required' => 'Please verify you are not a robot',
        ]);

        $forename = Crypt::encrypt($request->input('forename'));
        $surname = Crypt::encrypt($request->input('surname'));
        $email = $request->input('email');
        $phone = Crypt::encrypt($request->input('phone'));
        $eventID = $request->input('eventID');

        $event = Event::find($eventID);
        $eventName = $event->title;

        DB::table('events')
        ->where('id', $eventID)
        ->decrement('spaces_left');

        $token = Str::random(8);
        DB::table('guest_event_registrations')
        ->insert(['event_id' => $eventID, 
                  'forename' => $forename, 
                  'surname' => $surname, 
                  'email' => $email,
                  'token' => $token, 
                  'contact_no' => $phone
                  ]);

                  \Mail::send('email.eventConfirm', [
                    'body' => 'You are receiving this email as you wish to register for '.$eventName.'. To confirm you wish to register for this event, 
                    please copy the token below and paste it into the next page.',
                    'token' => $token
                ], function ($mail) use ($request) {
                    $mail->from('harleymdev@gmail.com', 'PCA Event Registation');
                    $mail->to($request->email)->subject('PCA Event Registration');
                });
                if( count(\Mail::failures()) > 0) {
                    $request->session()->flash('error', 'Something went wrong');
                    return redirect()->back();
                    } else {

        $request->session()->flash('success', 'An email with your verification code has been sent to '.$email. '. Please enter it below');
        return view('pages.confirmEventReg');
                    }
    }
    public function validateEventToken(Request $request)
    {
        $token = $request->token;

        $isNotVerified = DB::table('guest_event_registrations')
        ->where('token', $token)
        ->where('token_verified', 1)
        ->get();

        $event = DB::table('guest_event_registrations')
        ->join('events', 'guest_event_registrations.event_id', '=', 'events.id')
        ->where('guest_event_registrations.token', $token)
        ->select('guest_event_registrations.*', 'events.*')
        ->get();

        //dd($event);

        if(count($isNotVerified) > 0) {
            $request->session()->flash('error', 'You have already registered for this event');
            return view('pages.eventRegUnsuccessful');
        }
        
        $tokenValid = DB::table('guest_event_registrations')
        ->where('token', $token)
        ->get();
        
        if(count($tokenValid) < 1) {
            $request->session()->flash('error', 'Invalid token');
            return view('pages.eventRegUnsuccessful');
        }
        elseif(count($tokenValid) === 1) {
            DB::table('guest_event_registrations')
            ->where('token', $token)
            ->update(['token_verified' => 1]);
            $request->session()->flash('success', 'You have successfully registered for the event.');
            return view('pages.eventRegSuccessful');
    }
}

public function createdUserReg(Request $request) 
{
    //dd('Hello World!');

    $email = $request->email;
    $userExists = DB::table('users')
    ->where('email', $email)
    ->get();

    $UID = DB::table('users')
    ->insertGetId(['email' => $email]);

    $user = User::find($UID);

    $user->roles()->sync($request->roles);

    if(count($userExists) > 0) {
        $request->session()->flash('error', 'A user with this email address already exists');
            return redirect()->back();
    } else {
        $token = $token = Str::random(60);

         $insertToken = DB::table('user_tokens')
        ->insert(['token' => $token, 'user_id' => $UID]);

        //dd($UID);

        \Mail::send('email.newUser', [
            'body' => 'You are receiving this email as you have been added as a back-end user of the PCA website. Please click on the link below to complete setting up your account, ',
            'token' => $token
        ], function ($mail) use ($request) {
            $mail->from('harleymdev@gmail.com', 'PCA User Registation');
            $mail->to($request->email)->subject('PCA User Registration');
        });
        if( count(\Mail::failures()) > 0) {
            $request->session()->flash('error', 'Something went wrong');
            return redirect()->back();
            } else {

$request->session()->flash('success', 'An email has been sent to ' .$email .' with instructions to set up their account.');
return redirect()->back();
    }
}
}

public function validateUserToken(Request $request)
{
    if(!Auth::guest()) {
        $request->session()->flash('error', 'You are not authorised to complete this action');
            return redirect('/');
    }
    $token = $_GET['token'];

    //dd($token);

    $tokenValid = DB::table('user_tokens')
    ->where('token', $token)
    ->get();

    $tokenVerified = DB::table('user_tokens')
    ->where('token', $token)
    ->where('verified', 1)
    ->get();

    if(count($tokenValid) < 1) {
        $request->session()->flash('error', 'This token is invalid');
            return redirect('/');
    }

    if(count($tokenVerified) > 0) {
        $request->session()->flash('error', 'This token has already been verified');
            return redirect('/');
    }

    if(count($tokenValid) === 1) {
        // $verifyToken = DB::table('user_tokens')
        // ->where('token', $token)
        // ->update(['verified' => 1]);

        $userID = DB::table('user_tokens')
        ->where('token', $token)
        ->select('user_id')
        ->first();

            return view('pages.createPassword')->with('userID', $userID);
        
    }
}
}
