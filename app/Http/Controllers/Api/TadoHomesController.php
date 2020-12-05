<?php

namespace App\Http\Controllers\Api;

class TadoHomesController extends TadoController
{
    public function devices($homeId)
    {
        $response = $this->tado("/homes/$homeId/devices");

        return $response->json();
    }
}
