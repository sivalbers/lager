<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Artikel;
use App\Models\Artikeleinrichtung;
use App\Models\Abladestelle;
use App\Models\Lagerort;
use App\Repositories\ArtikelRepository;
use Illuminate\Support\Facades\Auth;

class ArtikelListen extends Component
{
    public $artikel;
    public $showArtikel = false;
    public $isEditArtikel = false;

    public $artikelnr = '100100';
    public $artikelBezeichnung;
    public $artikelEinheit;
    public $artikelMaterialgruppe;
    public $artikelEkpreis;

    public $einrichtungId;
    public $abladestelle_id;
    public $lagerort_id;
    public $mindestbestand;
    public $bestellmenge;
    public $bestellrhythmus;
    public $abladestellenspezifisch = false;


    public $showEinrichtung = false;
    public $isEditEinrichtung = false;

    public $abladestellen;
    public $lagerortAuswahl;


    public $confirmingDelete = false;
    public $deleteEinrichtungId = 0;




    public function mount()
    {
        $this->loadArtikel();
        $this->lagerortAuswahl = Lagerort::orderBy('bezeichnung')->get();
        $user = User::findOrFail(Auth::id());
        $abladestelle_ids = $user->abladestellen->pluck('id')->toArray();

        $this->abladestellen = Abladestelle::whereIn('id', $abladestelle_ids)->get();
    }

    public function render()
    {
        \Log::info(['render()' => 'x', 'artikel' => $this->artikelnr ]);
        return view('livewire.artikel-listen')->layout('layouts.app');
    }

    public function loadArtikel()
    {
        $this->artikel = Artikel::with('einrichtungen.abladestelle.lagerorte')->orderBy('artikelnr')->get();
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
            $this->artikelEkpreis = $artikel->ekpreis;
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
                'materialgruppe' => $this->artikelMaterialgruppe,
                'ekpreis' => $this->artikelEkpreis,
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
            $this->lagerort_id = $einrichtung->lagerort_id;
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
                'lagerort_id' => $this->lagerort_id,
                'mindestbestand' => $this->mindestbestand,
                'bestellmenge' => $this->bestellmenge,
                'bestellrhythmus' => $this->bestellrhythmus,
                'abladestellenspezifisch' => $this->abladestellenspezifisch,
               // 'naechstes_belieferungsdatum' => ($this->naechstes_belieferungsdatum) ? \Carbon\Carbon::parse($this->naechstes_belieferungsdatum) : null,
            ]
        );

        $this->loadArtikel();
    }

    public function loadFromFaveo($artikelnr){

        $artikelRepository = new ArtikelRepository();
        $artikel = $artikelRepository->holeArtikel($artikelnr);
        if ($artikel){
            $this->artikelnr = $artikel->artikelnr;
            $this->artikelBezeichnung = $artikel->bezeichnung;
            $this->artikelEinheit = $artikel->einheit;
            $this->artikelMaterialgruppe = $artikel->materialgruppe;
            $this->artikelEkpreis = $artikel->ekpreis;
        }
        else
        {
            $this->artikelBezeichnung = 'Das hat nicht funktionert';
            $this->artikelnr = $artikel->artikelnr;
            $this->artikelBezeichnung = $artikel->bezeichnung;
            $this->artikelEinheit = $artikel->einheit;
            $this->artikelMaterialgruppe = $artikel->materialgruppe;
            $this->artikelEkpreis = $artikel->ekpreis;
        }
    }

    public function confirmDelete($id)
    {
        $this->confirmingDelete = true;
        $this->deleteEinrichtungId = $id;
    }

    public function deleteEinrichtung()
    {
        Artikeleinrichtung::findOrFail($this->deleteEinrichtungId)->delete();
        $this->confirmingDelete = false;
        $this->deleteEinrichtungId = null;
        
    }

}
