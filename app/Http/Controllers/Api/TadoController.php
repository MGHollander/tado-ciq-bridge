<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TadoController extends Controller
{

    /**
     * Make a call to the tado° API.
     *
     * Before we make the call we check the expiration date of the access token
     * and we refresh it if is is expired.
     *
     * @param  string  $endpoint
     * @param  string  $method
     *
     * @return mixed
     */
    public function tado($endpoint, $method = 'get')
    {
        $user = auth('api')->user();

        if (Carbon::parse($user->expires_at)->lessThan(Carbon::now())) {
            $user = $user->refreshAccessToken();

            if (isset($response['error'])) {
                return $response;
            }
        }

        $token_type = $user->token_type;
        $access_token = $user->access_token;
        $url = config('tado.api_url') . $endpoint;

        $response = Http::withHeaders(['authorization' => $token_type .' '. $access_token])->$method($url);

        // TODO add extra check if error is returned for expired token and then try to refresh it.

        Log::debug("Request ($method) to $url", ['response' => $response]);

        return $response->json();
    }

    /**
     * Get user information.
     *
     * @return array
     */
    public function me(): array
    {
        $me = $this->tado('/me');

        return [
            'id' => $me['id'],
            'username' => $me['username'],
            'name' => $me['name'],
            'email' => $me['email'],
            'homes' => $me['homes'],
        ];
    }
}
