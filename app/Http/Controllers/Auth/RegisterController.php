<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use App\Role;
use App\Rules\Email_Validation;
use App\Rules\Name_Validation;
use App\Rules\Password_validation;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Rules\Script_Validation;
use App\Rules\Postcode_Validation;
use App\Rules\Phone_Vaidation;
use Carbon\Carbon;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */

     protected function validator(array $data)
     {
     }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */

    protected function index()
    {
        $causes = DB::table('causes')->get();
        return view('auth.register')->with('causes', $causes);
    }

    protected function create(Request $request)
    {
        $secretKey = env('NOCAPTCHA_SECRET');
        $response = $_POST['g-recaptcha-response'];
        $ip = $_SERVER['REMOTE_ADDR'];
        //dd($ip);

        $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$response&remoteip=$ip";
        $file = file_get_contents($url);
        $data = json_decode($file);
        if($data->success == true)
        {
            $validatedData = $request->validate([
                'email' => ['required', 'unique:users', 'email', 'min:8', 'max:255', new Script_Validation, new Email_Validation],
                'password' => ['required', 'max:20', 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%@~£^&*()-_=+`¬¦?><.,;:]).*$/', 'confirmed'],
                'agree' => 'required',
            ],
            $messages = [
                'password.regex' => 'Passwords must contain at least 1 capital letter, 1 number and 1 special character (e.g. @#!?%)',
                'password.confirmed' => 'Passwords do not match',
                'password_confirmation.required' => 'Passwords do not match',
                'email.unique' => 'A user with the email address '.$request->email.' already exists',
                'agree.required' => 'Please confirm you have read and agreed to the terms and conditions',

                ]);
        $userpass = request('password');
        $userconfpass = request('password_confirmation');

        $userExistsQuery = DB::table('users')
        ->where('email', $request->email)
        ->get();
        $userExists = count($userExistsQuery);
        if($userExists > 0) {
            $request->session()->flash('error', 'Error: A user with this email address already exists');
            return redirect()->back();
        }

        if($userpass === $userconfpass) {

        $user = new User();
        $role = Role::where('name', 'Member')->first();
        $user->email = request('email');
        $user->password = Hash::make(request('password'));

        $user->save();
        $userID = $user->id;
        $month = Carbon::now()->format('M');
        $year = Carbon::now()->year;
        DB::table('user_reg')
        ->insert(
            ['month' => $month, 'year' => $year, 'user_id' => $userID]
        );
        $user->attachRole($role);
        event(new \Illuminate\Auth\Events\Registered($user));

        return redirect('/email/verify');
        }
    }
    if($data->success == false) {
        $request->session()->flash('error', 'Error: Please verify you are not a robot');
        return redirect()->back();
    }
 else {
        $request->session()->flash('error', 'Error: Passwords do not match');
        return redirect()->back()->withInput($request->except('password'));
    }
    }
}
