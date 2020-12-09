<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * @var string
     */
    public $tadoAuthUrl = 'https://auth.tado.com/oauth';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email'];

    /**
     * Login in tado° and create a new session with the response.
     *
     * @param $email
     * @param $password
     */
    public static function getAccessToken($email, $password)
    {
        $client_id = config('tado.client_id');
        $client_secret = config('tado.client_secret');
        $scope = config('tado.scope');
        $expires_at = Carbon::now();

        $user = User::firstOrNew(['email' => $email]);

        $url = $user->tadoAuthUrl ."/token?client_id=$client_id&client_secret=$client_secret&scope=$scope&grant_type=password&username=$email&password=$password";
        $response = Http::post($url)->json();

        Log::debug("Create an access token for $email.", ['response' => $response]);

        if (isset($response['error'])) {
            return $response;
        }

        if (!$user->exists()) {
            $user->api_token = Str::random(64);
        }

        $user->access_token = $response['access_token'];
        $user->token_type = $response['token_type'];
        $user->refresh_token = $response['refresh_token'];
        $user->expires_at = $expires_at->addSeconds($response['expires_in']);
        $user->save();

        Log::debug("Session created for $email with token $user->api_token");

        return [
            'api_token' => $user->api_token,
        ];
    }

    public function refreshAccessToken()
    {
        $client_id = config('tado.client_id');
        $client_secret = config('tado.client_secret');
        $scope = config('tado.scope');
        $expires_at = Carbon::now();
        $refresh_token = $this->refresh_token;

        $url = $this->tadoAuthUrl ."/token?client_id=$client_id&client_secret=$client_secret&scope=$scope&grant_type=refresh_token&refresh_token=$refresh_token";
        $response = Http::post($url)->json();

        Log::debug("Refresh access token for $this->api_token", ['response' => $response]);

        if (isset($response['error'])) {
            return $response;
        }

        $this->access_token = $response['access_token'];
        $this->token_type = $response['token_type'];
        $this->refresh_token = $response['refresh_token'];
        $this->expires_at = $expires_at->addSeconds($response['expires_in']);
        $this->save();

        Log::debug("Update access token for $this->api_token");

        return $this;
    }
}
