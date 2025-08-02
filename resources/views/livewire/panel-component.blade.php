<div class="h-screen flex flex-col">
    <!-- Portada -->
    <div class="relative w-full h-[30%] shrink-0">
        <img src="{{ asset('img/portada1.jpg') }}" class="w-full h-full object-cover object-center" alt="">

        <!-- Gradiente -->
        <div
            class="absolute bottom-0 left-0 w-full h-[40%] bg-gradient-to-t from-white via-white/50 to-white/0 pointer-events-none">
        </div>

        <!-- Texto superpuesto -->
        <div class="absolute bottom-[-10px] left-0 w-full flex flex-col items-center text-center text-stone-800 z-10">
            <p class="text-sm text-stone-700">Mis recuerdos</p>
            <h2 class="text-2xl font-semibold text-center text-pink-700 mb-2">{{ $nombre }} 💍👰</h2>
        </div>
    </div>

    <!-- Menú móvil -->
    <nav class="w-full mt-[10px] shrink-0">
        <div class="flex justify-around items-center py-2">
            @if ($puedeSubirImagenes)
                <button @click="$wire.dispatch('abrirModuloCargarImagenes')"
                    class="flex flex-col items-center text-stone-400 hover:text-black">
                    <i class="fa fa-plus text-2xl mb-1"></i>
                    <span class="text-xs">Subir</span>
                </button>
            @endif

            @if ($puedeVerGaleria)
                <button @click="$wire.set('seccion','galeria')"
                    class="flex flex-col items-center text-stone-400 hover:text-black">
                    <i class="fa fa-images text-2xl mb-1"></i>
                    <span class="text-xs">Galería</span>
                </button>
            @endif


            @if ($puedeVerPrincipal)
                <button @click="$wire.set('seccion','oficiales')"
                    class="flex flex-col items-center text-stone-400 hover:text-black">
                    <i class="fa fa-star text-2xl mb-1"></i>
                    <span class="text-xs">Oficiales</span>
                </button>
            @endif
            @if ($puedeVerPlanning)
                <button @click="$wire.set('seccion','planning')"
                    class="flex flex-col items-center text-stone-400 hover:text-black">
                    <i class="fa fa-calendar text-2xl mb-1"></i>
                    <span class="text-xs">Planning</span>
                </button>
            @endif


            @auth
                <a href="{{ route('settings.boda') }}" class="flex flex-col items-center text-stone-400 hover:text-black">
                    <i class="fa fa-cogs text-2xl mb-1"></i>
                    <span class="text-xs">Config.</span>
                </a>
            @endauth
        </div>
    </nav>

    <!-- Contenido dinámico (ocupa el resto con scroll) -->
    <div class="flex-1 overflow-auto">
        @if ($seccion === 'galeria')
            <livewire:galeria-component />
        @elseif ($seccion === 'oficiales')
            <livewire:oficiales-component />
        @elseif ($seccion === 'planning')
            @include('partials.planning')
        @endif
    </div>

    <!-- Modal -->
    <livewire:cargar-imagen-component />
</div>
