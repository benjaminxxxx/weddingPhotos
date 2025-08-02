<?php

namespace App\Livewire;

use App\Models\Archivo;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class AprobarImagenesComponent extends Component
{
    public $imagenesPorAprobar = [];
    public $verAprobadas = 'no';
    public function mount(){
        
    }
  
    public function aprobar($id){
        $archivo = Archivo::find($id);
        if ($archivo) {
            $archivo->aprobado = true;
            $archivo->save();
            session()->flash('message', 'Imagen aprobada correctamente.');
          
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
            session()->flash('message', 'Imagen eliminada correctamente.');
        } else {
            session()->flash('error', 'Imagen no encontrada.');
        }
        $this->imagenesPorAprobar = Archivo::where('aprobado', false)->get();
    }

    public function render()
    {
        $query = Archivo::query();
        if ($this->verAprobadas === 'si') {
            $query->where('aprobado', true);
        } else {
            $query->where('aprobado', false);
        }

        $this->imagenesPorAprobar = $query->orderBy('created_at', 'desc')->get();
     
        return view('livewire.aprobar-imagenes-component');
    }
}
