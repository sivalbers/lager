<?php

namespace App\Repositories;

use App\Models\Artikel;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use UnexpectedValueException;


class ArtikelRepository
{
    public function createArtikel($nr, $bez, $einheit, $materialgruppe, $ekpreis){
        $artikel = Artikel::where('artikelnr', $nr)->first();
        if (!$artikel){
            $artikel = new Artikel();
        }

        $artikel->artikelnr = $nr;
        $artikel->bezeichnung = $bez;
        $artikel->einheit = $einheit;
        $artikel->materialgruppe = $materialgruppe;
        $artikel->ekpreis = $ekpreis;


        $artikel->save();
        return $artikel;
    }

    public function getArtikel($nr){
        $artikel = Artikel::where('artikelnr', $nr)->first();
        if ($artikel)
            return $artikel;
        else
            return null;
    }

    private function holeArtikelausfaveo($nr){
        $url = "https://sieverding-sandbox.faveo365.com:9248/NSTSUBSCRIPTIONSODATA/ODatav4/Company('Sieverding%%20Besitzunternehmen')/einkartikel?tenant=x7069851800471529774&\$filter=nr%%20eq%%20'%s'";
        $user     = 'testuser';
        $key      = 'Sieverding22!';

        $user     = 'EWEWEBSERVICEUSER';
        $key      = 'Sieverding22!3242a_.';


        $odataEscaped = str_replace("'", "''", $nr);
        $encodedValue = rawurlencode($odataEscaped);

        // Achtung: In sprintf müssen alle bestehenden % als %% geschrieben werden (außer %s).
        $url = sprintf( $url, $encodedValue );

        $response = Http::timeout(20)
            ->withBasicAuth($user, $key) // Benutzername & Kennwort
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

    private function extractArtikel(string $json){
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        $resultText = '';

        if (!isset($data['value']) || !is_array($data['value'])) {
            throw new UnexpectedValueException("Erwarte Feld 'value' als Array.");
        }

        $out = [];
        $artikelNr = '';

        foreach ($data['value'] as $row) {
            if (!is_array($row)) {
                continue; // oder Exception werfen – je nach gewünschter Strenge
            }

            if (!array_key_exists('nr', $row)) {
                throw new UnexpectedValueException("Feld 'nr' fehlt in einem Eintrag.");
            }
            if (!array_key_exists('beschreibung', $row)) {
                throw new UnexpectedValueException("Feld 'beschreibung' fehlt in einem Eintrag.");
            }
            if (!array_key_exists('basisEinheit', $row)) {
                throw new UnexpectedValueException("Feld 'basisEinheit' fehlt in einem Eintrag.");
            }
            if (!array_key_exists('artikelKategorie', $row)) {
                throw new UnexpectedValueException("Feld 'artikelKategorie' fehlt in einem Eintrag.");
            }
            if (!array_key_exists('ekpreis', $row)) {
                throw new UnexpectedValueException("Feld 'ekpreis' fehlt in einem Eintrag.");
            }

            $artikelNr = (string)$row['nr'];
            $beschreibung  = (string)$row['beschreibung'];
            $basisEinheit  = (string)$row['basisEinheit'];
            $artikelKategorie  = (string)$row['artikelKategorie'];
            // Menge kann in BC dezimal sein → als float casten
            $ekpreis     = is_numeric($row['ekpreis']) ? (float)$row['ekpreis'] : 0.0;

        }
        $materialgruppe = '';

        if ($artikelNr === ''){
            return null;
        }

        if (substr($artikelNr, 0, 1) === '2') {
            $materialgruppe = 'Strom';
        }
        else
        if (substr($artikelNr, 0, 1) === '3') {
            $materialgruppe = 'TK';
        }
        else
        if (substr($artikelNr, 0, 1) === '5') {
            $materialgruppe = 'GAS';
        }
        else
        if (substr($artikelNr, 0, 2) === '73') {
            $materialgruppe = 'Wasser';
        }
		else
        if (substr($artikelNr, 0, 2) === '75') {
            $materialgruppe = 'Wasser';
        }
		else
        if (substr($artikelNr, 0, 2) === '76') {
            $materialgruppe = 'Wasser';
        }
		else
        if (substr($artikelNr, 0, 2) === '77') {
            $materialgruppe = 'Wasser';
        }


        return $this->createArtikel($artikelNr, $beschreibung, $basisEinheit, $materialgruppe, $ekpreis);

    }

    public function holeArtikel($artikelnr){
        return $this->extractArtikel($this->holeArtikelausfaveo($artikelnr));
    }


    public function getArtikelOrLoadFromFaveo($artikelnr){
        $artikel = $this->getArtikel($artikelnr);
        if (empty($artikel)){
            $this->holeArtikel($artikelnr);
            return $this->getArtikel($artikelnr);
        }else{
            return $artikel;
        }
    }

}


