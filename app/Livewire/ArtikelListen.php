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
use Illuminate\Support\Facades\Log;

class ArtikelListen extends Component
{
    public $artikel;
    public $showArtikel = false;
    public $isEditArtikel = false;

    public $artikelnr = '';
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
        $this->lagerortAuswahl = [];
        $user = User::findOrFail(Auth::id());

        if ($user->hasBerechtigung('seine artikel abladestellen einstellungen')){
            $abladestelle_ids = $user->abladestellen->pluck('id')->toArray();
        }
        else {
            $abladestelle_ids = Abladestelle::pluck('id')->toArray();
        }

        $this->abladestellen = Abladestelle::whereIn('id', $abladestelle_ids)->get();

    }

    public function render()
    {
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
            $this->artikelnr = 'x';
            $this->artikelBezeichnung = $this->artikelEinheit = $this->artikelMaterialgruppe = '';


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



        if ($create) {
            $artikel = Artikel::find($artikelnr);
            $this->einrichtungId = null;
            $this->artikelnr = $artikelnr;
            $this->artikelBezeichnung = $artikel->bezeichnung;
            $this->abladestelle_id = 0;
            $this->abladestellenspezifisch = true;
        } else {
            $einrichtung = Artikeleinrichtung::find($id);

            $this->einrichtungId = $einrichtung->id;
            $this->artikelnr = $einrichtung->artikelnr;
            $this->artikelBezeichnung = $einrichtung->artikel->bezeichnung;
            $this->abladestelle_id = $einrichtung->abladestelle_id;

            $this->mindestbestand = $einrichtung->mindestbestand;
            $this->bestellmenge = $einrichtung->bestellmenge;
            $this->abladestellenspezifisch = $einrichtung->abladestellenspezifisch;

            $this->lagerortAuswahl = Lagerort::where('abladestelle_id', $this->abladestelle_id )->orderBy('bezeichnung')->get();
            $this->lagerort_id = $einrichtung->lagerort_id;

        }

        $this->showEinrichtung = true;
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
            $this->artikelnr = '';
            $this->artikelBezeichnung = '';
            $this->artikelEinheit = '';
            $this->artikelMaterialgruppe = '';
            $this->artikelEkpreis = 0;
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

    public function updatedAbladestelleId()
    {
        \Log::info(['updatedAbladestelle_id' => $this->abladestelle_id ]);
        $this->lagerortAuswahl = Lagerort::where('abladestelle_id', $this->abladestelle_id )->orderBy('bezeichnung')->get();
        if (!$this->lagerortAuswahl){
            $this->lagerortAuswahl = [];
        }
    }

}
