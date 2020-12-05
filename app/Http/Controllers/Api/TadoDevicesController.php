<?php

namespace App\Http\Controllers\Api;

class TadoDevicesController extends TadoController
{
    public function hi($deviceId)
    {
        $response = $this->tado("/devices/$deviceId/identify", 'post');

        return $response->json();
    }
}
