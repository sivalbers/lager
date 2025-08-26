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
    public $abladestelleName1;
    public $abladestelleName2;
    public $abladestelleStrasse;
    public $abladestellePlz;
    public $abladestelleOrt;
    public $abladestelleLagerort;

    public $lagerortNr;
    public $lagerortBezeichnung;

    public $debitoren;


    public $edDebitor;
    public $edAbladestelle;
    public $edLagerort;


    public function mount(){
        $this->loadDebitoren();

    }

    public function loadDebitoren(){
        $this->debitoren = Debitor::with('abladestellen')->orderBy('nr')->get();
    }

    public function render()
    {
        return view('livewire.debitor-listen')->layout('layouts.app');
    }

    public function editDebitor($doCreate){
        $this->isEditDebitor = !$doCreate;
        if ($doCreate){
            $this->edDebitor = new Debitor();
        }
        else {
            $this->edDebitor = Debitor::with('abladestellen')->where('nr', 21001)->get();
        }
        $this->showDebitor = true ;


    }

    public function editAbladestelle($doCreate){
        $this->isEditAbladestelle = !$doCreate;

        if ($doCreate){
            $this->edAbladestelle = new Abladestelle();
        }
        $this->showAbladestelle = true ;
    }

    public function editLagerort($doCreate){
        $this->isEditLagerort = !$doCreate;
        if ($doCreate){
            $this->edLagerort = new Lagerort();
        }
        $this->showLagerort = true ;
    }



    public function saveDebitor(){
        $this->showDebitor = false ;
        if ($this->isEditDebitor){
            $this->edDebitor = Debitor::get('nr', $this->DebitorNr);

        }
        else
            {
            $this->edDebitor->Nr = $this->DebitorNr;
        }
        $this->edDebitor->name = $this->DebitorName;
        $this->edDebitor->netzregion = $this->DebitorNetzregion;
        $this->edDebitor->abladestelle_id = $this->DebitorAbladestelle_id;
        $this->edDebitor->save();
    }

    public function saveAbladestelle(){
        $this->showAbladestelle = false ;

        if ($this->isEditAbladestelle){
            $this->edAbladestelle = Abladestelle::get('id', $this->AbladestelleId);

        }
        else
            {
            $this->edAbladestelle->id = $this->AbladestelleId;
        }
        $this->edAbladestelle->name1 = $this->AbladestelleName1;
        $this->edAbladestelle->name2 = $this->AbladestelleName2;
        $this->edAbladestelle->strasse = $this->AbladestelleStrasse;
        $this->edAbladestelle->plz = $this->AbladestellePlz;
        $this->edAbladestelle->ort = $this->AbladestelleOrt;
        $this->edAbladestelle->lagerort = $this->AbladestelleLagerort;
        $this->edAbladestelle->save();

    }

    public function saveLagerort(){
        $this->showLagerort = false ;

        if ($this->isEditLagerort){
            $this->edLagerort = Lagerort::get('nr', $this->lagerortNr);
        }
        else {
            $this->edLagerort->nr = $this->lagerortNr;
        }

        $this->edLagerort->bezeichnung = $this->lagerortName;

        $this->edLagerort->save();

    }

}
