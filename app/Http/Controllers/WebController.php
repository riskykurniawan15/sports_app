<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function profile()
    {
        return view('auth.profile');
    }

    public function dashboard()
    {
        return view('dashboard');
    }
} 