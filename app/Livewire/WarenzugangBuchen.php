<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Repositories\ArtikelRepository;
use App\Repositories\BestandsverwaltungRepository;
use App\Repositories\BestandsbuchungRepository;
use App\Repositories\WarenzugangRepository;
use Mary\Traits\Toast;

use App\Models\Lagerort;

class WarenzugangBuchen extends Component
{

    use Toast;

    public $artikelliste;

    public $jsonResult = "";
    public $clipboardValue = "";
    public $lieferscheinNr = "70678";
    private $url = "https://sieverding-sandbox.faveo365.com:9248/NSTSUBSCRIPTIONSODATA/ODatav4/Company('Sieverding%20Besitzunternehmen')/ShopLieferscheinArtikel?tenant=x7069851800471529774&\$filter=DocumentNo%20eq%20'%s'";

    protected $listeners = ['setJsonResult' => 'updateJsonResult'];
    protected ?string $user;
    protected ?string $key;

    public $positionen = [];
    public $message = '';

    public $abladestellenList = [];
    public $lagerortList = [];

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

        $br = new BestandsverwaltungRepository();

        $this->abladestellenList = $br->abladestellenVonUser();

    }

    public function msg_readLieferscheinOK(){
        $this->success(

            title: 'Lieferschein wurde geladen',
            description: null,                  // optional (text)
            position: 'toast-top toast-start',    // optional (daisyUI classes)
            icon: 'o-information-circle',       // Optional (any icon)
            css: 'alert-info',                  // Optional (daisyUI classes)
            timeout: 3000,                      // optional (ms)
            redirectTo: null                    // optional (uri)
        );

    }

    public function msg_readLieferscheinERR(){

        $this->error(
            title: 'Lieferschein wurde nicht geladen',
            description: 'Lieferschein wurde nicht geladen! <br> Entweder ist er nicht vorhanden, oder Sie haben nicht die Berechtigung.',                  // optional (text)
            position: 'toast-top toast-center',    // optional (daisyUI classes)
            icon: 'o-information-circle',       // Optional (any icon)
            css: 'alert-info btn-error',                  // Optional (daisyUI classes)
            timeout: 10000,                      // optional (ms)
            redirectTo: null                    // optional (uri)
        );
    }


    public function msg_Buchunggepeichert(){

        $this->error(
            title: 'Warenzugang wurde gebucht!',
            description: 'Der Warenzugang wurde erfolgreich gebucht.',                  // optional (text)
            position: 'toast-top toast-center',    // optional (daisyUI classes)
            icon: 'o-information-circle',       // Optional (any icon)
            css: 'alert-info btn-error',                  // Optional (daisyUI classes)
            timeout: 10000,                      // optional (ms)
            redirectTo: null                    // optional (uri)
        );
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

        $debitorNr = Auth()->user()->debitor_nr;
        // Achtung: In sprintf müssen alle bestehenden % als %% geschrieben werden (außer %s).
        /*
        $url = sprintf(
            "https://sieverding-sandbox.faveo365.com:9248/NSTSUBSCRIPTIONSODATA/ODatav4/Company('Sieverding%%20Besitzunternehmen')/ShopLieferscheinArtikel?tenant=x7069851800471529774&\$filter=DocumentNo%%20eq%%20'%s'",
            $encodedValue
        );
        */

        $url = sprintf(
            "https://sieverding-sandbox.faveo365.com:9248/NSTSUBSCRIPTIONSODATA/ODatav4/Company('Sieverding%%20Besitzunternehmen')/ShopLieferscheinArtikel?tenant=x7069851800471529774&\$filter=DocumentNo%%20eq%%20'%s'%%20and%%20DebitorNr%%20eq%%20'%s'",
            $encodedValue,
            $debitorNr
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


    /*
        Lädt die Lieferscheinpositionen aus faveo => (fetchLieferscheinAlsJson)
        Konvertiert die Positionen in ein Array => (ladePositionenAusJson)
        Importiert die Artikel aus faveo und legt diese an => (importArtikelFormFaveo)
    */

    public function readLieferschein(){
        \Log::info('readLieferschein()');
        $lieferscheinNr = $this->formatLsDocNo($this->lieferscheinNr);

        $result = $this->fetchLieferscheinAlsJson($lieferscheinNr);

        $this->ladePositionenAusJson($result);

        if (count($this->positionen) > 0 ){

            $this->importArtikelFormFaveo();
            // $this->msg_readLieferscheinOK();
        }
        else
        {
            $this->msg_readLieferscheinERR();
        }
    }

    public function ladePositionenAusJson($json)
    {
        $daten = json_decode($json, true)['value'];

        $abladestelle = 0;
        if ($this->abladestellenList->count() === 1) {
           $abladestelle = $this->abladestellenList->first()->id;
        }
        $lagerort = 0 ;

        if ($abladestelle != 0){
            $lagerorte = $this->loadLagerorte($abladestelle);
            if ( count($lagerorte) == 1){
                $lagerort = $lagerorte[0]['id'];
            }
        }


        $this->positionen = collect($daten)->map(function ($item ) use ($abladestelle, $lagerort) {



            return [
                'artikelnr' => $item['ArtikelNr'],
                'bezeichnung' => '', // optional: aus DB holen
                'einheit' => '',     // optional: aus DB holen
                'abladestelle' => $abladestelle,
                'lagerort' => $lagerort,
                'lagerorte' => [],
                'lagerplatz' => '',  // vom Benutzer zu füllen

                'menge' => $item['Menge'],
                'etikett' => false,
                'lieferscheinnr' => $this->lieferscheinNr
            ];
        })->toArray();

        foreach ($this->positionen as $index => $pos) {
            if ($pos['abladestelle']) {
                $lagerorte = $this->loadLagerorte($pos['abladestelle']);
                $this->positionen[$index]['lagerorte'] = $lagerorte;

                if (count($lagerorte) === 1) {
                    $this->positionen[$index]['lagerort'] = $lagerorte[0]['id'];
                }
            }
        }


    }


    public function importArtikelFormFaveo(){
        // dd($this->positionen);
        $artikelRepository = new ArtikelRepository();
        foreach ($this->positionen as $key => $positon){
            $artikel = $artikelRepository->getArtikelOrLoadFromFaveo( $this->positionen[$key]['artikelnr'] );
            if (!empty($artikel)){
                $this->positionen[$key]['bezeichnung'] = $artikel->bezeichnung;
                $this->positionen[$key]['einheit'] = $artikel->einheit;
            }
            else
            {
                $this->positionen[$key]['bezeichnung'] = 'Artikel existiert nicht';
            }
        }
    }

    public function loadLagerorte($abladestelleId)
    {
        // Beispiel:
        return Lagerort::where('abladestelle_id', $abladestelleId)
            ->select('id', 'bezeichnung')
            ->get()
            ->toArray();
    }

    public function updatedPositionen($value, $key)
    {
        // $key hat z. B. den Wert "1.abladestelle"
        if (str_ends_with($key, 'abladestelle')) {
            [$index, ] = explode('.', $key);
            $abladestelle = $this->positionen[$index]['abladestelle'];

            if ($abladestelle) {
                $lagerorte = $this->loadLagerorte($abladestelle);
                $this->positionen[$index]['lagerorte'] = $lagerorte;
                if (count($lagerorte) === 1) {
                    $this->positionen[$index]['lagerort'] = $lagerorte[0]['id'];
                } else {
                    $this->positionen[$index]['lagerort'] = '';
                }
            }
        }
    }

    public function weBuchen(){

        $buch = new BestandsbuchungRepository();
        foreach ($this->positionen as $index => $pos) {
            $buch->BucheBestand( $pos['artikelnr'], $pos['abladestelle'], $pos['lagerort'], $pos['lagerplatz'], $pos['menge'], 'warenzugang', $pos['lieferscheinnr'], $pos['etikett'] );
        }
        $this->positionen = [];
         $this->msg_Buchunggepeichert();
    }

    public function render()
    {
        return view('livewire.warenzugang-buchen')->layout('layouts.app');
    }

    public function checkArtikelliste(){
        $liste = WarenzugangRepository::parseArtikelText($this->artikelliste);
        $this->artikelliste = "Geprüft ...";
    }


    public function readArtikelliste(){
        $this->artikelliste = "Verarbeitet ...";
    }


}
