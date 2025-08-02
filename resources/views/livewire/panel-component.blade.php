<div class="h-screen flex flex-col">
    <!-- Portada -->
    <div class="relative w-full h-[30%] shrink-0">
        <img src="{{ asset('img/portada1.jpg') }}" class="w-full h-full object-cover object-center" alt="">

        <!-- Gradiente -->
        <div class="absolute bottom-0 left-0 w-full h-[40%] bg-gradient-to-t from-white via-white/50 to-white/0 pointer-events-none"></div>

        <!-- Texto superpuesto -->
        <div class="absolute bottom-[-10px] left-0 w-full flex flex-col items-center text-center text-stone-800 z-10">
            <p class="text-sm text-stone-700">Mis recuerdos</p>
            <h4 class="text-2xl font-bold">Manuel y Alejandrina 💍👰</h4>
        </div>
    </div>

    <!-- Menú móvil -->
    <nav class="w-full mt-[10px] shrink-0">
        <div class="flex justify-around items-center py-2">
            <button @click="$wire.dispatch('abrirModuloCargarImagenes')" class="flex flex-col items-center text-stone-400 hover:text-black">
                <i class="fa fa-plus text-2xl mb-1"></i>
                <span class="text-xs">Subir</span>
            </button>

            <button @click="$wire.set('seccion','galeria')" class="flex flex-col items-center text-stone-400 hover:text-black">
                <i class="fa fa-images text-2xl mb-1"></i>
                <span class="text-xs">Galería</span>
            </button>

            <button @click="$wire.set('seccion','oficiales')" class="flex flex-col items-center text-stone-400 hover:text-black">
                <i class="fa fa-star text-2xl mb-1"></i>
                <span class="text-xs">Oficiales</span>
            </button>

            <a href="#" class="flex flex-col items-center text-stone-400 hover:text-black">
                <i class="fa fa-calendar-alt text-2xl mb-1"></i>
                <span class="text-xs">Planning</span>
            </a>
        </div>
    </nav>

    <!-- Contenido dinámico (ocupa el resto con scroll) -->
    <div class="flex-1 overflow-auto">
        @if ($seccion === 'galeria')
            <livewire:galeria-component />
        @elseif ($seccion === 'oficiales')
            <livewire:oficiales-component />
        @endif
    </div>

    <!-- Modal -->
    <livewire:cargar-imagen-component />
</div>
