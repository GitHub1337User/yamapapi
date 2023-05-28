<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{

    public function loginIndex(){
        return view('pages.login');
    }
    public function regIndex(){
        return view('pages.reg');
    }
    public function login(Request $request){
        $validatedData = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);
        if (Auth::attempt($validatedData)) {
            $request->session()->regenerate();
            return redirect()->route('map');
        }
        return redirect()->route('loginIndex')->with('message', 'Данные не совпадают');
    }
    public function register(Request $request){
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
        return redirect()->route('loginIndex')->with('message', 'Успешная регистрация, авторизуйтесь!');
    }
    public function perform(Request $request)
    {
//        Auth::logout();
//
//        $request->session()->invalidate();
//
//        $request->session()->regenerateToken();
//
//        return redirect('/');

        Session::flush();

        Auth::logout();

        return redirect('login');
    }
}
