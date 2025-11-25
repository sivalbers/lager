<?php

namespace App\Repositories;

use App\Models\Protokoll;
use App\Models\Artikelbestand;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class BestandsbuchungRepository
{
    function createProtokoll($nr, $abladestelle_id, $lagerort_id, $lagerplatz, $menge, $buchungsgrund_id){
        $protokoll = new Protokoll();
        $protokoll->datum_zeit = now();
        $protokoll->user_id = Auth::user()->id;
        $protokoll->artikelnr = $nr;
        $protokoll->abladestelle_id = $abladestelle_id;
        $protokoll->lagerort_id = $lagerort_id;
        $protokoll->lagerplatz = $lagerplatz;
        $protokoll->menge = $menge ;
        $protokoll->buchungsgrund_id = $buchungsgrund_id;

        $protokoll->save();

        return $protokoll->id;
    }

    function _bucheBestand($artikelnr, $abladestelle_id, $lagerort_id, $lagerplatz, $menge){
        $bestand = Artikelbestand::
                    where('artikelnr', $artikelnr)->
                    where('abladestelle_id', $abladestelle_id)->
                    where('lagerort_id', $lagerort_id)->
                    where('lagerplatz', $lagerplatz)->first();

        if ($bestand){
            Log::info(['Bestand Found' => $bestand->id, 'Lagerplatz' => $lagerplatz ]);
            $bestand->bestand = $bestand->bestand + $menge;
        }
        else{
            Log::info(['Bestand not Found']);
            $bestand = new Artikelbestand();
            $bestand->artikelnr = $artikelnr;
            $bestand->abladestelle_id = $abladestelle_id;
            $bestand->lagerort_id = $lagerort_id;
            $bestand->lagerplatz = $lagerplatz;
            $bestand->bestand = $menge;
        }
        $bestand->save();
        return $bestand->id;
    }

    public function BucheBestand($nr, $abladestelle_id, $lagerort_id, $lagerplatz, $menge, $modus){

        if ($modus === 'entnahme') {
            $menge = $menge * -1;
            $buchungsgrund_id = 1;
        }
        elseif ($modus === 'rueckgabe') {
            $buchungsgrund_id = 2;
        }
        elseif ($modus === 'korrektur'){
            $buchungsgrund_id = 3;
        }

        if ( $this->_bucheBestand($nr, $abladestelle_id, $lagerort_id, $lagerplatz, $menge) > 0){
            $this->createProtokoll($nr, $abladestelle_id, $lagerort_id, $lagerplatz, $menge, $buchungsgrund_id);
        }

    }
}
