<?php

namespace App\Livewire;

use Livewire\Component;

class ScanArtikel extends Component
{

    protected $listeners = ['qrcode-scanned' => 'handleScan'];
    public $inputData = [];

public function handleScan($data = null)
{
    if (!$data || !isset($data['code'])) {
        return;
    }

    $decoded = json_decode($data['code'], true);

    if (is_array($decoded)) {
        $this->inputData[] = [
            'Artikel'  => $decoded['artikel'] ?? '',
            'Lagerort' => $decoded['lagerort'] ?? '',
            'Menge'    => 1,
        ];
    } else {
        $this->inputData[] = [
            'Artikel'  => $data['code'],
            'Lagerort' => '',
            'Menge'    => 1,
        ];
    }
}


    public function render()
    {
        return view('livewire.scan-artikel')->layout('layouts.app');
    }


    public function buchen()
    {
        // Hier kannst du $this->inputData durchgehen und in DB schreiben
        foreach ($this->inputData as $row) {
            // z. B.:
            // DB::table('buchungen')->insert([
            //     'artikel' => $row['Artikel'],
            //     'lagerort' => $row['Lagerort'],
            //     'menge' => $row['Menge'],
            // ]);
        }

        // Reset nach Buchung (optional)
        $this->inputData = [];

        session()->flash('message', 'Buchung erfolgreich gespeichert!');
    }
}
