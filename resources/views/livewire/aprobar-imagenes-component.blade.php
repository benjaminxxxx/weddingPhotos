<div>
    <h3 class="font-bold text-lg md:text-2xl">
        Imagenes por aprobar
    </h3>
    @if (session()->has('message'))
        <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-4">
            {{ session('message') }}
        </div>
    @elseif (session()->has('error'))
        <div class="bg-red-100 text-red-800 p-4 rounded-lg mb-4">
            {{ session('error') }}
        </div>
    @endif
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4 mt-4">
        @foreach ($imagenesPorAprobar as $imagen)
            <div>
                <div class="border border-gray-100 rounded-lg shadow mb-3 flex items-center justify-center overflow-hidden">
                    <img src="{{ Storage::disk('public')->url($imagen->archivo) }}" alt="Imagen por aprobar"
                        class="object-cover">
                </div>

                <div class="flex items-center justify-center gap-4 mt-3">

                    <button type="button" wire:click="aprobar({{ $imagen->id }})"
                        class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                        <i class="fa fa-check"></i>
                    </button>

                    <button type="button" wire:confirm="La imagen se va a eliminar"
                        wire:click="noaprobar({{ $imagen->id }})"
                        class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"><i
                            class="fa fa-trash"></i></button>

                </div>
            </div>
        @endforeach
    </div>
    <x-loading wire:loading />
</div>