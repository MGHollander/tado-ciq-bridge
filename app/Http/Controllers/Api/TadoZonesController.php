<?php

namespace App\Http\Controllers\Api;

class TadoZonesController extends TadoController
{

    /**
     * Get zones and the zone states for a home.
     *
     * @param $homeId
     *
     * @return array
     */
    public function zones($homeId): array
    {
        $zones = $this->tado("/homes/$homeId/zones");

        $response = [];
        foreach ($zones as $zone) {
            if ($zone['type'] !== "HEATING") {
                continue;
            }

            $zoneState = $this->tado("/homes/$homeId/zones/{$zone['id']}/state");

            $response[] = [
                'name' => $zone['name'],
                'temperature' => [
                    'celsius' => $zoneState['sensorDataPoints']['insideTemperature']['celsius'],
                    'fahrenheit' => $zoneState['sensorDataPoints']['insideTemperature']['fahrenheit'],
                    'setting' => [
                        'celsius' => $zoneState['setting']['temperature']['celsius'],
                        'fahrenheit' => $zoneState['setting']['temperature']['fahrenheit'],
                    ],
                ],
                'humidity' => $zoneState['sensorDataPoints']['humidity']['percentage'],
            ];
        }

        return $response;
    }
}
