<?php

namespace App\Http\Controllers;

use App\Events\MessageWasCreated;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::where('name', $request->username)->first();

        $message = \App\Message::create([
            'body' => $request->body,
            'user_id' => $user->id
        ]);

         event(new MessageWasCreated($message));

        return [
            'message' => [
                'body' => $message->body,
                'username' => $user->name
            ]
        ];
    }

}
