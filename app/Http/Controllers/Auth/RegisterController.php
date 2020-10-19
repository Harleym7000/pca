<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use App\Role;
use App\Rules\Name_Validation;
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
        $validatedData = $request->validate([
            'g-recaptcha-response' => 'required|captcha',
            'email' => ['required', 'email', 'max:255', new Script_Validation],
            'password' => ['required', 'max:20', 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%@~£^&*()-_=+`¬¦?><.,;:]).*$/', 'confirmed', new Script_Validation],
            'password_confirmation' => ['required', 'max:20', 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%@~£^&*()-_=+`¬¦?><.,;:]).*$/', new Script_Validation],
            'agree' => ['accepted', 'required']
        ],
        $messages = [
            'password.regex' => 'Passwords must contaain at least 1 capital letter, 1 number and 1 special character (e.g. @#!?%)',
            'password.confirmed' => 'Passwords do not match'
        ]);

        $userpass = request('password');
        $userconfpass = request('password_confirmation');

        if($userpass === $userconfpass) {

        $user = new User();
        $role = Role::where('name', 'Committee Member')->first();
        $user->email = request('email');
        $user->password = Hash::make(request('password'));

        $user->save();
        $month = Carbon::now()->format('M');
        $year = Carbon::now()->year;
        DB::table('user_reg')
        ->insert(
            ['month' => $month, 'year' => $year]
        );
        $user->attachRole($role);
        return redirect('/email/verify');
        }
 else {
        $request->session()->flash('error', 'Error: Passwords do not match');
        return redirect()->back()->withInput($request->except('password'));
    }
    }
} 