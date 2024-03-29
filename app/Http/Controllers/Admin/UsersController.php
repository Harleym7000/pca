<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use App\Role;
use App\Cause;
use App\Rules\Script_Validation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Mail\SendMail;
use App\Rules\Email_Validation;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function __construct()
     {
         $this->middleware(['auth', 'verified']);
     }

    public function index()
    {
        $title = 'User Management';
        $roles = Role::all();
        //$users = User::paginate(10);
        $users = User::where('profile_set', 1)->paginate(6);
        $causes = Cause::all();
        
        return view('admin.users.index')->with([
            'roles' => $roles,
            'users'=> $users,
            'title' => $title,
            'causes' => $causes
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "Create New User";
        $roles = Role::all()->except(6);
        return view('admin.users.create')->with([
            'roles' => $roles,
            'title' => $title]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        //$passwords = $request->password.' '.$request->passwordCon;
        //dd($passwords);
        $validatedData = $request->validate([
            'email' => ['required', 'unique:users', 'email', 'min:8', 'max:255', new Script_Validation, new Email_Validation],
            'roles' => 'required'
        ],
        $messages = [
            'email.unique' => 'A user with the email address '.$request->email.' already exists',
            'roles.required' => 'Please select at least one role'
            ]);

        $user = new User;
        $user->email = $request->input('email');
        $userPass = $request->input('password');

        $userPassConf = $request->input('passwordCon');
        $userExistsQuery = DB::table('users')
        ->where('email', $user->email)
        ->get();
        $userExists = count($userExistsQuery);
        if($userExists > 0) {
            $request->session()->flash('error', 'Error: A user with this email address already exists');
            return redirect()->back();
        }
        if($userPass === $userPassConf) {
            $user->password = Hash::make($request->password);
            $user->save();
            $user->roles()->sync($request->roles);
            $email = $request->input('email');
        \Mail::send('email.credentials', [
            'email' => $email,
            'password' => $userPass,
        ], function ($mail) use ($request) {
            $mail->from('harleymdev@gmail.com', 'PCA Accounts');
            $mail->to($request->email)->subject('PCA Account Credentials');
        });
            $title = 'User Management';
        $roles = Role::all();
        $users = User::paginate(10); 
        $request->session()->flash('success', 'New user created successfully');
        return redirect()->back();
        } else {
            $title = "Create New User";
        $roles = Role::all()->except(6);
        $request->session()->flash('error', 'Error: Passwords do not match');
        return view('admin.users.create')->with([
            'roles' => $roles,
            'title' => $title]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Gate::denies('manage-users')) {
            return redirect(route('admin.users.index'));
        }
        $roles = Role::all()->except(6);
        $causes = Cause::all();
        $title = 'Edit Users';
        $user = User::find($id);

        return view('admin.users.edit')->with([
            'user' => $user,
            'roles' => $roles,
            'causes' => $causes,
            'title' => $title
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user->roles()->sync($request->roles);

        if($user->save()) {

        $request->session()->flash('success', 'The user has been updated successfully');
        } else {
            $request->session()->flash('error', 'There was an error updating the user');
        }

        return redirect()->route('admin.users.index');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if(Gate::denies('manage-users')) {
            return redirect('admin.users.index');
        }

        DB::table('user_reg')
        ->where('user_id', $id)
        ->delete();
        
        DB::table('cause_user')
        ->where('user_id', $id)
        ->delete();
        
        DB::table('role_user')
        ->where('user_id', $id)
        ->delete();
        
        DB::table('user_event_registrations')
        ->where('user_id', $id)
        ->delete();
        
        DB::table('user_reg')
        ->where('user_id', $id)
        ->delete();
        
        DB::table('user_tokens')
        ->where('user_id', $id)
        ->delete();
        
        $user = User::find($id);
        $user->roles()->detach();
        $user->causes()->detach();
        $user->profile()->delete();
        
        if($user->delete()) {
            $request->session()->flash('success', 'The user was deleted successfully');
        } else {
            $request->session()->flash('error', 'There was an error deleting the user. Please try again');
        }

        return redirect()->route('admin.users.index');
    }

    public function displayResetUserPassword() {
        return view('admin.users.resetPass');
    }

    public function resetUserPassword(Request $request, User $user) {
        $validatedData = $request->validate([
            'password' => ['required', 'min:8', 'max:20', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/', 'confirmed']
        ],
        $messages = [
            'password.regex' => 'Passwords must contain at least 1 capital letter, 1 number and 1 special character (e.g. @#!?%)',
            'password.confirmed' => 'Passwords do not match',
            'password_confirmation.required' => 'Passwords do not match',
            ]);

        $user = auth()->user();
        $oldPass = $request->current_password;
        //dd($oldPass);
        $validatePass = Hash::check($oldPass, $user->password);

        if(!$validatePass) {
            $request->session()->flash('error', 'Your current password was incorrect');
            return redirect()->back();
        }

        if($validatePass) {

        $pass = $request->input('password');
        $passConf = $request->input('password_confirmation');

        if($pass === $passConf) {
            $userID = $user->id;
            $newPass = Hash::make($pass);
            DB::table('users')
            ->where('id', $userID)
            ->update(['password' => $newPass]);
            $request->session()->flash('success', 'Your password has been reset. You can now login using your new password');
            return redirect()->back();
        } else {
            $request->session()->flash('error', 'Your passwords did not match. Please try again');
            return redirect()->back();
        }
        
    }
}

    public function getUserCauses(Request $request) 
    {
        $user_id = $_POST['id'];
        $query = DB::table('user_causes')
        ->join('causes', 'user_causes.id', '=', 'causes.id')
        ->select('user_causes.user_id', 'user_causes.cause_id', 'causes.name AS cause')
        ->where('user_causes.user_id', '=', $user_id);

        $result = $query->get();

        return response()->json($result);
    }

    public function updateUserCauses(Request $request, $id)
    {
        $causes = $request->comms;
        $user = User::find($id);
        $user->causes()->sync($causes);

        if($user->save()) {

        $request->session()->flash('success', 'Your committees have been updated successfully');
        } else {
            $request->session()->flash('error', 'There was an error updating your committees');
        }

        $title = 'User Management';
        $roles = Role::all();
        //$users = User::paginate(10);
        $users = User::where('profile_set', 1)->paginate(6);
        $causes = Cause::all();
        
        return view('admin.users.index')->with([
            'roles' => $roles,
            'users'=> $users,
            'title' => $title,
            'causes' => $causes
            ]);
    }

    public function searchUser(Request $request) {
        $name = $request->name;
        $email = $request->email;

        $existingUsers = DB::table('users')
        ->join('profiles', 'users.id', '=', 'profiles.user_id')
        ->join('role_user', 'users.id', '=', 'role_user.user_id')
        ->join('roles', 'role_user.role_id', '=', 'roles.id')
        ->groupBy('users.id')
        ->select('users.id', 'profiles.firstname', 'profiles.surname', 'users.email', 'role_user.role_id', 'roles.display_name')
        ->get();

        $matches = [];
        foreach($existingUsers as $user) {
            $decryptedFirstName = decrypt($user->firstname);
            $decryptedSurname = decrypt($user->surname);
            $decryptedName = $decryptedFirstName.' '.$decryptedSurname;
            $email = $user->email;
            $role = $user->display_name;
            $userID = $user->id;
            //dd($userRoles);

            if(Str::of($decryptedName)->lower()->startsWith($name)) {
                $matchingUsers = DB::table('users')
                ->join('profiles', 'users.id', '=', 'profiles.user_id')
                ->join('role_user', 'users.id', '=', 'role_user.user_id')
                ->join('roles', 'role_user.role_id', '=', 'roles.id')
                ->where('users.id', $userID)
                ->groupBy('users.id')
                ->select('users.id', 'profiles.firstname', 'profiles.surname', 'users.email', 'role_user.role_id', 'roles.display_name')
                ->get();

                array_push($matches, $userID);
            }
        }

        $totalMatches = count($matches);
            $matchingUsers = [];
            for($i = 0; $i < $totalMatches; $i++) {
                $userID = $matches[$i];
                $match = User::find($userID);
                array_push($matchingUsers, $match);
            }

                $causes = Cause::all();
                return view('admin.users.searchResults')->with([
                    'matchingUsers' => $matchingUsers,
                    'causes' => $causes
                    ]);      
    }
}
