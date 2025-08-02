<div x-data="{ abierto: @entangle('mostrarFormularioCargarImagen'), uploading: false, progress: 0 }" x-show="abierto"
    x-on:livewire-upload-start="uploading = true" x-on:livewire-upload-finish="uploading = false"
    x-on:livewire-upload-cancel="uploading = false" x-on:livewire-upload-error="uploading = false"
    x-on:livewire-upload-progress="progress = $event.detail.progress" x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur p-2">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-md mx-auto p-6">
        <h2 class="text-xl font-bold text-center text-gray-800 dark:text-gray-200 mb-6">Subir Fotos y Videos</h2>
        {{-- MENSAJE DE ÉXITO --}}
        @if ($cargaCompleta)
            <div class="space-y-4 text-center">
                <i class="fa fa-check-circle text-green-500 text-5xl mb-2"></i>
                <p class="text-gray-800 dark:text-gray-100 text-lg font-semibold">
                    Fotos subidas correctamente, una vez aprobadas se van a ver en galeria.
                </p>

                <div class="text-center mt-6">

                    <button type="button" @click="abierto = false"
                        class="flex-1 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        Cerrar
                    </button>
                </div>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="bg-red-100 text-red-800 p-4 rounded-lg mb-4">
                <p class="text-sm">{{ session('error') }}</p>
            </div>
            
        @endif
        @if (!$cargaCompleta)
            <form wire:submit.prevent="subirImagenes">
                <!-- Dropzone -->
                @if (!$fotos || count($fotos) === 0)
                    <div class="border-2 border-dashed border-rose-300 rounded-2xl p-8 text-center cursor-pointer bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600"
                        @click="$refs.fileInput.click()">
                        <i class="fa fa-upload text-rose-400 text-4xl mb-4"></i>
                        <p class="text-gray-600 dark:text-gray-300 mb-2">Arrastra archivos aquí o haz clic para seleccionar
                        </p>
                        <input type="file" wire:model="fotos" multiple class="hidden" x-ref="fileInput" />
                        <button type="button" class="bg-rose-500 hover:bg-rose-600 text-white px-4 py-2 rounded-lg mt-2">
                            Seleccionar Archivos
                        </button>
                    </div>

                    <!-- Barra de progreso -->
                    <div x-show="uploading" class="mt-4">
                        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-600">
                            <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-200"
                                :style="'width: ' + progress + '%'">
                            </div>
                        </div>
                        <p class="text-sm text-center mt-1 text-gray-600 dark:text-gray-300"
                            x-text="'Subiendo... ' + progress + '%'"></p>
                    </div>
                @endif

                <!-- Archivos seleccionados -->
                @if ($fotos && count($fotos) > 0)
                    <div class="space-y-2 max-h-[300px] overflow-y-auto mt-4">
                        @foreach ($fotos as $key => $foto)
                            <div class="flex items-center justify-between bg-gray-100 dark:bg-gray-700 p-2 rounded-lg">
                                <div class="flex items-center gap-2 overflow-hidden">
                                    @php
                                        $type = $foto->getMimeType();
                                    @endphp
                                    @if (Str::startsWith($type, 'image/'))
                                        <i class="fa fa-image text-blue-500"></i>
                                    @elseif (Str::startsWith($type, 'video/'))
                                        <i class="fa fa-video text-green-500"></i>
                                    @else
                                        <i class="fa fa-file text-gray-500"></i>
                                    @endif
                                    <span
                                        class="text-sm truncate max-w-[150px] text-gray-800 dark:text-gray-200">{{ $foto->getClientOriginalName() }}</span>
                                </div>
                                <button wire:click.prevent="eliminarFoto({{ $key }})" class="text-red-500 hover:text-red-700">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Descripción -->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Descripción
                        (opcional)</label>
                    <textarea wire:model.defer="mensaje_opcional"
                        class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white resize-none"
                        rows="3" placeholder="Añade una descripción a tus fotos..."></textarea>
                </div>

                <!-- Botones -->
                <div class="flex gap-3 mt-6">
                    <button type="button" @click="abierto = false"
                        class="flex-1 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        Cancelar
                    </button>
                    <button type="submit" class="flex-1 bg-rose-500 hover:bg-rose-600 text-white px-4 py-2 rounded-lg"
                        x-bind:disabled="uploading || {{ count($fotos ?? []) }} === 0">
                        <span x-show="!uploading">Subir</span>
                        <span x-show="uploading">Subiendo...</span>
                    </button>
                </div>
            </form>
        @endif
    </div>

    <!-- Loading overlay -->
    <x-loading wire:loading wire:target="subirImagenes" />
</div>