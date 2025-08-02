<div class="max-w-xl mx-auto p-6 bg-white rounded-lg shadow dark:bg-stone-800">

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
            <strong class="font-bold">Éxito!</strong>
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <form wire:submit.prevent="guardarConfiguracion" class="space-y-6">

        <!-- Campo Nombre -->
        <div>
            <label for="nombre" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">
                Nombres de los esposos
            </label>
            <input type="text" wire:model.defer="nombre" id="nombre"
                class="w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" />
            @error('nombre') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Toggle subir imágenes -->
        <div>
            <label class="inline-flex items-center cursor-pointer">
                <input type="checkbox" wire:model="subida_activa" class="sr-only peer">
                <div
                    class="peer h-6 w-11 rounded-full bg-gray-300 after:absolute after:top-[2px] after:start-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all peer-checked:bg-blue-600 peer-checked:after:translate-x-full peer-checked:after:border-white dark:bg-gray-600 dark:peer-checked:bg-blue-500 relative after:content-['']">
                </div>
                <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">Permitir subir imágenes</span>
            </label>
        </div>

        <!-- Toggle ver portada -->
        <div>
            <label class="inline-flex items-center cursor-pointer">
                <input type="checkbox" wire:model="ver_principal" class="sr-only peer">
                <div
                    class="peer h-6 w-11 rounded-full bg-gray-300 after:absolute after:top-[2px] after:start-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all peer-checked:bg-blue-600 peer-checked:after:translate-x-full peer-checked:after:border-white dark:bg-gray-600 dark:peer-checked:bg-blue-500 relative after:content-['']">
                </div>
                <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">Mostrar portada</span>
            </label>
        </div>

        <!-- Toggle ver planning -->
        <div>
            <label class="inline-flex items-center cursor-pointer">
                <input type="checkbox" wire:model="ver_planning" class="sr-only peer">
                <div
                    class="peer h-6 w-11 rounded-full bg-gray-300 after:absolute after:top-[2px] after:start-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all peer-checked:bg-blue-600 peer-checked:after:translate-x-full peer-checked:after:border-white dark:bg-gray-600 dark:peer-checked:bg-blue-500 relative after:content-['']">
                </div>
                <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">Mostrar planning</span>
            </label>
        </div>

        <!-- Toggle ver galería -->
        <div>
            <label class="inline-flex items-center cursor-pointer">
                <input type="checkbox" wire:model="ver_galeria" class="sr-only peer">
                <div
                    class="peer h-6 w-11 rounded-full bg-gray-300 after:absolute after:top-[2px] after:start-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all peer-checked:bg-blue-600 peer-checked:after:translate-x-full peer-checked:after:border-white dark:bg-gray-600 dark:peer-checked:bg-blue-500 relative after:content-['']">
                </div>
                <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">Mostrar galería</span>
            </label>
        </div>

        <!-- Botón -->
        <div>
            <button type="submit"
                class="w-full flex justify-center items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white font-medium py-2.5 px-5 rounded-lg text-sm focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                <i class="fa fa-save"></i> Guardar cambios
            </button>
        </div>
    </form>
</div>
