<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function join(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|alpha_dash|min:3'
        ]);

        $user = User::where('name', $request->username)->first();

        if( ! $user) {
            $user = User::create(['name' => $request->username]);
        }

        Auth::login($user);

        return ['success' => true];
    }

    public function check()
    {
        if (Auth::check()) {
            return Auth::user();
        }
        
        return ['success' => false];
    }

    public function logout()
    {
        Auth::logout();

        return ['success' => true];
    }

}
