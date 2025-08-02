<?php

namespace App\Livewire;

use App\Models\Archivo;
use App\Models\Boda;
use App\Models\UserReaction;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

class GaleriaComponent extends Component
{
    public $imagenes = [];
    public $offset = 0;
    public $limit = 10; // Para pruebas rápidas, luego puedes cambiar a 10 o más
    public $hasMore = true;
    public $loading = false;
    public $sliderOpen = false;
    public $currentImageIndex = 0;
    public $totalImagenes = 0; // Total de imágenes disponibles en la base de datos

    protected $userToken;
    protected $bodaUuid;
    protected $bodaId;
    protected $mesa;

    public function mount()
    {
        // Obtener datos de la sesión
        $this->userToken = Session::get('upload_token');
        $this->bodaUuid = Session::get('boda_uuid');
        $this->mesa = Session::get('mesa');

        if (!$this->userToken || !$this->bodaUuid) {
            session()->flash('error', 'Sesión expirada. Por favor, escanea el código QR nuevamente.');
            return redirect()->route('invitado.expirado');
        }

        // Obtener el boda_id desde el UUID
        $boda = Boda::where('uuid', $this->bodaUuid)->first();
        if (!$boda) {
            session()->flash('error', 'La boda no existe.');
            return redirect()->route('invitado.expirado');
        }

        $this->bodaId = $boda->id;
        $this->cargarImagenesIniciales();
    }

    // Método para cargar las primeras imágenes y el total
    private function cargarImagenesIniciales()
    {
        // Contar el total de imágenes para la boda actual
        $this->totalImagenes = Archivo::where('aprobado', true)
                                    ->where('oficial', false)
                                    ->count();
        
        // Cargar el primer lote de imágenes
        $this->imagenes = $this->obtenerImagenesConReacciones(0, $this->limit);
        
        // Actualizar el offset al número de imágenes cargadas
        $this->offset = count($this->imagenes);
        // Determinar si hay más imágenes para cargar
        $this->hasMore = $this->offset < $this->totalImagenes;
    }

    public function cargarMasImagenes()
    {
        if (!$this->hasMore || $this->loading) {
            return;
        }

        $this->loading = true;
        
        // Obtener el siguiente lote de imágenes
        $nuevasImagenes = $this->obtenerImagenesConReacciones($this->offset, $this->limit);

        if (count($nuevasImagenes) > 0) {
            // Fusionar las nuevas imágenes con las existentes
            $this->imagenes = array_merge($this->imagenes, $nuevasImagenes);
            // Actualizar el offset
            $this->offset = count($this->imagenes);
        }

        // Recalcular si hay más imágenes
        $this->hasMore = $this->offset < $this->totalImagenes;
        $this->loading = false;

        $data = [
            'imagenes' => $this->imagenes,
            'hasMore' => $this->hasMore,
            'loading' => $this->loading,
            'total' => $this->totalImagenes,
            'cargadas' => count($this->imagenes)
        ];
        //dd($data );
        // Disparar evento para que Alpine.js actualice su estado
        $this->dispatch('imagenesActualizadas',$data  );
    }

    public function abrirSlider($index)
    {
        $this->currentImageIndex = (int) $index;
        $this->sliderOpen = true;
        
        $this->dispatch('sliderAbierto', [
            'index' => $this->currentImageIndex,
            'totalImagenes' => count($this->imagenes)
        ]);
    }

    public function cerrarSlider()
    {
        $this->sliderOpen = false;
        $this->dispatch('sliderCerrado');
    }

    public function navegarSlider($direccion)
    {
        $nuevoIndex = $this->currentImageIndex;
        
        if ($direccion === 'anterior' && $this->currentImageIndex > 0) {
            $nuevoIndex = $this->currentImageIndex - 1;
        } elseif ($direccion === 'siguiente' && $this->currentImageIndex < count($this->imagenes) - 1) {
            $nuevoIndex = $this->currentImageIndex + 1;
        }

        if ($nuevoIndex !== $this->currentImageIndex) {
            $this->currentImageIndex = $nuevoIndex;
            $this->dispatch('sliderNavegado', [
                'index' => $this->currentImageIndex
            ]);
        }
    }

