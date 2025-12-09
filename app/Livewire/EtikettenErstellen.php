<?php

namespace App\Livewire;

use League\CommonMark\Environment\Environment;
use Livewire\Component;
use App\Repositories\BestandsverwaltungRepository;
use App\Models\Etikett;
use App\Models\Artikel;
use App\Models\Lagerort;

class EtikettenErstellen extends Component
{

    public $mArtikelNr = '';
    public $mBezeichnung = '';
    public $mAbladestelle_id = 0;
    public $mLagerort_id = 0;
    public $mLagerplatz = '';


    public $lagerorteList = [];

    public $abladestellenList ;

    private $bestandRepository;
    public $etiketten;


    public $text;

    public array $data ;

    public function mount(){

        $this->loadAbladestellenList();

        if ($this->abladestellenList->count() === 1){
            $this->mAbladestelle_id = $this->abladestellenList->pluck('id')->first();
        }
        $this->loadEtikettFromTable();
    }

    private function loadAbladestellenList(){
        $this->bestandRepository = new BestandsverwaltungRepository();
        $this->abladestellenList  = $this->bestandRepository->abladestellenVonUser();
    }

    public function createDataFromText()
    {
        $bestandRepository = new BestandsverwaltungRepository();
        $this->data = []; // reset

        // Text in Zeilen aufteilen
        $zeilen = preg_split('/\r\n|\r|\n/', trim($this->text));

        foreach ($zeilen as $zeile) {
            if (trim($zeile) === '') {
                continue; // Leere Zeilen überspringen
            }

            // Werte durch Komma trennen
            $werte = array_map('trim', explode(',', $zeile));

            if (count($werte) >= 2) {
                $this->data[] = [
                    'artikelnr' => $werte[0],
                    'bezeichnung' => Artikel::where('artikelnr', $werte[0])->pluck('bezeichnung')->first(),
                    'abladestelle_id' => (int)$werte[1],
                    'lagerort_id' => (int)$werte[2],
                    'lagerorte' =>  $bestandRepository->lagerorteArrayFromAbladestelle_id($werte[1]),
                    'lagerplatz' => $werte[3]
                ];
            }

        }
       // dd($this->data);

       $this->loadAbladestellenList();


    }

    public function loadEtikettFromTable(){
        $eti = Etikett::whereIn('abladestelle_id', $this->bestandRepository->abladestellenArray())->get();

        $this->text = '';
        foreach($eti as $etikett){

            $this->data[] = [
                'artikelnr' => $etikett->artikelnr,
                'bezeichnung' => $etikett->artikel->bezeichnung,
                'abladestelle_id' => $etikett->abladestelle_id,
                'lagerort_id' => $etikett->lagerort_id,
                'lagerorte' =>  $this->bestandRepository->lagerorteArrayFromAbladestelle_id($etikett->abladestelle_id),
                'lagerplatz' => $etikett->lagerplatz,
                'we' => $etikett->id,

            ];


            $this->text .= sprintf(
                "%s,%d,%d,%s%s",
                $etikett->artikelnr,
                $etikett->abladestelle_id,
                $etikett->lagerort_id,
                $etikett->lagerplatz,
                PHP_EOL // <-- echter Zeilenumbruch
            );
        }
    }


    public function render()
    {
        return view('livewire.etiketten-erstellen')->layout('layouts.app');
    }


    public function updatedMartikelNr(){
        $this->mBezeichnung = Artikel::where('artikelnr', $this->mArtikelNr)->value('bezeichnung');
    }

    public function updatedMAbladestelleId()
    {
        \Log::info([ 'updatedMAbladestelleId' => $this->mBezeichnung ] );
        $bestandRepository = new BestandsverwaltungRepository();

        $this->lagerorteList = $bestandRepository->lagerorteArrayFromAbladestelle_id($this->mAbladestelle_id);

        if (!$this->lagerorteList){
            $this->lagerorteList = [];
        }
    }

    public function manuelleErfassung(){
        $bestandRepository = new BestandsverwaltungRepository();
        $this->data[] = [
                'artikelnr' => $this->mArtikelNr,
                'bezeichnung' => $this->mBezeichnung,
                'abladestelle_id' => $this->mAbladestelle_id,
                'lagerort_id' => $this->mLagerort_id,
                'lagerorte' =>  $bestandRepository->lagerorteArrayFromAbladestelle_id($this->mAbladestelle_id),
                'lagerplatz' => $this->mLagerplatz
            ];

        $this->text .= sprintf(
            "%s,%d,%d,%s%s",
            $this->mArtikelNr,
            $this->mAbladestelle_id,
            $this->mLagerort_id,
            $this->mLagerplatz,
            PHP_EOL // <-- echter Zeilenumbruch
        );
        $this->loadAbladestellenList();
    }


    public function abladestelleGeaendert($index)
    {
        $eintrag = $this->data[$index] ?? null;

        if ($eintrag && !empty($eintrag['abladestelle_id'])) {
            // Beispiel: Lagerorte für diese Abladestelle laden

            $bestandRepository = new BestandsverwaltungRepository();

            $lagerorte = $bestandRepository->lagerorteArrayFromAbladestelle_id($eintrag['abladestelle_id']);
            // $lagerorte = Lagerort::where('abladestelle_id', $eintrag['abladestelle_id'])->pluck('id', 'bezeichnung')->toArray();

            // Ins data-Array zurückschreiben
            $this->data[$index]['lagerorte'] = $lagerorte;

            // Optional: Vorbelegung setzen
            $this->data[$index]['lagerort_id'] = null;
        }
    }


public function aktualisiereQRCodes()
{
    // Trigger für das Re-Rendern – optional: neue Daten erzeugen
    $this->data = collect($this->data)->map(function ($item) {
        // Optional kannst du hier die Daten verfeinern
        return $item;
    })->toArray();
}

}
