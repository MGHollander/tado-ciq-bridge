<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email:rfc'],
            'password' => ['required', 'min:6'],
        ]);

        $response = User::getAccessToken($request->email, $request->password);

        if (isset($response['error'])) {
            return back()
                ->withInput()
                ->withErrors(['email' => $response['error_description']]);
        }

        return redirect(
            route('login.callback', $response)
        );
    }

    /**
     * Callback for a successful login.
     *
     * This is just a placeholder to serve a valid response for the Garmin IQ
     * makeOAuthRequest method.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback(Request $request)
    {
        return redirect(route('home'));
    }
}
