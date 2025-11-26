<?php

namespace App\Repositories;

use App\Models\Abladestelle;
use App\Models\Artikel;
use App\Models\Artikelbestand;


class BestandsverwaltungRepository
{

    public function abladestellenArray()
    {
        $user = auth()->user();
        return $user->abladestellen->pluck('id')->toArray();
    }

    public function abladestellenVonUser()
    {
        $user = auth()->user();
        return $user->abladestellen;
    }

    
    // Liste aller Artikel im Bestand des angemeldeten Users
    public function artikelArrayAusBestandInAbladestellenVonUser(){
        $abladestellen_id_array = $this->abladestellenArray();
        return Artikelbestand::whereIn('abladestelle_id', $abladestellen_id_array)->distinct()->pluck('artikelnr')->toArray();
    }

    public function artikelArrayAusBestandInAbladestelle($abladestelle_id){

        return Artikelbestand::where('abladestelle_id', $abladestelle_id)->distinct()->pluck('artikelnr')->toArray();
    }


    public function artikelBestandInAbladestellen( $abladestellen_id_array)
    {
        $artikel = Artikelbestand::whereIn('abladestelle_id', $abladestellen_id_array)
            ->get();
        return $artikel;
    }


public function artikelArrayAusBestand_artikel_abladestellen_lagerorte($artikelnr = '', $abladestellen_id_array = [], $lagerorte_id_array = [])
{

        \Log::info('Starte Artikelabfrage');
    \Log::info('Suchbegriff Artikelnr: ' . $artikelnr);
    \Log::info('Abladestellen-IDs: ', $abladestellen_id_array);
    \Log::info('Lagerorte-IDs: ', $lagerorte_id_array);


    $query = Artikelbestand::query();

    if (!empty($artikelnr)) {
        $query->where('artikelnr', 'like', "%{$artikelnr}%");
    }

    if (!empty($abladestellen_id_array)) {
        $query->whereIn('abladestelle_id', $abladestellen_id_array);
    }

    if (!empty($lagerorte_id_array)) {
        $query->whereIn('lagerort_id', $lagerorte_id_array);
    }

    $result = $query->distinct()->pluck('artikelnr')->toArray();

    return $result;
}

}
