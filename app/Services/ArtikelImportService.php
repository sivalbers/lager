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
        $artRepository = new ArtikelRepository();
        $lagerortRepository = new LagerortRepository();
        $lagerdatenRepository = new LagerdatenRepository();


        foreach($this->items as $item){

            $artRepository->createArtikel($item['itemNo'], $item['description'], '');
            $lagerortRepository->createLagerort($item['locationCode'], 'undefiniert');
            $lagerdatenRepository->createLagerdaten($item['itemNo'], $item['locationCode'], '', $item['inventory']);

        }

    }

}

