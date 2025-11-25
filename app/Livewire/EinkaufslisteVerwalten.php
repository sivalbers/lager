<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Einkaufsliste;
use App\Models\Debitor;
use App\Models\Abladestelle;
use App\Models\Lagerort;
use Illuminate\Support\Facades\Auth;

class EinkaufslisteVerwalten extends Component
{
    public $einkaufslisten;
    public $showModal = false;

    public $einkaufId, $debitorNr, $abladestelleId, $lagerortId, $artikelnr, $menge, $kommentar;

    protected $rules = [
        'debitorNr' => 'required|integer',
        'artikelnr' => 'required|string|max:255',
        'menge' => 'required|numeric|min:0.01',
        'kommentar' => 'nullable|string',
    ];

    public function mount()
    {
        $this->loadEinkaufslisten();
    }

    public function loadEinkaufslisten()
    {
        $this->einkaufslisten = Einkaufsliste::with(['abladestelle', 'lagerort'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function edit($id = null)
    {
        $this->resetErrorBag();
        $this->reset(['einkaufId', 'debitorNr', 'abladestelleId', 'lagerortId', 'artikelnr', 'menge', 'kommentar']);

        if ($id) {
            $e = Einkaufsliste::findOrFail($id);
            $this->einkaufId = $e->id;
            $this->debitorNr = $e->debitor_nr;
            $this->abladestelleId = $e->abladestelle_id;
            $this->lagerortId = $e->lagerort_id;
            $this->artikelnr = $e->artikelnr;
            $this->menge = $e->menge;
            $this->kommentar = $e->kommentar;
        }

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        Einkaufsliste::updateOrCreate(
            ['id' => $this->einkaufId],
            [
                'user_id' => Auth::id(),
                'debitor_nr' => $this->debitorNr,
                'abladestelle_id' => $this->abladestelleId,
                'lagerort_id' => $this->lagerortId,
                'artikelnr' => $this->artikelnr,
                'menge' => $this->menge,
                'kommentar' => $this->kommentar,
            ]
        );

        $this->showModal = false;
        $this->loadEinkaufslisten();
    }

    public function delete($id)
    {
        Einkaufsliste::destroy($id);
        $this->loadEinkaufslisten();
    }

    public function render()
    {
        return view('livewire.einkaufsliste-verwalten');
    }
}
