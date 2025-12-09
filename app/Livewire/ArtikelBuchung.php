<?php

namespace App\Livewire;

use App\Models\Abladestelle;
use App\Models\Lagerort;
use App\Models\Artikel;
use App\Models\Etikett;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Repositories\BestandsbuchungRepository;
use App\Repositories\BestandsverwaltungRepository;

class ArtikelBuchung extends Component
{

    public BestandsbuchungRepository $artikelRepository;
    protected ?BestandsverwaltungRepository $bestandsverwaltungRepository = null;

    public $inputData = [];
    public $abladestellen_ids = [];

    public int $debitornr = -1;
    public $abladestellen = [];
    public $lagerorte = [];

    public $artikelliste = [];


    public $mArtikel = '';
    public $mBezeichnung = '';
    public $mAbladestelle = null;
    public $mLagerort = '';
    public $mLagerplatz = '';
    public $mMenge = 0;
    public $modus = '';
    public $ueberschrift = '';
    public $cameraId = null;


    public function mount($modus){
        $this->modus = $modus;
        if ($modus === 'rueckgabe'){
            $this->ueberschrift = 'Artikelzugang buchen';
        } elseif ($modus === 'entnahme'){
            $this->ueberschrift = 'Artikelabgang buchen';
        } elseif ($modus === 'korrektur'){
            $this->ueberschrift = 'Artikelkorrektur buchen ** Funktion ist noch in Arbeit - bitte nicht verwenden **';
        }
        else {
            $this->ueberschrift = 'Sonstige Buchung';
        }

        $this->inputData = null;

        $this->debitornr = auth()->user()->debitor_nr;
        $this->cameraId = auth()->user()->camera_device_id;


        $user = User::findOrFail(Auth::id());
        $this->abladestellen_ids = $user->abladestellen->pluck('id')->toArray();
        $this->loadAbladestellen();

        $this->artikelliste =  $this->getBestandsverwaltungRepository()->artikelNrBez_ArrayAusBestandInAbladestellenVonUser();


    }

    protected function getBestandsverwaltungRepository(): BestandsverwaltungRepository
    {
        if (!$this->bestandsverwaltungRepository) {
            $this->bestandsverwaltungRepository = new BestandsverwaltungRepository();
        }

        return $this->bestandsverwaltungRepository;
    }


#[On('qrcode-scanned')]
public function handleScan(string $code = null): void {
    \Log::info('Anfang handleScan', ['code' => $code]);

    if (!$code) {
        return;
    }

    $decoded = json_decode($code, true);

    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {

        if (count($this->inputData) === 0 ){
            $this->inputData[] = [
                'artikel' => $decoded['artikel'] ?? '',
                'bezeichnung' => Artikel::where('artikelnr', $decoded['artikel'] ?? '')->value('bezeichnung') ?? '',
                'abladestelle_id' => $decoded['abladestelle'] ?? '',
                'abladestelle' => Abladestelle::where('id', $decoded['abladestelle'] ?? 0)->value('name') ?? '',
                'lagerort' => Lagerort::Where('id', $decoded['lagerort'] ?? 0)->value('bezeichnung') ?? '',
                'lagerort_id' => $decoded['lagerort'] ?? '', 'lagerplatz' => $decoded['lagerplatz'] ?? '',
                'menge' => $this->mMenge ?? -1,
            ];
        }
        else {
        array_unshift($this->inputData, [
            'artikel'         => $decoded['artikel'] ?? '',
            'bezeichnung'     => Artikel::where('artikelnr', $decoded['artikel'] ?? '')->value('bezeichnung') ?? '',
            'abladestelle_id' => $decoded['abladestelle'] ?? '',
            'abladestelle'    => Abladestelle::where('id', $decoded['abladestelle'] ?? 0)->value('name') ?? '',
            'lagerort'        => Lagerort::where('id', $decoded['lagerort'] ?? 0)->value('bezeichnung') ?? '',
            'lagerort_id'     => $decoded['lagerort'] ?? '',
            'lagerplatz'      => $decoded['lagerplatz'] ?? '',
            'menge'           => $this->mMenge ?? -1,
        ]);
    }
    }

    \Log::info('Scan verarbeitet', $this->inputData);
    $this->dispatch('scan-processed');
    \Log::info('Ende handleScan');
}

public function addRow($index = null)
    {
        if ($index === null) {
            $this->inputData[] = [
                'artikel'  => '',
                'bezeichnung'  => '',
                'abladestelle'  => '',
                'lagerort' => '',
                'lagerplatz' => '',
                'menge'    => 0,
            ];
            return;
        }
        else {
            $newRow = [
                'artikel'  => '',
                'bezeichnung'  => '',
                'abladestelle'  => '',
                'abladestelle_id' => 0,
                'lagerort' => '',
                'lagerort_id' => 0,
                'lagerplatz' => '',
                'menge'    => 0,
            ];
            array_splice($this->inputData, $index + 1, 0, [$newRow]);
        }

    }

    public function addManuelleEingabe()
    {
        $this->validate( [
                        'mArtikel' => 'required',
                        'mAbladestelle' => 'required',
                        'mLagerort' => 'required',
                        'mMenge' => 'required|min:1' ] );

        $ab = Abladestelle::where ('id', (int)$this->mAbladestelle)->first();
        $la = Lagerort::where ('id', (int)$this->mLagerort)->first();
        Log::info(['Abladestelle' => $this->mAbladestelle]);
            $this->inputData[] = [
                'artikel'  => $this->mArtikel,
                'bezeichnung'  => $this->mBezeichnung,
                'abladestelle'  => $ab->name,
                'abladestelle_id'  => (int)$this->mAbladestelle,
                'lagerort' => $la->bezeichnung,
                'lagerort_id' => (int)$this->mLagerort,
                'lagerplatz' => $this->mLagerplatz,
                'menge'    => $this->mMenge,
            ];
            Log::info($this->inputData[count($this->inputData)-1]);
            return;
    }


    public function render()
    {
        Log:info($this->inputData);
        return view('livewire.artikel-buchen', ['ueberschrift' => $this->ueberschrift ])->layout('layouts.app');
    }


    public function buchen()
    {
        $bestandsRepository = new BestandsbuchungRepository();
        foreach ($this->inputData as $row) {
            $bestandsRepository->BucheBestand(
                $row['artikel'],
                $row['abladestelle_id'],
                $row['lagerort_id'],
                $row['lagerplatz'],
                $row['menge'],
                $this->modus);
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
        $this->abladestellen = Abladestelle::select('name', 'id')->whereIn( 'id', $this->abladestellen_ids )->orderBy('name')->get()->toArray();
        if (count($this->abladestellen_ids) == 1){
            $this->mAbladestelle = $this->abladestellen_ids[0];
            $this->loadLagerorte();
        }
        Log::info(['loadAbladestellen' => $this->abladestellen]);
    }

    public function loadLagerorte(){
        $this->lagerorte = Lagerort::select( 'id', 'bezeichnung' )->where( 'abladestelle_id', $this->mAbladestelle)->orderBy('bezeichnung')->get()->toArray();
        Log::info(['loadLagerorte' => $this->lagerorte]);
    }



}
