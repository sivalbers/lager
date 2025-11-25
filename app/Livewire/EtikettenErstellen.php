<?php

namespace App\Livewire;

use League\CommonMark\Environment\Environment;
use Livewire\Component;

class EtikettenErstellen extends Component
{

    public $text;

    public array $data ;

    public function mount(){

    }

    public function createDataFromText()
    {
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
                    'artikel' => $werte[0],
                    'abladestelle' => $werte[1],
                    'lagerort' => $werte[2],
                    'lagerplatz' => $werte[3]
                ];
            }
        }
    }


    public function render()
    {
        \Log::info('EtikettenErstellen - render()');
        return view('livewire.etiketten-erstellen')->layout('layouts.app');

    }

}
