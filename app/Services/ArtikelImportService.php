<?php

namespace App\Services;

use Throwable;
use App\Repositories\ArtikelRepository;
use App\Repositories\LagerortRepository;
use App\Repositories\LagerdatenRepository;

use App\Services\ODataReadBestand;


class ArtikelImportService
{

    private $items = [];

    public function importArtikel(){
        $client = new ODataReadBestand();
        $res = $client->get();
        $this->items = $res['value'] ?? [];

        $file = storage_path('app/import/artikel.json');

        // JSON speichern
        file_put_contents($file, json_encode($this->items));

        $this->importArtikelFromFile();

/*
        $artRepository = new ArtikelRepository();
        $lagerortRepository = new LagerortRepository();
        $lagerdatenRepository = new LagerdatenRepository();


        foreach($this->items as $item){

            $artRepository->createArtikel($item['itemNo'], $item['description'], $item['Base_Unit_of_Measure']);
            $lagerortRepository->createLagerort($item['locationCode'], 'undefiniert');
            $lagerdatenRepository->createLagerdaten($item['itemNo'], $item['locationCode'], '', $item['inventory']);

        }
*/
    }

    public function importArtikelFromFile()
    {
        $file = storage_path('app/import/artikel.json');
        $items = json_decode(file_get_contents($file), true);
        $artRepository = new ArtikelRepository();

        foreach ($items as $item) {
            $artRepository->createArtikel(
                $item['itemNo'],
                $item['description'],
                $item['Base_Unit_of_Measure']
            );
        }
    }

    public function importLagerorteFromFile()
    {
        $file = storage_path('app/import/artikel.json');
        $items = json_decode(file_get_contents($file), true);
        $lagerortRepository = new LagerortRepository();

        foreach ($items as $item) {
            $lagerortRepository->createLagerort(
                $item['locationCode'],
                'undefiniert'
            );
        }
    }

    public function importLagerdatenFromFile()
    {
        $file = storage_path('app/import/artikel.json');
        $items = json_decode(file_get_contents($file), true);
        $lagerdatenRepository = new LagerdatenRepository();

        foreach ($items as $item) {
            $lagerdatenRepository->createLagerdaten(
                $item['itemNo'],
                $item['locationCode'],
                '', // leerer Lagerplatz
                $item['inventory']
            );
        }
}

}

