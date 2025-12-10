<?php

namespace App\Repositories;

use App\Models\Psp;


class WarenzugangRepository
{



    public static function parseArtikelText(string $artikeltext): array
{
    $ergebnis = [];
    $zeilen = preg_split('/\r\n|\r|\n/', trim($artikeltext));

    foreach ($zeilen as $index => $zeile) {

        // Leere Zeilen überspringen
        if (trim($zeile) === '') {
            continue;
        }

        $teile = array_map('trim', explode(';', $zeile));

        // Erwartet: ArtikelNr;Abladestelle;Lagerort;Lagerplatz;Menge
        if (count($teile) !== 5) {
            $ergebnis[] = [
                'raw' => $zeile,
                'index' => $index + 1,
                'valid' => false,
                'error' => 'Ungültiges Format – erwartet 5 Werte.'
            ];
            continue;
        }

        [$artikelnummer, $abladestelle, $lagerort, $lagerplatz, $menge] = $teile;

        // Grundvalidierungen
        if (!is_numeric($artikelnummer)) {
            $ergebnis[] = [
                'raw' => $zeile,
                'index' => $index + 1,
                'valid' => false,
                'error' => 'Artikelnummer ist ungültig.'
            ];
            continue;
        }

        if (!is_numeric($menge) || (int)$menge <= 0) {
            $ergebnis[] = [
                'raw' => $zeile,
                'index' => $index + 1,
                'valid' => false,
                'error' => 'Menge muss eine positive Zahl sein.'
            ];
            continue;
        }

        // Wenn alles passt → strukturiert speichern
        $ergebnis[] = [
            'index'         => $index + 1,
            'valid'         => true,
            'artikelnummer' => $artikelnummer,
            'abladestelle'  => $abladestelle,
            'lagerort'      => $lagerort,
            'lagerplatz'    => $lagerplatz,
            'menge'         => (int)$menge,
        ];
    }

    return $ergebnis;
}


}