    public function toggleReaction($imageId, $type)
    {
        $this->userToken = Session::get('upload_token'); // Asegurarse de que el token esté disponible

        if (!$this->userToken) {
            $this->dispatch('sesionExpirada');
            return;
        }

        try {
            $archivo = Archivo::find($imageId);
            if (!$archivo) {
                return;
            }

            $reactionKey = $this->userToken . '_' . $imageId;
            $userReaction = UserReaction::where('reaction_key', $reactionKey)->first();

            $reactionAdded = false;
            $previousType = null;
            $boda = Boda::first();

            if ($userReaction && $userReaction->type === $type) {
                $userReaction->delete();
                $archivo->decrement($type);
                $reactionAdded = false;
            } else {
                if ($userReaction) {
                    $previousType = $userReaction->type;
                    $archivo->decrement($previousType);
                    $userReaction->update(['type' => $type]);
                } else {
                    UserReaction::create([
                        'reaction_key' => $reactionKey,
                        'archivo_id' => $imageId,
                        'type' => $type,
                        'user_token' => $this->userToken,
                        'boda_id' => $boda->id, // Usar boda_id
                        'mesa' => $this->mesa
                    ]);
                }
                $archivo->increment($type);
                $reactionAdded = true;
            }

            $this->actualizarImagenLocal($imageId, $type, $reactionAdded, $previousType);

            $archivoFresh = $archivo->fresh();
            $this->dispatch('reaccionActualizada', [
                'imageId' => $imageId,
                'type' => $type,
                'reactionAdded' => $reactionAdded,
                'previousType' => $previousType,
                'newCounts' => [
                    'likes' => $archivoFresh->likes ?? 0,
                    'unlikes' => $archivoFresh->unlikes ?? 0,
                    'hearts' => $archivoFresh->hearts ?? 0,
                ],
                'userReaction' => $reactionAdded ? $type : null // Estado de la reacción del usuario
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en toggleReaction: ' . $e->getMessage());
            $this->dispatch('errorReaccion', $e->getMessage());
        }
    }

    private function obtenerImagenesConReacciones($offset = 0, $limit = null)
    {
        
        $query = Archivo::where('aprobado', true)
                        ->where('oficial', false)
                        ->orderBy('created_at', 'desc')->orderBy('id', 'desc');

        if ($offset > 0) {
            $query->skip($offset);
        }

        if ($limit) {
            $query->take($limit);
        }

        return $query->get()->map(function ($archivo) {
            $reactionKey = $this->userToken . '_' . $archivo->id;
            $userReaction = UserReaction::where('reaction_key', $reactionKey)->first();

            return [
                'id' => $archivo->id,
                'archivo' => $archivo->archivo,
                'likes' => (int) ($archivo->likes ?? 0),
                'unlikes' => (int) ($archivo->unlikes ?? 0),
                'hearts' => (int) ($archivo->hearts ?? 0),
                'user_reaction' => $userReaction ? $userReaction->type : null,
                'created_at' => $archivo->created_at->diffForHumans()
            ];
        })->toArray();
    }

    // Actualiza el array local de imágenes para reflejar los cambios de reacción
    private function actualizarImagenLocal($imageId, $type, $reactionAdded, $previousType = null)
    {
        foreach ($this->imagenes as &$imagen) {
            if ($imagen['id'] == $imageId) {
                if ($reactionAdded) {
                    if ($previousType && $previousType !== $type) {
                        $imagen[$previousType]--;
                    }
                    $imagen[$type]++;
                    $imagen['user_reaction'] = $type;
                } else {
                    $imagen[$type]--;
                    $imagen['user_reaction'] = null;
                }
                break;
            }
        }
    }

    public function render()
    {
        return view('livewire.galeria-component');
    }
}
