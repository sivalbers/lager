<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use App\Repositories\ArtikelRepository;

class WarenzugangBuchen extends Component
{

    public $jsonResult = "";
    public $clipboardValue = "";
    public $lieferscheinNr = "70824";
    private $url = "https://sieverding-sandbox.faveo365.com:9248/NSTSUBSCRIPTIONSODATA/ODatav4/Company('Sieverding%20Besitzunternehmen')/ShopLieferscheinArtikel?tenant=x7069851800471529774&\$filter=DocumentNo%20eq%20'%s'";

    protected $listeners = ['setJsonResult' => 'updateJsonResult'];
    protected ?string $user;
    protected ?string $key;




    public $positionen = [];
    public $message = '';

    /**
     * Wandelt das OData-JSON der ShopLieferscheinArtikel in ein Array um:
     * [
     *   ["ArtikelNr" => "200356", "Lagerort" => "4000", "Menge" => 1.0],
     *   ...
     * ]
     *
     * @param string $json OData-JSON als String
     * @return array<int, array{ArtikelNr:string, Lagerort:string, Menge:float}>
     *
     * @throws JsonException bei ungültigem JSON
     * @throws UnexpectedValueException bei fehlender/inkorrekter Struktur
     */

    public function __construct(){
        $this->user     = 'EWEWEBSERVICEUSER';
        $this->key      = 'Sieverding22!3242a_.';
    }

    public function updateJsonResult($data)
    {
        $this->jsonResult = $data['value'];
    }


/**
     * Ruft ShopLieferscheinArtikel per OData ab und liefert JSON (pretty-printed).
     *
     * @param string $lieferscheinNr  z.B. "LS00070912"
     * @return string  JSON-String (mehrzeilig), bereit für ein Textfeld
     */
    function fetchLieferscheinAlsJson(string $lieferscheinNr): string{
        // OData-String-Escape: einfaches ' wird zu ''
        $odataEscaped = str_replace("'", "''", $lieferscheinNr);
        // Für die URL encoden (Spaces, /, etc.)
        $encodedValue = rawurlencode($odataEscaped);

        // Achtung: In sprintf müssen alle bestehenden % als %% geschrieben werden (außer %s).
        $url = sprintf(
            "https://sieverding-sandbox.faveo365.com:9248/NSTSUBSCRIPTIONSODATA/ODatav4/Company('Sieverding%%20Besitzunternehmen')/ShopLieferscheinArtikel?tenant=x7069851800471529774&\$filter=DocumentNo%%20eq%%20'%s'",
            $encodedValue
        );

        $response = Http::timeout(20)
            ->withBasicAuth('testuser', 'Sieverding22!') // Benutzername & Kennwort
            ->withHeaders([
                'Accept' => 'application/json;odata.metadata=none',
            ])
            ->get($url);

        if ($response->successful()) {
            // Mehrzeilig für dein Textfeld
            return json_encode($response->json(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }

        // Fehler als JSON zurückgeben (damit du es im Textfeld siehst)
        return json_encode([
            'error' => [
                'status'  => $response->status(),
                'message' => $response->body(),
                'url'     => $url,
            ],
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    function formatLsDocNo(int|string $eingabe): string{
        $s = trim((string)$eingabe);

        if (!preg_match('/^\d{1,8}$/', $s)) {
            throw new InvalidArgumentException('Eingabe muss aus 1–8 Ziffern bestehen.');
        }

        return 'LS' . str_pad($s, 8, '0', STR_PAD_LEFT);
    }


    public function readLieferschein(){
        \Log::info('readLieferschein()');
        $lieferscheinNr = $this->formatLsDocNo($this->lieferscheinNr);

        $result = $this->fetchLieferscheinAlsJson($lieferscheinNr);
        dd($result);
        $this->jsonResult     = $result['table'];     // Anzeige
        $this->clipboardValue = $result['clipboard']; // nur ArtikelNr + Lagerort
    }



    private function getLieferscheinFromWebservice($nr)
    {
        // Beispiel-Dummy-Daten. In echt: Webservice-Call machen.
        return [
            'value' => [
                ['ArtikelNr' => '72039591', 'LagerOrt' => '3000', 'Menge' => 6],
                ['ArtikelNr' => '72038694', 'LagerOrt' => '3000', 'Menge' => 5],
                ['ArtikelNr' => '72039791', 'LagerOrt' => '3000', 'Menge' => 15],
            ],
        ];
    }

    public function render()
    {
        return view('livewire.warenzugang-buchen')->layout('layouts.app');
    }
}
