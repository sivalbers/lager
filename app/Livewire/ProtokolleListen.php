<?php


namespace App\Livewire;


use Livewire\Component;
use App\Models\Protokoll;
use App\Models\Abladestelle;
use App\Models\Lagerort;
use App\Models\Buchungsgrund;

use Illuminate\Support\Facades\Log;

class ProtokolleListen extends Component
{

public $search = '';
public $abladestelle = 0;
public $lagerort = 0;
public $buchungsgrund = '';
public $dateFrom;
public $dateTo;

public $sortField = 'datum_zeit';
public $sortDirection = 'asc';

    public function render()
    {
        $query = Protokoll::with(['user', 'abladestelle', 'lagerort', 'buchungsgrund']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('artikelnr', 'like', "%{$this->search}%")
                ->orWhere('lagerplatz', 'like', "%{$this->search}%")
                ->orWhere('bemerkung', 'like', "%{$this->search}%");
            });
        }

        if ($this->abladestelle) {
            $query->where('abladestelle_id', $this->abladestelle);
        }

        if ($this->lagerort) {
            $query->where('lagerort_id', $this->lagerort);
        }

        if ($this->buchungsgrund) {
            $query->where('buchungsgrund_id', $this->buchungsgrund);
        }

        if ($this->dateFrom) {
            $query->whereDate('datum_zeit', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('datum_zeit', '<=', $this->dateTo);
        }




Log::info(['Abladestelle' => $this->abladestelle]);
        return view('livewire.protokolle-listen', [
            'protokolle' =>  $query->orderBy($this->sortField, $this->sortDirection)->paginate(25),
            'abladestellen' => Abladestelle::all(),
            'lagerorte' => Lagerort::all(),
            'buchungsgruende' => Buchungsgrund::all(),
        ])->layout('layouts.app');
    }


    public function sort($sortField){
        if ($this->sortField === $sortField){
            ($this->sortDirection === 'asc') ? $this->sortDirection = 'desc' : $this->sortDirection = 'asc';
        }

        $this->sortField = $sortField ;
        Log::info(['sortField' => $this->sortField, 'sortDirection' => $this->sortDirection ]);

    }
}
