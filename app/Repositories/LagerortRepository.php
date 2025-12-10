<?php

namespace App\Repositories;

use App\Models\Lagerort;


class LagerortRepository
{
    public function createLagerort($nr, $bez){

        $lagerort = Lagerort::where('nr', $nr)->first();
        if (!$lagerort){
            $lagerort = new Lagerort();
            $lagerort->bezeichnung = $bez;
            $lagerort->nr = $nr;
            $lagerort->save();

        }

    }

public static function existiertLagerortBezUndAbladestelle(string $lagerort, string $abladestelle): bool
{
    return Lagerort::query()
        ->join('abladestellen', 'abladestellen.id', '=', 'lagerorte.abladestelle_id')
        ->whereRaw('LOWER(lagerorte.bezeichnung) = ?', [mb_strtolower(trim($lagerort))])
        ->whereRaw('LOWER(abladestellen.name) = ?', [mb_strtolower(trim($abladestelle))])
        ->exists();
}


}
