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
    <div class="flex items-center justify-between mb-4">

        <form class="max-w-sm my-3">
            <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select an
                option</label>
            <select wire:model.live="verAprobadas"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="no">Imágenes por aprobar</option>
                <option value="si">Imágenes aprobadas</option>
            </select>
        </form>

    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4 mt-4">
        @foreach ($imagenesPorAprobar as $imagen)
            <div>
                <div class="border border-gray-100 rounded-lg shadow mb-3 flex items-center justify-center overflow-hidden">
                    <img src="{{ Storage::disk('public')->url($imagen->archivo) }}" alt="Imagen por aprobar"
                        class="object-cover">
                </div>

                <div class="flex items-center justify-center gap-4 mt-3">
                    @if (!$imagen->aprobado)
                        <button type="button" wire:click="aprobar({{ $imagen->id }})"
                            class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                            <i class="fa fa-check"></i>
                        </button>
                    @endif


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