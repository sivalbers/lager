<?php

namespace App\Livewire;

use App\Models\Abladestelle;
use App\Models\Lagerort;
use App\Models\Artikel;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;
use App\Repositories\BestandsbuchungRepository;

class ScanArtikel extends Component
{

    public $inputData = [];
    public int $abladestelle = -1;
    public int $debitornr = -1;
    public $abladestellen = [];
    public $lagerorte = [];


    public $mArtikel = '';
    public $mBezeichnung = '';
    public $mAbladestelle = null;
    public $mLagerort = '';
    public $mLagerplatz = '';
    public $mMenge = 0;


    public function mount(){

        /*
        $this->inputData[] =  [
                'Artikel'  => '200333',
                'Bezeichnung'  => 'Testartikel 200333',
                'Abladestelle'  => 'BM Wesermarsch',
                'Lagerort' => '0098',
                'Lagerplatz' => 'A01-01-01',
                'Menge'    => -1,
            ];
*/

        $this->inputData = null;

        $this->debitornr = auth()->user()->debitor_nr;
        $this->abladestelle = auth()->user()->abladestelle_id;
        $this->loadAbladestellen();

    }


#[On('qrcode-scanned')]
public function handleScan(string $code = null): void {
    \Log::info('Anfang handleScan', ['code' => $code]);

    if (!$code) {
        return;
    }

    $decoded = json_decode($code, true);

    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
        $this->inputData[] = [
            'Artikel'  => $decoded['artikel'] ?? '',
            'Lagerort' => $decoded['lagerort'] ?? '',
            'Menge'    => $decoded['menge'] ?? -1,
        ];
    }

    \Log::info('Scan verarbeitet', $this->inputData);
    $this->dispatch('scan-processed');
    \Log::info('Ende handleScan');
}


public function addRow($index = null)
    {
        if ($index === null) {
            $this->inputData[] = [
                'Artikel'  => '',
                'Bezeichnung'  => '',
                'Abladestelle'  => '',
                'Lagerort' => '',
                'Lagerplatz' => '',
                'Menge'    => 0,
            ];
            return;
        }
        else {
            $newRow = [
                'Artikel'  => '',
                'Bezeichnung'  => '',
                'Abladestelle'  => '',
                'Abladestelle_id' => 0,
                'Lagerort' => '',
                'Lagerort_id' => 0,
                'Lagerplatz' => '',
                'Menge'    => 0,
            ];
            array_splice($this->inputData, $index + 1, 0, [$newRow]);
        }

    }

    public function addManuelleEingabe()
    {
        $ab = Abladestelle::where ('id', (int)$this->mAbladestelle)->first();
        $la = Lagerort::where ('id', (int)$this->mLagerort)->first();
        Log::info(['Abladestelle' => $this->mAbladestelle]);
            $this->inputData[] = [
                'Artikel'  => $this->mArtikel,
                'Bezeichnung'  => $this->mBezeichnung,
                'Abladestelle'  => $ab->name,
                'Abladestelle_id'  => (int)$this->mAbladestelle,
                'Lagerort' => $la->bezeichnung,
                'Lagerort_id' => (int)$this->mLagerort,
                'Lagerplatz' => $this->mLagerplatz,
                'Menge'    => $this->mMenge,
            ];
            Log::info($this->inputData[count($this->inputData)-1]);
            return;
    }


    public function render()
    {
        Log:info($this->inputData);
        return view('livewire.scan-artikel')->layout('layouts.app');
    }


    public function buchen()
    {
        $bestandsRepository = new BestandsbuchungRepository();
        foreach ($this->inputData as $row) {
            $bestandsRepository->BucheBestand(
                $row['Artikel'],
                $row['Abladestelle_id'],
                $row['Lagerort_id'],
                $row['Lagerplatz'],
                $row['Menge']);
        }

        $this->inputData = [];

        session()->flash('message', 'Buchung erfolgreich gespeichert!');
     }

    public function updatedMartikel(){
        $this->mBezeichnung = Artikel::where('artikelnr', $this->mArtikel)->value('bezeichnung');
        Log::info($this->mBezeichnung);
        session()->flash('message', 'Artikel aktualisiert');
    }

    public function updatedMabladestelle(){

        $this->loadLagerorte();
        session()->flash('message', 'Abladestelle aktualisiert - Lagerorte geladen');
    }

    public function updatedMlagerort(){


        session()->flash('message', 'Lagerort geändert');
    }

    public function manuelleErfassung(){
        $this->addManuelleEingabe();
    }

    public function loadAbladestellen(){
        $this->abladestellen = Abladestelle::select('name', 'id')->where( 'debitor_nr', $this->debitornr)->orderBy('name')->get()->toArray();
        Log::info(['loadAbladestellen' => $this->abladestellen]);
    }

    public function loadLagerorte(){
        $this->lagerorte = Lagerort::select( 'id', 'bezeichnung' )->where( 'abladestelle_id', $this->mAbladestelle)->orderBy('bezeichnung')->get()->toArray();
        Log::info(['loadLagerorte' => $this->lagerorte]);
    }

}
