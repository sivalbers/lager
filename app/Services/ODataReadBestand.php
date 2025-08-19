<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

class ODataReadBestand
{
    private $odata = [];

    public function __construct()
    {
        \Log::info('__construct()');
        $this->odata = [
        'base_url' => env('ODATA_BASE_URL'),
        'username' => env('ODATA_USERNAME'),
        'password' => env('ODATA_PASSWORD'),
        ];
        \Log::info($this->odata);
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
