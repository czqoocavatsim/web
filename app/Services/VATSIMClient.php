<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class VATSIMClient
{
    public function getVATSIMData()
    {
        $client = new Client();
        $responseStatus = $client->get('https://status.vatsim.net/status.json');
        $dataUrl = json_decode($responseStatus->getBody())->data->v3[0];

        $response = $client->get($dataUrl);

        if ($response->getStatusCode() === 200) {
            return json_decode($response->getBody());
        }
    }

    public function searchCallsign($callsign, $precise)
    {
        $data = Cache::remember('vatsimdata', 15, function () {
            return $this->getVATSIMData();
        });

        $controllers = [];
        
        foreach ($data->controllers as $controller) {
            if ($precise) {
                if ($controller->callsign == $callsign) {
                    return $controller;
                }
            } else {
                $controllerCallsignParts = explode('_', $controller->callsign);
                $callsignParts = explode('_', $callsign);
                if (($controllerCallsignParts[0] === $callsignParts[0]) && (end($controllerCallsignParts) === end($callsignParts))) {
                    array_push($controllers, $controller);
                }
            }
        }

        if ($precise) {
            return null;
        }

        return $controllers;
    }

    public function getPilots()
    {
    $data = Cache::remember('vatsimdatapilots', 15, function () {
        return $this->getVATSIMData();
    });

    return $data->pilots ?? []; // Ensures it always returns an array
    }
}