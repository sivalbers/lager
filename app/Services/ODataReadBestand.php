<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\PendingRequest;


class ODataReadBestand
{
    private $odata = [];

    public function __construct()
    {

        $this->odata = [
        'base_url' => env('ODATA_BASE_URL', "https://sieverding-webshop.faveo365.com:9248/NSTSUBSCRIPTIONSODATA/ODatav4/Company('Sieverding%%20Besitzunternehmen')/%s?tenant=y7069851800471529774"),
        'username' => env('ODATA_USERNAME', 'testuser'),
        'password' => env('ODATA_PASSWORD', 'Sieverding22!'),
        ];
/*
        Log::info([
            'base_url' => $this->odata['base_url'],
            'username' => $this->odata['username'],
            'password' => $this->odata['password']
        ]);

    */

    }

    protected function request(): PendingRequest
    {
        return Http::withBasicAuth(
                $this->odata['username'],
                $this->odata['password']
            )
            ->acceptJson()
            ->timeout(15)
            ->retry(2, 300);
    }

    protected function base(string $path = ''): string
    {
        return sprintf($this->odata['base_url'], $path);
    }

    public function get(array $params = []): array
    {
        // Beispiel-Endpunkt: /availability
        $resp = $this->request()
            ->get($this->base('availability'), $params)
            ->throw();

        return $resp->json();           // oder: ($resp->json()['value'] ?? [])
    }


}
