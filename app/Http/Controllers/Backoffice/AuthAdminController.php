<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AuthAdminController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if ($admin && $request->password === $admin->password) {
            return response()->json(['message' => 'Connexion réussie', 'admin'=> $admin]);
        }

        return response()->json([
            'message' => 'Email ou mot de passe incorrect',
            'admin'   => $admin
        ]);
    }
 

}

