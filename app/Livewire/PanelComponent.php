<?php

namespace App\Livewire;

use App\Models\Boda;
use Livewire\Component;

class PanelComponent extends Component
{
    public $seccion = 'galeria';
    public $nombre;
    public $puedeSubirImagenes = false;
    public $puedeVerGaleria = true;
    public $puedeVerPlanning = true;
    public $puedeVerPrincipal = true;
    public function mount(){
        $boda = Boda::first();
        if ($boda) {
            $this->nombre = $boda->nombre;
            $this->puedeSubirImagenes = (bool) $boda->subida_activa; // Verifica si la subida está activa
            $this->puedeVerGaleria = (bool) $boda->ver_galeria;
            $this->puedeVerPlanning = (bool) $boda->ver_planning;
            $this->puedeVerPrincipal = (bool) $boda->ver_principal;
        } else {
            $this->nombre = 'Boda Desconocida'; // Valor por defecto si no hay boda
        }
    }
    public function render()
    {
        return view('livewire.panel-component');
    }
}
