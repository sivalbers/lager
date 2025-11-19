<?php

if (!function_exists('bestellrhythmus_text')) {
    /**
     * Gibt den Bestellrhythmus als lesbaren Text zurück.
     *
     * @param  int|string|null  $wert
     * @return string
     */
    function bestellrhythmus_text($wert)
    {
        if (empty($wert) || $wert === "0") {
            return 'Manuell';
        }

        // Stelle sicher, dass der Wert als String verarbeitet wird (z. B. "23")
        $wert = strval($wert);

        $wochentage = [
            '1' => 'Montag',
            '2' => 'Dienstag',
            '3' => 'Mittwoch',
            '4' => 'Donnerstag',
            '5' => 'Freitag',
        ];

        $wochenfolge = [
            '1' => '1.',
            '2' => '2.',
            '3' => '3.',
            '4' => '4.',
        ];

        $tag = $wert[0] ?? null;  // z. B. '2'
        $folge = $wert[1] ?? null; // z. B. '3'

        if (!isset($wochentage[$tag]) || !isset($wochenfolge[$folge])) {
            return 'Unbekannt';
        }

        return "{$wochenfolge[$folge]} {$wochentage[$tag]} iM";
    }
}

