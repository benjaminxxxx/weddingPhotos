<?php

namespace App\Livewire;

use App\Models\Archivo;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class AprobarImagenesComponent extends Component
{
    public $imagenesPorAprobar = [];
    public function mount(){
        $this->cargarImagenesPorAprobar();
    }
    public function cargarImagenesPorAprobar()
    {
        // Cargar imágenes que aún no han sido aprobadas
        $this->imagenesPorAprobar = Archivo::where('aprobado', false)->orderBy('created_at','desc')->get();
        
    }
    public function aprobar($id){
        $archivo = Archivo::find($id);
        if ($archivo) {
            $archivo->aprobado = true;
            $archivo->save();
            session()->flash('message', 'Imagen aprobada correctamente.');
            $this->cargarImagenesPorAprobar();
        } else {
            session()->flash('error', 'Imagen no encontrada.');
        }
    }
    public function noaprobar($id){
        $archivo = Archivo::find($id);
        if ($archivo) {
            //eliminar el archivo de la capreta Storage::disk('public') utilizando Storage::delete
            Storage::disk('public')->delete($archivo->archivo);

            $archivo->delete();
            $this->cargarImagenesPorAprobar();
            session()->flash('message', 'Imagen eliminada correctamente.');
        } else {
            session()->flash('error', 'Imagen no encontrada.');
        }
        $this->imagenesPorAprobar = Archivo::where('aprobado', false)->get();
    }

    public function render()
    {
        return view('livewire.aprobar-imagenes-component');
    }
}
