<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Log in with new or existing user.
     * 
     * @param Request $request
     * @return array
     */
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

}
