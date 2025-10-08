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

            //* login untuk admin yayasan
            if ($user->hasRole('admin_yayasan')) {
                return redirect()->route('adminYysDashboard');
            }

            //* login untuk operator instansi
            else if ($user->hasRole('operator_instansi')) {
                return redirect()->route('operatorDashboard');
            }


            //* login untuk tenaga pendidik
            else if ($user->hasRole('tenaga_pendidik')) {
                return redirect()->route('userDashboard');
            }

            //* login untuk tenaga kependidikan
            else if ($user->hasRole('tenaga_kependidikan')) {
                return redirect()->route('userDashboard');
            }

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
