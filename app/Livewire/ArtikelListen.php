<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Artikel;
use App\Models\Artikeleinrichtung;
use App\Models\Abladestelle;

class ArtikelListen extends Component
{
    public $artikel;
    public $showArtikel = false;
    public $isEditArtikel = false;

    public $artikelnr;
    public $artikelBezeichnung;
    public $artikelEinheit;
    public $artikelMaterialgruppe;

    public $einrichtungId;
    public $abladestelle_id;
    public $lagerort;
    public $mindestbestand;
    public $bestellmenge;
    public $bestellrhythmus;
    public $abladestellenspezifisch = false;


    public $showEinrichtung = false;
    public $isEditEinrichtung = false;

    public $abladestellen;

    public function mount()
    {
        $this->loadArtikel();
        $this->abladestellen = Abladestelle::all();
    }

    public function render()
    {
        return view('livewire.artikel-listen')->layout('layouts.app');
    }

    public function loadArtikel()
    {
        $this->artikel = Artikel::with('einrichtungen.abladestelle')->orderBy('artikelnr')->get();
    }

    public function editArtikel($create = true, $nr = null)
    {
        $this->isEditArtikel = !$create;
        $this->showArtikel = true;

        if ($create) {
            $this->artikelnr = $this->artikelBezeichnung = $this->artikelEinheit = $this->artikelMaterialgruppe = '';
        } else {
            $artikel = Artikel::find($nr);
            $this->artikelnr = $artikel->artikelnr;
            $this->artikelBezeichnung = $artikel->bezeichnung;
            $this->artikelEinheit = $artikel->einheit;
            $this->artikelMaterialgruppe = $artikel->materialgruppe;
        }
    }

    public function saveArtikel()
    {
        $this->showArtikel = false;

        $artikel = Artikel::updateOrCreate(
            ['artikelnr' => $this->artikelnr],
            [
                'bezeichnung' => $this->artikelBezeichnung,
                'einheit' => $this->artikelEinheit,
                'materialgruppe' => $this->artikelMaterialgruppe
            ]
        );

        $this->loadArtikel();
    }

    public function editEinrichtung($create = true, $id = null, $artikelnr = null)
    {
        $this->isEditEinrichtung = !$create;
        $this->showEinrichtung = true;


        if ($create) {
            $artikel = Artikel::find($artikelnr);
            $this->einrichtungId = null;
            $this->artikelnr = $artikelnr;
            $this->artikelBezeichnung = $artikel->bezeichnung;
            $this->abladestelle_id = 0;
            $this->abladestellenspezifisch = false;
        } else {
            $einrichtung = Artikeleinrichtung::find($id);

            $this->einrichtungId = $einrichtung->id;
            $this->artikelnr = $einrichtung->artikelnr;
            $this->artikelBezeichnung = $einrichtung->artikel->bezeichnung;
            $this->abladestelle_id = $einrichtung->abladestelle_id;
            $this->lagerort = $einrichtung->lagerort;
            $this->mindestbestand = $einrichtung->mindestbestand;
            $this->bestellmenge = $einrichtung->bestellmenge;
            $this->abladestellenspezifisch = $einrichtung->abladestellenspezifisch;


        }
    }

    public function saveEinrichtung()
    {
        $this->showEinrichtung = false;

        Artikeleinrichtung::updateOrCreate(


            ['id' => $this->einrichtungId],
            [
                'artikelnr' => $this->artikelnr,
                'abladestelle_id' => ($this->abladestelle_id==="") ? 0 : $this->abladestelle_id,
                'lagerort' => $this->lagerort,
                'mindestbestand' => $this->mindestbestand,
                'bestellmenge' => $this->bestellmenge,
                'bestellrhythmus' => $this->bestellrhythmus,
                'abladestellenspezifisch' => $this->abladestellenspezifisch,
               // 'naechstes_belieferungsdatum' => ($this->naechstes_belieferungsdatum) ? \Carbon\Carbon::parse($this->naechstes_belieferungsdatum) : null,
            ]
        );

        $this->loadArtikel();
    }
}
