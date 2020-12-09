<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LoginController
{
    /**
     * Display the login form.
     *
     * @return \Illuminate\Http\Response
     */
    function index() {
        return view('login');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email:rfc'],
            'password' => ['required', 'min:6'],
        ]);

        $user = new User;
        $response = $user->newAccessToken($request->input('email'), $request->input('password'));

        if (isset($response['error'])) {
            return back()
                ->withInput()
                ->withErrors(['email' => $response['error_description']]);
        }

        return redirect(
            route('login.callback', [
                'api_token' => $response['token'],
            ])
        );
    }

    /**
     * Callback for a successful login.
     *
     * This is just a placeholder to serve a valid response for the Garmin IQ
     * makeOAuthRequest method.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function callback(Request $request): array
    {
        return [];
    }
}
