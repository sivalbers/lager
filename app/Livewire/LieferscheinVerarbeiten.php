<?php
declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Str;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;

use InvalidArgumentException;
use UnexpectedValueException;
use App\Repositories\ArtikelRepository;

class LieferscheinVerarbeiten extends Component
{

    public $jsonResult = "";
    public $clipboardValue = "";
    public $lieferscheinNr = "70824";
    private $url = "https://sieverding-sandbox.faveo365.com:9248/NSTSUBSCRIPTIONSODATA/ODatav4/Company('Sieverding%20Besitzunternehmen')/ShopLieferscheinArtikel?tenant=x7069851800471529774&\$filter=DocumentNo%20eq%20'%s'";

    protected $listeners = ['setJsonResult' => 'updateJsonResult'];
    protected ?string $user;
    protected ?string $key;
    protected $positionen;


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

    public function render()
    {
        return view('livewire.lieferschein-verarbeiten')
            ->layout('layouts.app');
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

        $result = $this->extractArtikelLagerMenge($this->fetchLieferscheinAlsJson($lieferscheinNr));
        $this->jsonResult     = $result['table'];     // Anzeige
        $this->clipboardValue = $result['clipboard']; // nur ArtikelNr + Lagerort


    }


    function extractArtikelLagerMenge(string $json){
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        $resultText = '';

        if (!isset($data['value']) || !is_array($data['value'])) {
            throw new UnexpectedValueException("Erwarte Feld 'value' als Array.");
        }

        $out = [];
        $artRepository = new ArtikelRepository();

        foreach ($data['value'] as $row) {
            if (!is_array($row)) {
                continue; // oder Exception werfen – je nach gewünschter Strenge
            }

            if (!array_key_exists('ArtikelNr', $row)) {
                throw new UnexpectedValueException("Feld 'ArtikelNr' fehlt in einem Eintrag.");
            }
            if (!array_key_exists('LagerOrt', $row)) {
                throw new UnexpectedValueException("Feld 'LagerOrt' fehlt in einem Eintrag.");
            }
            if (!array_key_exists('Menge', $row)) {
                throw new UnexpectedValueException("Feld 'Menge' fehlt in einem Eintrag.");
            }

            $artikelNr = (string)$row['ArtikelNr'];
            $lagerort  = (string)$row['LagerOrt'];
            // Menge kann in BC dezimal sein → als float casten
            $menge     = is_numeric($row['Menge']) ? (float)$row['Menge'] : 0.0;
            $artikel = $artRepository->getArtikel($artikelNr);
            $out[] = [
                'ArtikelNr' => $artikelNr,
                'Bezeichnung' => (!empty($artikel)) ? $artikel->bezeichnung : '',
                'Einheit' => (!empty($artikel)) ? $artikel->einheit : '',
                'Lagerort'  => $lagerort,  // gewünschte Schreibweise
                'Menge'     => $menge,
            ];

        }

        $resultText  = mb_str_pad('ArtikelNr', 12)
                    . mb_str_pad('Bezeichnung', 50)
                    . mb_str_pad('Lagerort', 10)
                    . mb_str_pad('Menge', 8)
                    . 'Einheit' . PHP_EOL;

        foreach ($out as $o) {
            $resultText .= mb_str_pad($o['ArtikelNr'], 12)
                        . mb_str_pad($o['Bezeichnung'], 50)
                        . mb_str_pad($o['Lagerort'], 10)
                        . mb_str_pad((string)$o['Menge'], 8)
                        . $o['Einheit'] . PHP_EOL;
        }

        // Zweite Ausgabe nur für Clipboard
        $clipboard = '';
        foreach ($out as $o) {
            $clipboard .= $o['ArtikelNr'] . ", " . $o['Lagerort'] . PHP_EOL;
        }

        // Beides zurückgeben (z. B. als Array)
        return [
            'table'     => $resultText,   // fürs Anzeigen
            'clipboard' => $clipboard,    // fürs Kopieren
        ];
    }

    /**
     * UTF-8 sicheres str_pad
     */
    function mb_str_pad(string $string, int $length, string $padStr = " ", int $padType = STR_PAD_RIGHT, string $encoding = "UTF-8"): string {
        $strLen = mb_strlen($string, $encoding);
        $padLen = $length - $strLen;

        if ($padLen <= 0) {
            return $string;
        }

        switch ($padType) {
            case STR_PAD_LEFT:
                return str_repeat($padStr, $padLen) . $string;
            case STR_PAD_BOTH:
                $left  = floor($padLen / 2);
                $right = $padLen - $left;
                return str_repeat($padStr, $left) . $string . str_repeat($padStr, $right);
            case STR_PAD_RIGHT:
            default:
                return $string . str_repeat($padStr, $padLen);
        }
    }


}
