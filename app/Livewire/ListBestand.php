<?php

namespace App\Livewire;

use App\Models\Abladestelle;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Livewire\WithPagination;
use App\Models\Artikel;
use App\Models\Lagerort;
use App\Models\Artikelbestand;
use App\Services\ArtikelImportService;


use App\Repositories\BestandsverwaltungRepository;

class ListBestand extends Component
{
    use WithPagination;
    public string $search = '';


    public $screenWidth = "";


    public ?int $abladestelle = null;


    public $abladestellenAuswahl;
    public $artikelSummen = [];
    public $abladestellen = [];
    public $lagerorte = [];
    public $lagerplaetze = [];

    public $offeneArtikel = [];
    public $offeneAbladestellen = [];
    public $offeneLagerorte = [];

    public $abladestellenIds;
    public $items = null;


    public int $perPage = 10;
    public int $currentPage = 1;

    protected ?BestandsverwaltungRepository $bestandsverwaltungRepository = null;


//    public $artikelGruppiert;


    public function mount()
    {
        $this->load();
    }


    protected function getBestandsverwaltungRepository(): BestandsverwaltungRepository
    {
        if (!$this->bestandsverwaltungRepository) {
            $this->bestandsverwaltungRepository = new BestandsverwaltungRepository();
        }

        return $this->bestandsverwaltungRepository;
    }


    private function load(){
        Log::info('in load()');


        // Es werden nur die Abladestellen des angemeldeten Users angezeigt
        $this->abladestellenAuswahl = $this->getBestandsverwaltungRepository()->abladestellenVonUser();
        Log::info('Anzahl Abladestellen des Users: ' . $this->abladestellenAuswahl->count() );
        if ($this->abladestellenAuswahl->count() == 1) {
            // hat er nur eine Abladestelle, diese vorwählen
            $this->abladestelle = $this->abladestellenAuswahl->first()->id;
        } else{
            $this->abladestelle = 0 ;
        }



        $this->loadArtikelSummen();
    }

    public function getArtikelSummenPaginiertProperty()
    {
        return array_slice(
            $this->artikelSummen,
            ($this->currentPage - 1) * $this->perPage,
            $this->perPage
        );
    }

    public function nextPage()
    {
        if ($this->currentPage * $this->perPage < count($this->artikelSummen)) {
            $this->currentPage++;
        }
    }

    public function previousPage()
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
        }
    }

    public function loadArtikelSummen()
    {


        // Wenn nach Abladestelle gefiltert wird, nur diese eine Abladestelle berücksichtigen

        // Rohdaten holen und zur Array-Struktur aufbereiten
        if($this->abladestelle === 0){
            // eine einzelne Abladestelle
            $abladestellenIds = $this->getBestandsverwaltungRepository()->abladestellenArray();

        } else {
            // alle Abladestellen des Users
            $abladestellenIds = [ $this->abladestelle ] ;

        }


        $lagerorte_id_array = [] ;

        $artikelnrArray = $this->getBestandsverwaltungRepository()->artikelArrayAusBestand_artikel_abladestellen_lagerorte($this->search, $abladestellenIds, $lagerorte_id_array);

        $query = Artikelbestand::whereIn('artikelnr', $artikelnrArray) ;

        $this->artikelSummen = $query->select('artikelnr')
            ->selectRaw('SUM(bestand) as summe')
            ->with('artikel:artikelnr,bezeichnung,einheit') // Nur notwendige Felder
            ->groupBy('artikelnr')
            ->get()
            ->map(function ($row) {
                return [
                    'artikelnr'   => $row->artikelnr,
                    'bezeichnung' => $row->artikel->bezeichnung ?? 'Unbekannt',
                    'einheit'     => $row->artikel->einheit,
                    'summe'       => $row->summe,
                ];
            })->toArray(); // wichtig: in ein reines Array umwandeln!
    }

    public function toggleArtikel($artikelnr){
        if (in_array($artikelnr, $this->offeneArtikel)) {
            $this->offeneArtikel = array_diff($this->offeneArtikel, [$artikelnr]);
            unset($this->abladestellen[$artikelnr]); // löschen wenn eingeklappt
            return;
        }

        $this->offeneArtikel[] = $artikelnr;

        $this->abladestellen[$artikelnr] = Artikelbestand::where('artikelnr', $artikelnr)
            ->select('abladestelle_id')
            ->selectRaw('SUM(bestand) as summe')
            ->groupBy('abladestelle_id')
            ->with('lagerort.abladestelle')
            ->get()
            ->map(function ($row) {
                $row->id = $row->abladestelle->id ?? 'Unbekannt';
                $row->name = $row->abladestelle->name ?? 'Unbekannt';
                return $row;
            })
            ->toArray(); // <-- WICHTIG!
    }

    public function toggleAbladestelle($artikelnr, $abladestelleId) {
        if (isset($this->lagerorte[$artikelnr][$abladestelleId])) {
            unset($this->lagerorte[$artikelnr][$abladestelleId]);
            return;
        }

        $this->lagerorte[$artikelnr][$abladestelleId] = Artikelbestand::where('artikelnr', $artikelnr)
            ->where('abladestelle_id', $abladestelleId)
            ->select('lagerort_id')
            ->selectRaw('SUM(bestand) as summe')
            ->groupBy('lagerort_id')
            ->with('lagerort')
            ->get()
            ->map(function ($row) {
                $row->id = $row->lagerort->id ?? 'Unbekannt';
                $row->bezeichnung = $row->lagerort->bezeichnung ?? 'Unbekannt';
                return $row;
            })
            ->toArray(); // <-- WICHTIG!
    }

    public function toggleLagerort($artikelnr, $abladestelleId, $lagerortId) {
        if (isset($this->lagerplaetze[$artikelnr][$abladestelleId][$lagerortId])) {
            unset($this->lagerplaetze[$artikelnr][$abladestelleId][$lagerortId]);
            return;
        }

        $this->lagerplaetze[$artikelnr][$abladestelleId][$lagerortId] = Artikelbestand::where([
                'artikelnr' => $artikelnr,
                'abladestelle_id' => $abladestelleId,
                'lagerort_id' => $lagerortId
            ])
            ->select('lagerplatz', 'bestand')
            ->get()
            ->toArray(); // <-- WICHTIG!
    }


    public function updatedSearch()   {

        Log::info( [ 'updatedSearch' => $this->search ] );

        $this->load();
        $this->resetPage();
    }


    public function render()
    {
        Log::info('render()');
        return view('livewire.list-bestand', [
            'items' => $this->items, // falls du das brauchst
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
