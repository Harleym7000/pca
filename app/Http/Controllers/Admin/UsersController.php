<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function __construct()
     {
         $this->middleware('auth');
     }

    public function index()
    {
        $title = 'User Management';
        $roles = Role::all();
        $users = User::paginate(8); 
        return view('admin.users.index')->with([
            'roles' => $roles,
            'users'=> $users,
            'title' => $title
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
        $user = new User;
        $user->name = $request->input('username');
        $user->email = $request->input('email');
        $userPass = $request->input('password');

        $userPassConf = $request->input('passwordCon');
        if($userPass === $userPassConf) {
            $user->password = Hash::make($request->password);
            $user->save();
            $user->roles()->sync($request->roles);
            $title = 'User Management';
        $roles = Role::all();
        $users = User::paginate(8); 
        return view('admin.users.index')->with([
            'success', 'New User created successfully',
            'roles' => $roles,
            'users'=> $users,
            'title' => $title
            ]);
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
    public function edit(User $user)
    {
        if(Gate::denies('edit-users')) {
            return redirect(route('admin.users.index'));
        }
        $roles = Role::all();
        $title = 'Edit Users';

        return view('admin.users.edit')->with([
            'user' => $user,
            'roles' => $roles,
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

        $user->name = $request->name;
        $user->email = $request->email;

        if($user->save()) {

        $request->session()->flash('success', $user->name . ' has been updated');
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
    public function destroy($id)
    {
        if(Gate::denies('delete-users')) {
            return redirect('admin.users.index');
        }

        $user = User::find($id);
        $user->roles()->detach();
        $user->delete();

        return redirect()->route('admin.users.index');
    }

    public function getUsersByRole(Request $request) {
        $role_id = $_POST['id'];
        $query = DB::table('users')
        ->join('role_user', 'users.id', '=', 'role_user.user_id')
        ->join('roles', 'roles.id', '=', 'role_user.role_id')
        ->select('users.id AS user_id', 'users.name', 'users.email', 'role_user.role_id', 'role_user.user_id', 'roles.name AS role_name')
        ->where('role_user.role_id', '=', $role_id);

        $result = $query->get();

        return response()->json($result);
    }

    public function getUsersByName(Request $request) {
        $name = $_POST['search'];
        $query = DB::table('users')
        ->join('role_user', 'users.id', '=', 'role_user.user_id')
        ->join('roles', 'roles.id', '=', 'role_user.role_id')
        ->select('users.id AS user_id', 'users.name', 'users.email', 'role_user.role_id', 'role_user.user_id', 'roles.name AS role_name')
        ->where('users.name', 'like', '%'.$name.'%')
        ->groupBy('users.id');

        $result = $query->get();

        return response()->json($result);
    }

    public function displayResetUserPassword() {
        return view('admin.users.resetPass');
    }

    public function resetUserPassword(Request $request, User $user) {

        $user = auth()->user();
        $pass = $request->input('password');
        $passConf = $request->input('passwordCon');

        if($pass === $passConf) {
            $userID = $user->id;
            $newPass = Hash::make($pass);
            DB::table('users')
            ->where('id', $userID)
            ->update(['password' => $newPass]);
            $request->session()->flash('success', 'Your password has been reset. You can now login using your new password');
        } else {
            $request->session()->flash('error', 'Your passwords did not match. Please try again');
        }
        return view('admin.users.resetPass');
    }

}
