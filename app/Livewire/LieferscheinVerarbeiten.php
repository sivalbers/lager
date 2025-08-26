<?php

namespace App\Livewire;

use Livewire\Component;

class LieferscheinVerarbeiten extends Component
{

    public $jsonResult = "";

    protected $listeners = ['setJsonResult' => 'updateJsonResult'];

    public function updateJsonResult($data)
    {
        $this->jsonResult = $data['value'];
    }

    public function render()
    {
        return view('livewire.lieferschein-verarbeiten')
            ->layout('layouts.app');
    }


}
