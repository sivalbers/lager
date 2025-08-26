<?php

namespace App\Livewire;

use Livewire\Component;

class ScanArtikel extends Component
{

    protected $listeners = ['qrcode-scanned' => 'handleScan'];

    public function handleScan($data)
    {
        \Log::info("Scanned QR: " . $data['code']);
        // hier kannst du direkt Artikel suchen, DB-Abfrage etc.
    }

    public function render()
    {
        return view('livewire.scan-artikel')->layout('layouts.app');
    }

}
