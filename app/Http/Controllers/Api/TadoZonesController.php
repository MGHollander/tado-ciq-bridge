<?php

namespace App\Http\Controllers\Api;

class TadoZonesController extends TadoController
{
    public function zones($homeId): array
    {
        $zones = $this->tado("/homes/$homeId/zones");

        $response = [];
        foreach ($zones as $zone) {
            $zoneState = $this->tado("/homes/$homeId/zones/{$zone['id']}/state");

            $response[] = [
                'name' => $zone['name'],
                'temperature' => [
                    'celsius' => $zoneState['sensorDataPoints']['insideTemperature']['celsius'],
                    'fahrenheit' => $zoneState['sensorDataPoints']['insideTemperature']['fahrenheit'],
                ],
                'humidity' => $zoneState['sensorDataPoints']['humidity']['percentage'],
            ];
        }

        return $response;
    }
}
