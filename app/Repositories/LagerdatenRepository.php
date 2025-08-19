<?php

namespace App\Repositories;

use App\Models\Lagerdaten;


class LagerdatenRepository
{
    public function createLagerdaten($nr, $lagernr, $lagerplatz, $bestand){
        $lagerdaten = Lagerdaten::where('artikelnr', $nr)->where('lagernr', $lagernr)->where('lagerplatz', $lagerplatz)->first();
        if (!$lagerdaten){
            $lagerdaten = new Lagerdaten();
        }

        $lagerdaten->artikelnr = $nr;
        $lagerdaten->lagernr = $lagernr;
        $lagerdaten->lagerplatz = $lagerplatz;
        $lagerdaten->bestand = $bestand;


        $lagerdaten->save();
    }
}
