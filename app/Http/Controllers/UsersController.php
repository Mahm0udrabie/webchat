<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    protected $model;
    public function __construct(User $model) {
        $this->model = $model;
    }
    public function login() {
        return view('auth.login');
    }
    public function register() {
        return view('auth.register');
    }
    public function store(Request $request) {
        $request->validate([
            'name'      => 'required',
            'email'    =>'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);
        $user = $this->model->create([
            "name"     => $request->name,
            "email"    => $request->email,
            "password" => bcrypt($request->password)
        ]);
        if($user) {
            return redirect('login');
        }
        return redirect()->back()->withErrors('error happened when insert user');
    }
    public function auth_user(Request $request) {
        $request->validate(['email' => 'required|email', 'password'=>"required"]);
        if(Auth::attempt(['email'=> $request->email, 'password' => $request->password])) {
            return redirect('/home');
        }
        return redirect('login') ->withErrors('error email or password');
    }
    public function home() {
        return view('layout.chat');
    }
}
