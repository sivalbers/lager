<?php

namespace App\Services;

use Throwable;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

class BuchungsService
{


    private $odata = [];

    public function __construct()
    {

        $this->odata = [
        'base_url' => env('ODATA_BASE_URL'),
        'username' => env('ODATA_USERNAME'),
        'password' => env('ODATA_PASSWORD'),
        ];

    }

    protected function base(string $path = ''): string
    {
        return sprintf($this->odata['base_url'], $path);
    }

    public function BucheArtikel($artikelnr, $lagerort, $menge){

        $params = [
            'ItemNo' => $artikelnr,
            'LocationCode' => $lagerort,
            'Quantity' => $menge,
            'PostingDescription' => 'Webshop ' . uniqid()
        ];

        try {
            $response = Http::withBasicAuth(
                    $this->odata['username'],
                    $this->odata['password']
                )
                ->acceptJson()
                ->timeout(15)
                ->retry(2, 300)
                ->post($this->base('ItemJournalPostingWS_PostItemJournalEntry'), $params)
                ->throw();

            return [
                'success' => true,
                'data' => $response->json(),
            ];
        } catch (Throwable $e) {
            \Log::error('Fehler bei Artikelbuchung in BC', [
                'artikel' => $artikelnr,
                'lagerort' => $lagerort,
                'menge' => $menge,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Fehler bei der Buchung: ' . $e->getMessage(),
            ];
        }
    }
}
