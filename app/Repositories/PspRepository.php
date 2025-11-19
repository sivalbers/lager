<?php

namespace App\Repositories;

use App\Models\Psp;


class PspRepository
{
    public static function findePspDatensatz($netzregion, $kostenstelle, $artikel, $materialgruppe)
    {
        $allePsp = Psp::all();

        $gefunden = $allePsp->first(function ($psp) use ($netzregion, $kostenstelle, $artikel, $materialgruppe) {
            // Netzregion prüfen
            if ($psp->netzregion !== '*' && $psp->netzregion !== $netzregion) {
                return null;
            }

            // Kostenstelle prüfen
            if ($psp->kostenstelle !== '*' && $psp->kostenstelle !== $kostenstelle) {
                return null;
            }

            // Materialgruppe prüfen
            if ($psp->materialgruppe !== '*' && $psp->materialgruppe !== $materialgruppe) {
                return null;
            }

            // Artikel prüfen
            if ($psp->artikel === '*') {
                return $psp;
            }

            if (str_contains($psp->artikel, '..')) {
                // Bereich
                [$start, $ende] = explode('..', $psp->artikel);
                if ($artikel >= $start && $artikel <= $ende) {
                    return $psp;
                }
                return null;
            }

            return $psp;;
        });

        return $gefunden;
    }

}
