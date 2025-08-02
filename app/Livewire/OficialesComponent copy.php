<?php

namespace App\Livewire;

use App\Models\Archivo;
use App\Models\Boda;
use App\Models\UserReaction;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

class OficialesComponent extends Component
{
    public $imagenes = [];
    public $offset = 0;
    public $limit = 10;
    public $hasMore = true;
    public $loading = false;
    public $sliderOpen = false;
    public $currentImageIndex = 0;
    public $totalImagenes = 0;

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

    public function cargarMasImagenes()
    {
        if (!$this->hasMore || $this->loading) {
            return;
        }

        $this->loading = true;
        
        // Incrementar offset para la siguiente página
        $nuevoOffset = $this->offset + $this->limit;
        
        $nuevasImagenes = $this->obtenerImagenesConReacciones($nuevoOffset, $this->limit);

        if (count($nuevasImagenes) > 0) {
            $this->imagenes = array_merge($this->imagenes, $nuevasImagenes);
            $this->offset = $nuevoOffset;
        }

        // Verificar si hay más imágenes
        $this->hasMore = ($this->offset + $this->limit) < $this->totalImagenes;
        $this->loading = false;

        $this->dispatch('imagenesActualizadas', [
            'imagenes' => $this->imagenes,
            'hasMore' => $this->hasMore,
            'loading' => $this->loading,
            'total' => $this->totalImagenes,
            'cargadas' => count($this->imagenes)
        ]);
    }

    public function abrirSlider($index)
    {
        $this->currentImageIndex = (int) $index;
        $this->sliderOpen = true;
        
        $this->dispatch('sliderAbierto', [
            'index' => $this->currentImageIndex,
            'imagen' => $this->imagenes[$this->currentImageIndex] ?? null,
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
                'index' => $this->currentImageIndex,
                'imagen' => $this->imagenes[$this->currentImageIndex] ?? null
            ]);
        }
    }

    public function toggleReaction($imageId, $type)
    {
        $this->userToken = Session::get('upload_token');

        if (!$this->userToken) {
            $this->dispatch('sesionExpirada');
            return;
        }
        $boda = Boda::first();

        try {
            $archivo = Archivo::find($imageId);
            if (!$archivo) {
                return;
            }

            // Crear clave de reacción
            $reactionKey = $this->userToken . '_' . $imageId;

            // Buscar reacción existente
            $userReaction = UserReaction::where('reaction_key', $reactionKey)->first();

            $reactionAdded = false;
            $previousType = null;

            if ($userReaction && $userReaction->type === $type) {
                // Quitar reacción existente del mismo tipo
                $userReaction->delete();
                $archivo->decrement($type);
                $reactionAdded = false;
            } else {
                if ($userReaction) {
                    // Cambiar tipo de reacción
                    $previousType = $userReaction->type;
                    $archivo->decrement($previousType);
                    $userReaction->update(['type' => $type]);
                } else {
                    // Nueva reacción
                    UserReaction::create([
                        'reaction_key' => $reactionKey,
                        'archivo_id' => $imageId,
                        'type' => $type,
                        'user_token' => $this->userToken,
                        'boda_id' => $boda->id,
                        'mesa' => $this->mesa
                    ]);
                }

                $archivo->increment($type);
                $reactionAdded = true;
            }

            // Actualizar imagen en el array local
            $this->actualizarImagenLocal($imageId, $type, $reactionAdded, $previousType);

            // Emitir evento con los datos actualizados
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
                'userReaction' => $reactionAdded ? $type : null
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en toggleReaction: ' . $e->getMessage());
            $this->dispatch('errorReaccion', 'Error al procesar la reacción');
        }
    }

    private function obtenerImagenesConReacciones($offset = 0, $limit = null)
    {
        $query = Archivo::where('oficial', true)
            ->orderBy('created_at', 'desc');

        if ($offset > 0) {
            $query->skip($offset);
        }

        if ($limit) {
            $query->take($limit);
        }

        return $query->get()->map(function ($archivo) {
            // Verificar reacción del usuario actual
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

    private function cargarImagenesIniciales()
    {
        // Obtener total de imágenes para esta boda
        $this->totalImagenes = Archivo::where('oficial', true)->count();
        
        // Cargar primeras imágenes
        $this->imagenes = $this->obtenerImagenesConReacciones(0, $this->limit);
        
        // Verificar si hay más imágenes
        $this->hasMore = $this->totalImagenes > $this->limit;
    }

    private function actualizarImagenLocal($imageId, $type, $reactionAdded, $previousType = null)
    {
        foreach ($this->imagenes as &$imagen) {
            if ($imagen['id'] == $imageId) {
                if ($reactionAdded) {
                    // Si había una reacción anterior diferente, decrementarla
                    if ($previousType && $previousType !== $type) {
                        $imagen[$previousType]--;
                    }
                    $imagen[$type]++;
                    $imagen['user_reaction'] = $type;
                } else {
                    // Quitar reacción
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
