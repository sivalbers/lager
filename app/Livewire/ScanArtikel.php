<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class ScanArtikel extends Component
{

    public $inputData = [];

    public function mount(){

        $this->inputData[] =  [
                'Artikel'  => '200333',
                'Lagerort' => '0098',
                'Menge'    => -1,
            ];
        /*
        $this->inputData[] =  [
                'Artikel'  => '200335',
                'Lagerort' => '0099',
                'Menge'    => 10,
            ];

        $this->inputData[] =  [
                'Artikel'  => '200323',
                'Lagerort' => '0097',
                'Menge'    => 1,
            ];
            */
    }


#[On('qrcode-scanned')]
public function handleScan(string $code = null): void
{
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
