<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function viewLogin()
    {
        return view('auth.login');
    }


    public function login(Request $request)
    {
        $credential = [
            "username" => $request->username,
            "password" => $request->password
        ];

        if (auth('web')->attempt($credential)) {
            $user = User::where('username', $request->username)->first();

            return redirect()->route('operatorDashboard');
        } else {
            return back();
        }
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerate();
        return redirect()->intended('/');
    }
}
