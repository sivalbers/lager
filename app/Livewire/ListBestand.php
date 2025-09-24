<?php

namespace App\Livewire;

use Livewire\Component;

use Livewire\WithPagination;
use App\Models\Lagerort;
use App\Models\Lagerdaten;
use App\Services\ArtikelImportService;

class ListBestand extends Component
{
    use WithPagination;
    public string $search = '';
    public ?int $lagerort = null;


    public $lagerorte;


    public function mount()
    {
        $this->load();
    }

    private function load(){
        \Log::info('in load()');

        $this->lagerorte = Lagerort::orderBy('nr')->get();
        $this->lagerort = 0;

    }

    public function updatingSearch()   { $this->resetPage(); }
    public function updatingLagerort() { $this->resetPage(); }

    public function render()
    {
        $query = \App\Models\Lagerdaten::with(['artikel','lagerort'])
            ->where('bestand', '>', 0);

        if ($this->search !== '') {
            $query->where('artikelnr', 'like', "%{$this->search}%");
        }
        if ($this->lagerort) {
            $query->where('lagernr', $this->lagerort);
        }

        $items = $query->orderBy('artikelnr')->paginate(13);

        return view('livewire.list-bestand', [
            'items'     => $items,
            'lagerorte' => $this->lagerorte,
        ])->layout('layouts.app');
    }

    public function importOData(){
        \Log::info('importOData()');

        $artikelService = new ArtikelImportService();
        $artikelService->importArtikel();
        $this->load();

    }

}
