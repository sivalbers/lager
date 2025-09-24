<?php

namespace App\Repositories;

use App\Models\Artikel;


class ArtikelRepository
{
    public function createArtikel($nr, $bez, $einheit){
        $artikel = Artikel::where('artikelnr', $nr)->first();
        if (!$artikel){
            $artikel = new Artikel();
        }

        $artikel->artikelnr = $nr;
        $artikel->bezeichnung = $bez;
        $artikel->einheit = $einheit;

        $artikel->save();
    }

    public function getArtikel($nr){
        $artikel = Artikel::where('artikelnr', $nr)->first();
        if ($artikel)
            return $artikel;
        else
            return null;
    }
}


