<?php

return [

    /*
    |--------------------------------------------------------------------------
    | tado° variables
    |--------------------------------------------------------------------------
    |
    | These values determine some parameters that need to be send with every
    | request that is made to the tado° API. This API is not public at the time
    | of writing, but some third party documentation is available at here:
    | https://documenter.getpostman.com/view/154267/S11Bz2gw#5f5d5c3a-2ecb-46d5-a0f3-c47fa2d29c15
    |
    */

    'api_url' => env('TADO_API_URL', 'https://my.tado.com/api/v2'),
    'client_id' => env('TADO_CLIENT_ID', 'tado-web-app'),
    'client_secret' => env('TADO_CLIENT_SECRET'),
    'scope' => env('TADO_SCOPE', 'home.user'),

];
