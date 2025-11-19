<?php

namespace App\Livewire;

use App\Models\Abladestelle;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Livewire\WithPagination;
use App\Models\Lagerort;
use App\Models\Artikelbestand;
use App\Services\ArtikelImportService;

class ListBestand extends Component
{
    use WithPagination;
    public string $search = '';
    public ?int $lagerort = null;

    public $screenWidth = "";


    public $lagerorte;

    public ?int $abladestelle = null;
    public $abladestellen;


    public function mount()
    {
        $this->load();
    }

    private function load(){
        Log::info('in load()');

        $debitorNr = Auth::user()->debitor_nr;
        $this->abladestellen = Abladestelle::where('debitor_nr', $debitorNr)->get();
        $this->abladestelle = 0 ;

        $this->lagerorte = Lagerort::orderBy('id')->get();
        $this->lagerort = 0;

    }

    public function updatingSearch()   { $this->resetPage(); }
    public function updatingLagerort() { $this->resetPage(); }

    public function render()
    {
        $query = \App\Models\Artikelbestand::with(['artikel','lagerort'])
            ->where('bestand', '>', 0);

        if ($this->search !== '') {
            $query->where('artikelnr', 'like', "%{$this->search}%");
        }

        if ($this->abladestelle) {
            $query->where('abladestelle_id', $this->abladestelle);
        }
        if ($this->lagerort) {
            $query->where('lagerort_id', $this->lagerort);
        }

        $items = $query->orderBy('artikelnr')->paginate(13);

        return view('livewire.list-bestand', [
            'items'     => $items,
            'lagerorte' => $this->lagerorte,
        ])->layout('layouts.app');
    }

    public function importOData(){
        Log::info('importOData()');

        $artikelService = new ArtikelImportService();
        $artikelService->importArtikel();
        $this->load();

    }

}
