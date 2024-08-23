<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\LogActivityHelper;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{
    function index()
    {

        return view('auth.login');
    }

    function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (auth()->attempt($credentials)) {
            return redirect('/');
        }
        Alert::error('Error', 'Email atau Password Salah');
        return back();
    }

    function logout()
    {
        auth()->logout();
        return redirect('/login');
    }
}
