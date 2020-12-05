<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TadoController extends Controller
{
    public function tado($endpoint, $method = 'get')
    {
        $user = auth('api')->user();

        if (Carbon::parse($user->expires_at)->lessThan(Carbon::now())) {
            $user = $user->refreshAccessToken();

            if (isset($response['error'])) {
                return route('login');
            }
        }

        $token_type = $user->token_type;
        $access_token = $user->access_token;
        $url = config('tado.api_url') . $endpoint;

        $response = Http::withHeaders(['authorization' => $token_type .' '. $access_token])->$method($url);

        // TODO add extra check if error is returned for expired token and then try to refresh if.

        Log::debug("Request ($method) to $url", ['response' => $response]);

        return $response;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function me()
    {
        $response = $this->tado('/me');

        return $response->json();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function devices($homeId)
    {
        $response = $this->tado("/homes/$homeId/devices");

        return $response->json();
    }
}
