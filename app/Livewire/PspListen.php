<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Psp;
use App\Repositories\PspRepository;

class PspListen extends Component
{
    public $psps;
    public $showPsp = false;
    public $isEditPsp = false;

    public $pspId;
    public $pspNetzregion;
    public $pspKostenstelle;
    public $pspArtikel;
    public $pspMaterialgruppe;
    public $pspFormat;
    public $pspBeschreibung;

    public $edPsp;

    public $pspTestNetzregion;
    public $pspTestKostenstelle;
    public $pspTestArtikel;
    public $pspTestMaterialgruppe;
    public $pspTestGefunden;


    public function mount()
    {
        $this->loadPsps();
    }

    public function loadPsps()
    {
        $this->psps = Psp::orderBy('id')->get();
    }

    public function render()
    {
        return view('livewire.psp-listen')->layout('layouts.app');
    }

    public function editPsp($doCreate, $id = null)
    {
        $this->isEditPsp = !$doCreate;

        if ($doCreate) {
            $this->edPsp = new Psp();
            $this->resetInputFields();
        } else {
            $this->edPsp = Psp::find($id);
            $this->pspId = $this->edPsp->id;
            $this->pspNetzregion = $this->edPsp->netzregion;
            $this->pspKostenstelle = $this->edPsp->kostenstelle;
            $this->pspArtikel = $this->edPsp->artikel;
            $this->pspMaterialgruppe = $this->edPsp->materialgruppe;
            $this->pspFormat = $this->edPsp->format;
            $this->pspBeschreibung = $this->edPsp->beschreibung;
        }

        $this->showPsp = true;
    }

    public function savePsp()
    {
        $this->showPsp = false;

        if ($this->isEditPsp && $this->pspId) {
            $this->edPsp = Psp::find($this->pspId);
        } else {
            $this->edPsp = new Psp();
        }

        $this->edPsp->netzregion = $this->pspNetzregion;
        $this->edPsp->kostenstelle = $this->pspKostenstelle;
        $this->edPsp->artikel = $this->pspArtikel;
        $this->edPsp->materialgruppe = $this->pspMaterialgruppe;
        $this->edPsp->format = $this->pspFormat;
        $this->edPsp->beschreibung = $this->pspBeschreibung;

        $this->edPsp->save();

        $this->loadPsps();
    }

    private function resetInputFields()
    {
        $this->pspId = null;
        $this->pspNetzregion = '';
        $this->pspKostenstelle = '';
        $this->pspArtikel = '';
        $this->pspMaterialgruppe = '';
        $this->pspFormat = '';
        $this->pspBeschreibung = '';
    }

    public function testFindPsp()
    {
        $this->pspTestGefunden = PspRepository::findePspDatensatz( $this->pspTestNetzregion,
                    $this->pspTestKostenstelle,
                    $this->pspTestArtikel,
                    $this->pspTestMaterialgruppe );

    }
}
