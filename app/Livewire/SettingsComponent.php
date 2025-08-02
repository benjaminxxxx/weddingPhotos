<?php

namespace App\Livewire;

use App\Models\Boda;
use Livewire\Component;

class SettingsComponent extends Component
{
    public $nombre;
    public $subida_activa = true;
    public $ver_planning = true;
    public $ver_galeria = true;
    public $ver_principal = true;

    public function mount()
    {
        $boda = Boda::first();
        if ($boda) {
            $this->nombre = $boda->nombre;
            $this->subida_activa = (bool) $boda->subida_activa;
            $this->ver_planning = (bool) $boda->ver_planning;
            $this->ver_galeria = (bool) $boda->ver_galeria;
            $this->ver_principal = (bool) $boda->ver_principal;
        }
    }

    public function guardarConfiguracion()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $data = [
            'nombre' => $this->nombre,
            'subida_activa' => $this->subida_activa,
            'ver_planning' => $this->ver_planning,
            'ver_galeria' => $this->ver_galeria,
            'ver_principal' => $this->ver_principal,
        ];

        $boda = Boda::first();
        if ($boda) {
            $boda->update($data);
        } else {
            Boda::create($data);
        }

        session()->flash('message', 'Configuración guardada correctamente.');
    }

    public function render()
    {
        return view('livewire.settings-component');
    }
}
