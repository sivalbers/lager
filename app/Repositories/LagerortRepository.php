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
}
