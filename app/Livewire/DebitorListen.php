<?php

namespace App\Livewire;


use Livewire\Component;
use App\Models\Debitor;
use App\Models\Abladestelle;
use App\Models\Lagerort;

class DebitorListen extends Component
{

    public $showDebitor = false;
    public $isEditDebitor = false;
    public $showAbladestelle = false;
    public $isEditAbladestelle = false;
    public $showLagerort = false;
    public $isEditLagerort = false;

    public $debitorNr;
    public $debitorName;
    public $debitorNetzregion;
    public $debitorAbladestelle_id;


    public $abladestelleId;
    public $abladestelleName;
    public $abladestelleName2;
    public $abladestelleStrasse;
    public $abladestellePlz;
    public $abladestelleOrt;
    public $abladestelleKostenstelle;
    public $abladestelleBestellrhythmus;


    public $lagerortId;
    public $lagerortAbladestelleId;
    public $lagerortBezeichnung;
    public $lagerortAbladestelleName;

    public $debitoren;


    public $edDebitor;
    public $edAbladestelle;
    public $edLagerort;

    public $berechtigung;


    public function mount(){
        $this->berechtigung = session('berechtigung');

        $this->loadDebitoren();

    }

    public function loadDebitoren(){
        $this->debitoren = Debitor::with('abladestellen')->orderBy('nr')->get();
    }

    public function render()
    {
        return view('livewire.debitor-listen')->layout('layouts.app');
    }

    public function editDebitor($doCreate, $debitornr = null){
        $this->isEditDebitor = !$doCreate;
        if ($doCreate){
            $this->edDebitor = new Debitor();
        }
        else {
            $this->edDebitor = Debitor::with('abladestellen')->where('nr', $debitornr)->first();
            $this->debitorNr            = $this->edDebitor->nr;
            $this->debitorName          = $this->edDebitor->name;
            $this->debitorNetzregion    = $this->edDebitor->netzregion;



        }
        $this->showDebitor = true ;

    }

    public function editAbladestelle($doCreate, $debitorNr, $Abladestelle_Id=null){
        $this->isEditAbladestelle = !$doCreate;

        if ($doCreate){
            $this->clearAbladestelle();
            $this->edAbladestelle = new Abladestelle();
            $this->debitorNr = $debitorNr;
            $this->debitorName = Debitor::where('nr', $debitorNr)->first()->name;

            if ($Abladestelle_Id){


                $this->edAbladestelle = Abladestelle::where('id', $Abladestelle_Id)->first();
                $this->abladestelleId         = null;
                $this->abladestelleName       = $this->edAbladestelle->name;
                $this->abladestelleName2      = $this->edAbladestelle->name2;
                $this->abladestelleStrasse    = $this->edAbladestelle->strasse;
                $this->abladestellePlz        = $this->edAbladestelle->plz;
                $this->abladestelleOrt        = $this->edAbladestelle->ort;
                $this->debitorNr              = $this->edAbladestelle->debitor_nr;

                $this->abladestelleKostenstelle = $this->edAbladestelle->kostenstelle;
                $this->abladestelleBestellrhythmus = $this->edAbladestelle->bestellrhythmus;

            }


        }
        else {
            $this->edAbladestelle = Abladestelle::where('id', $Abladestelle_Id)->first();
            $this->abladestelleId         = $this->edAbladestelle->id;
            $this->abladestelleName       = $this->edAbladestelle->name;
            $this->abladestelleName2      = $this->edAbladestelle->name2;
            $this->abladestelleStrasse    = $this->edAbladestelle->strasse;
            $this->abladestellePlz        = $this->edAbladestelle->plz;
            $this->abladestelleOrt        = $this->edAbladestelle->ort;
            $this->debitorNr              = $this->edAbladestelle->debitor_nr;
            $this->debitorName            = Debitor::where('nr', $this->debitorNr)->first()->name;
            $this->abladestelleKostenstelle = $this->edAbladestelle->kostenstelle;
            $this->abladestelleBestellrhythmus = $this->edAbladestelle->bestellrhythmus;

        }
        $this->showAbladestelle = true ;
    }

    public function editLagerort($doCreate, $id)
    {
        $this->isEditLagerort = !$doCreate;

        if ($doCreate) {
            $this->lagerortId = null;
            $this->lagerortBezeichnung = '';
            $this->lagerortAbladestelleId = $id;

            $this->lagerortAbladestelleName = Abladestelle::findOrFail($id)->name;
        } else {
            $lagerort = Lagerort::with('abladestelle')->findOrFail($id);

            $this->lagerortId = $lagerort->id;
            $this->lagerortBezeichnung = $lagerort->bezeichnung;
            $this->lagerortAbladestelleId = $lagerort->abladestelle_id;
            $this->lagerortAbladestelleName = $lagerort->abladestelle?->name;
        }

        $this->showLagerort = true;
    }



    public function saveDebitor(){
        $this->showDebitor = false ;
        if ($this->isEditDebitor){
            $this->edDebitor = Debitor::where('nr', $this->debitorNr)->first();

        }
        else
            {
            $this->edDebitor = new Debitor();
            $this->edDebitor->Nr = $this->debitorNr;
        }
        $this->edDebitor->name = $this->debitorName;
        $this->edDebitor->netzregion = $this->debitorNetzregion;
        $this->edDebitor->save();

        $this->loadDebitoren();
    }

    public function saveAbladestelle(){
        $this->showAbladestelle = false ;

        if ($this->abladestelleId){
            $this->edAbladestelle = Abladestelle::where('id', $this->abladestelleId)->first();

        }
        else {
            $this->edAbladestelle = new Abladestelle();
        }
        $this->edAbladestelle->name = $this->abladestelleName;
        $this->edAbladestelle->name2 = $this->abladestelleName2;
        $this->edAbladestelle->strasse = $this->abladestelleStrasse;
        $this->edAbladestelle->plz = $this->abladestellePlz;
        $this->edAbladestelle->ort = $this->abladestelleOrt;
        $this->edAbladestelle->debitor_nr = $this->debitorNr;
        $this->edAbladestelle->kostenstelle = $this->abladestelleKostenstelle;
        $this->edAbladestelle->bestellrhythmus = $this->abladestelleBestellrhythmus;

        $this->edAbladestelle->save();

    }

    public function saveLagerort()
    {
        $this->validate([
            'lagerortBezeichnung' => 'required|string|max:255',
            'lagerortAbladestelleId' => 'required|exists:abladestellen,id',
        ]);

        if ($this->isEditLagerort && $this->lagerortId) {
            $lagerort = Lagerort::findOrFail($this->lagerortId);
        } else {
            $lagerort = new Lagerort();
            $lagerort->abladestelle_id = $this->lagerortAbladestelleId;
        }

        $lagerort->bezeichnung = $this->lagerortBezeichnung;
        $lagerort->save();

        $this->showLagerort = false;
    }

    private function clearAbladestelle(){
        $this->abladestelleId         = null;
        $this->abladestelleName       = null;
        $this->abladestelleName2      = null;
        $this->abladestelleStrasse    = null;
        $this->abladestellePlz        = null;
        $this->abladestelleOrt        = null;
        $this->abladestelleKostenstelle = null;
        $this->abladestelleBestellrhythmus = null;
    }

    public function updatedStrasse($value){
     \Log::info(["updatedStrasse", $value]);
    }
    public function updated($propertyName, $value)
{
    \Log::info('Property updated: ' . $propertyName . ' = ' . $value);

}

}
