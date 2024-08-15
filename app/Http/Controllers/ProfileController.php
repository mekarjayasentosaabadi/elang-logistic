<?php

namespace App\Http\Controllers;

use auth;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(){
        $profile        = auth()->user();
        return view('pages.profile.index', compact('profile'));
    }
}
