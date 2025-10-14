<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TidakHadirController extends Controller
{
    //TODO mengembangkan view tidak hadir kedepannya
    public function operatorViewTidakHadir()
    {
        $user = auth()->user();
        if (!$user->hasRole('operator_instansi')) {
            return redirect()->back()->with('error', 'anda tidak punya permission');
        }

    }
}
