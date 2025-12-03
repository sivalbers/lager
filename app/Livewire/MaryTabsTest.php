<?php
namespace App\Livewire;

use Livewire\Component;

class MaryTabsTest extends Component
{
    public string $activeTab = 'a';

    public function render()
    {
        return view('livewire.mary-tabs-test')->layout('layouts.app');
    }
}
