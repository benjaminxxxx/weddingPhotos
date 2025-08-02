<?php

namespace App\Livewire;

use Livewire\Component;

class PanelComponent extends Component
{
    public $seccion = 'galeria';
    public function render()
    {
        return view('livewire.panel-component');
    }
}
