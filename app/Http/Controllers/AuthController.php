<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\LogActivityHelper;
use Illuminate\Support\Facades\Auth;
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
            if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2 || Auth::user()->role_id == 4 || Auth::user()->role_id == 6) {
                return redirect('/');
            }else if(Auth::user()->role_id == 3){
                return redirect('/shipping-courier');
            }
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
