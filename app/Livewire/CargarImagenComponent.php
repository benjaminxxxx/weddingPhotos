<?php

namespace App\Livewire;

use App\Models\Archivo;
use App\Models\Boda;
use App\Models\Galeria;
use Livewire\Component;


use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use Session;

class CargarImagenComponent extends Component
{
    public $mostrarFormularioCargarImagen = false;
    use WithFileUploads;
    public $fotos = [];
    protected $listeners = ['abrirModuloCargarImagenes'];
    public $cargaCompleta = false;
    public $mensaje_opcional;
    public function abrirModuloCargarImagenes()
    {
        $this->cargaCompleta = false;
        $this->mostrarFormularioCargarImagen = true;
    }
    public function subirImagenes()
    {
        $this->validate([
            'fotos.*' => 'image', // 8MB por imagen
        ]);

        try {
            // Validar que exista la sesión
            $uuid = Session::get('boda_uuid');
            $mesa = Session::get('mesa');
            $uploadToken = Session::get('upload_token');

            if (!$uuid || !$mesa || !$uploadToken) {
                throw new \Exception('Sesión expirada o incompleta. Por favor, escanea el código QR nuevamente.');
            }

            // Buscar boda
            $boda = Boda::where('uuid', $uuid)->first();

            if (!$boda) {
                throw new \Exception('La boda ya no está disponible.');
            }

            if($boda->subida_activa==false){
                throw new \Exception('La subida de imágenes está desactivada para esta boda.');
            }

            // Crear una nueva galería (una sola vez por subida)
            $galeria = Galeria::create([
                'boda_id' => $boda->id,
                'mesa' => $mesa,
                'upload_token' => $uploadToken,
                'mensaje' => $this->mensaje_opcional,
                'likes' => 0,
                'unlikes' => 0,
                'hearts' => 0,
            ]);

            foreach ($this->fotos as $foto) {
                $path = 'bodas/' . date('Y') . '/' . date('m');
                $filename = 'boda_' . Str::random(20) . '.' . $foto->getClientOriginalExtension();

                if (!Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->makeDirectory($path);
                }

                $image = Image::read($foto)->scaleDown(1000, 1000);
                $publicPath = Storage::disk('public')->path("$path/$filename");
                $image->save($publicPath);

                $nombreGenerado = now()->format('Y_m_d_H_i_') . Str::random(6);

                Archivo::create([
                    'boda_id' => $boda->id,
                    'galeria_id' => $galeria->id,
                    'numero' => $this->numero ?? 1,
                    'tipo' => 'foto',
                    'archivo' => "$path/$filename",
                    'aprobado' => false,
                    'upload_token' => $uploadToken,
                    'nombre_opcional' => $nombreGenerado,
                    'mensaje_opcional' => null, // Ya está en galería
                    'oficial' => false,
                    'galeria' => false,
                ]);
            }

            $this->cargaCompleta = true;
            $this->reset('fotos', 'mensaje_opcional');
            session()->flash('success', 'Imágenes subidas correctamente.');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
        }
    }
    public function eliminarFoto($key)
    {
        if (isset($this->fotos[$key])) {
            unset($this->fotos[$key]);
            $this->fotos = array_values($this->fotos); // Reindexar para evitar errores de Livewire
        }
    }

    public function render()
    {
        return view('livewire.cargar-imagen-component');
    }
}
