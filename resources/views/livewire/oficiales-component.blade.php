<div x-data="galeria" class="relative">
    <!-- Galería Principal -->
    <div class="p-4 max-w-7xl mx-auto">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="galeria-grid">
            <template x-for="(img, index) in images" :key="img.id">
                <div class="group relative bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300">
                    <!-- Imagen -->
                    <div class="aspect-square relative overflow-hidden cursor-pointer" 
                         @click="abrirSlider(index)">
                        <img :src="storageUrl + img.archivo" 
                             :alt="'Imagen ' + (index + 1)"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
                             loading="lazy">
                        
                        <!-- Overlay con estadísticas -->
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                            <div class="flex space-x-4 text-white">
                                <div class="flex items-center space-x-1">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                                    </svg>
                                    <span x-text="img.likes" class="text-sm font-medium"></span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                    </svg>
                                    <span x-text="img.hearts" class="text-sm font-medium"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Barra de reacciones -->
                    <div class="p-3 bg-white">
                        <div class="flex items-center justify-between">
                            <div class="flex space-x-3">
                                <!-- Like -->
                                <button @click="toggleReaction(img.id, 'likes')" 
                                        class="flex items-center space-x-1 transition-colors duration-200"
                                        :class="img.user_reaction === 'likes' ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600'">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                                    </svg>
                                    <span x-text="img.likes" class="text-sm font-medium"></span>
                                </button>
                                
                                <!-- Heart -->
                                <button @click="toggleReaction(img.id, 'hearts')" 
                                        class="flex items-center space-x-1 transition-colors duration-200"
                                        :class="img.user_reaction === 'hearts' ? 'text-red-500' : 'text-gray-600 hover:text-red-500'">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                    </svg>
                                    <span x-text="img.hearts" class="text-sm font-medium"></span>
                                </button>
                                
                                <!-- Unlike -->
                                <button @click="toggleReaction(img.id, 'unlikes')" 
                                        class="flex items-center space-x-1 transition-colors duration-200"
                                        :class="img.user_reaction === 'unlikes' ? 'text-gray-800' : 'text-gray-600 hover:text-gray-800'">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" transform="rotate(180)">
                                        <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                                    </svg>
                                    <span x-text="img.unlikes" class="text-sm font-medium"></span>
                                </button>
                            </div>
                            
                            <span x-text="img.created_at" class="text-xs text-gray-500"></span>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Loading indicator -->
        <div x-show="loading" class="flex justify-center items-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
            <span class="ml-2 text-gray-600">Cargando más imágenes...</span>
        </div>

        <!-- Debug info -->
        <div x-show="showDebug" class="text-center py-4 text-sm text-gray-500">
            <p>Cargadas: <span x-text="images.length"></span> | Total: <span x-text="totalImagenes"></span> | Más: <span x-text="hasMore ? 'Sí' : 'No'"></span></p>
        </div>

        <!-- Mensaje cuando no hay más imágenes -->
        <div x-show="!hasMore && !loading && images.length > 0" class="text-center py-8">
            <div class="text-gray-500 text-lg">✨ Has visto todas las imágenes (<span x-text="images.length"></span>)</div>
        </div>

        <!-- Sentinel element -->
        <div x-ref="sentinel" class="h-1"></div>
    </div>

    <!-- Modal Slider -->
    <div x-show="sliderOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 bg-black bg-opacity-95 flex items-center justify-center"
         @keydown.escape.window="cerrarSlider()"
         @keydown.arrow-left.window="anteriorImagen()"
         @keydown.arrow-right.window="siguienteImagen()"
         @click.self="cerrarSlider()">
        
        <!-- Botón cerrar - MÁS VISIBLE -->
        <button @click="cerrarSlider()" 
                class="absolute top-6 right-6 z-60 text-white hover:text-red-400 transition-colors bg-black bg-opacity-50 rounded-full p-3">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <!-- Navegación izquierda -->
        <button @click="anteriorImagen()" 
                x-show="currentImageIndex > 0"
                class="absolute left-6 top-1/2 transform -translate-y-1/2 z-60 text-white hover:text-blue-400 transition-colors bg-black bg-opacity-50 rounded-full p-3">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        <!-- Navegación derecha -->
        <button @click="siguienteImagen()" 
                x-show="currentImageIndex < images.length - 1"
                class="absolute right-6 top-1/2 transform -translate-y-1/2 z-60 text-white hover:text-blue-400 transition-colors bg-black bg-opacity-50 rounded-full p-3">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

        <!-- Contenedor del slider -->
        <div class="w-full h-full flex items-center justify-center p-4">
            <div class="relative max-w-5xl max-h-full">
                <!-- Imagen actual -->
                <div x-show="currentSliderImage" class="relative">
                    <img :src="currentSliderImage ? storageUrl + currentSliderImage.archivo : ''" 
                         :alt="'Imagen ' + (currentImageIndex + 1)"
                         class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100">
                </div>

                <!-- Barra de reacciones en el slider -->
                <div x-show="currentSliderImage" 
                     class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-black bg-opacity-70 rounded-full px-6 py-3 flex space-x-4">
                    <!-- Like -->
                    <button @click="toggleReaction(currentSliderImage.id, 'likes')" 
                            class="flex items-center space-x-2 text-white transition-colors duration-200"
                            :class="currentSliderImage?.user_reaction === 'likes' ? 'text-blue-400' : 'hover:text-blue-400'">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                        </svg>
                        <span x-text="currentSliderImage ? currentSliderImage.likes : 0" class="font-medium"></span>
                    </button>
                    
                    <!-- Heart -->
                    <button @click="toggleReaction(currentSliderImage.id, 'hearts')" 
                            class="flex items-center space-x-2 text-white transition-colors duration-200"
                            :class="currentSliderImage?.user_reaction === 'hearts' ? 'text-red-400' : 'hover:text-red-400'">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                        </svg>
                        <span x-text="currentSliderImage ? currentSliderImage.hearts : 0" class="font-medium"></span>
                    </button>
                    
                    <!-- Unlike -->
                    <button @click="toggleReaction(currentSliderImage.id, 'unlikes')" 
                            class="flex items-center space-x-2 text-white transition-colors duration-200"
                            :class="currentSliderImage?.user_reaction === 'unlikes' ? 'text-gray-400' : 'hover:text-gray-400'">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" transform="rotate(180)">
                            <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                        </svg>
                        <span x-text="currentSliderImage ? currentSliderImage.unlikes : 0" class="font-medium"></span>
                    </button>
                </div>

                <!-- Indicador de posición -->
                <div class="absolute top-4 left-1/2 transform -translate-x-1/2 bg-black bg-opacity-70 rounded-full px-4 py-2 text-white text-sm">
                    <span x-text="currentImageIndex + 1"></span> / <span x-text="images.length"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast para errores -->
    <div x-show="showError" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         class="fixed top-4 right-4 z-50 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
        <p x-text="errorMessage" class="font-medium"></p>
    </div>
</div>

@script
<script>
Alpine.data('galeria', () => ({
    images: @json($imagenes),
    storageUrl: '{{ Storage::disk("public")->url("") }}',
    loading: @json($loading),
    hasMore: @json($hasMore),
    totalImagenes: @json($totalImagenes),
    sliderOpen: false,
    currentImageIndex: 0,
    currentSliderImage: null,
    observer: null,
    preloadedImages: new Map(),
    showError: false,
    errorMessage: '',
    showDebug: false, // Cambiar a true para ver debug info

    init() {
        console.log('🎬 Galería inicializada:', {
            imagenes: this.images.length,
            total: this.totalImagenes,
            hasMore: this.hasMore
        });
        
        this.setupInfiniteScroll();
        this.setupEventListeners();
        this.setupKeyboardNavigation();
    },

    setupInfiniteScroll() {
        this.observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && this.hasMore && !this.loading) {
                    console.log('📥 Activando carga infinita...');
                    this.cargarMas();
                }
            });
        }, {
            rootMargin: '200px' // Aumentar margen para cargar antes
        });

        this.observer.observe(this.$refs.sentinel);
    },

    setupEventListeners() {
        this.$wire.on('imagenesActualizadas', (data) => {
            const eventData = Array.isArray(data) ? data[0] : data;
            console.log('📊 Imágenes actualizadas:', eventData);
            
            this.images = eventData.imagenes || [];
            this.hasMore = eventData.hasMore;
            this.loading = eventData.loading;
            this.totalImagenes = eventData.total || 0;
        });

        this.$wire.on('sliderAbierto', (data) => {
            const eventData = Array.isArray(data) ? data[0] : data;
            console.log('🖼️ Abriendo slider:', eventData);
            
            this.currentImageIndex = eventData.index;
            this.currentSliderImage = this.images[this.currentImageIndex];
            this.sliderOpen = true;
            this.precargarImagenes();
            document.body.style.overflow = 'hidden';
        });

        this.$wire.on('sliderCerrado', () => {
            console.log('❌ Cerrando slider');
            this.sliderOpen = false;
            document.body.style.overflow = 'auto';
        });

        this.$wire.on('sliderNavegado', (data) => {
            const eventData = Array.isArray(data) ? data[0] : data;
            console.log('🔄 Navegando slider:', eventData);
            
            this.currentImageIndex = eventData.index;
            this.currentSliderImage = eventData.imagen;
            this.precargarImagenes();
        });

        this.$wire.on('reaccionActualizada', (data) => {
            const eventData = Array.isArray(data) ? data[0] : data;
            this.actualizarReaccionLocal(eventData);
        });

        this.$wire.on('sesionExpirada', () => {
            this.mostrarError('Sesión expirada. Por favor, escanea el código QR nuevamente.');
        });

        this.$wire.on('errorReaccion', (message) => {
            const errorMsg = Array.isArray(message) ? message[0] : message;
            this.mostrarError(errorMsg);
        });
    },

    setupKeyboardNavigation() {
        // Navegación por teclado global
        document.addEventListener('keydown', (e) => {
            if (this.sliderOpen) {
                if (e.key === 'Escape') {
                    this.cerrarSlider();
                } else if (e.key === 'ArrowLeft') {
                    this.anteriorImagen();
                } else if (e.key === 'ArrowRight') {
                    this.siguienteImagen();
                }
            }
        });
    },

    cargarMas() {
        if (this.loading || !this.hasMore) {
            console.log('⏸️ No se puede cargar más:', { loading: this.loading, hasMore: this.hasMore });
            return;
        }
        
        console.log('🔄 Cargando más imágenes...');
        this.loading = true;
        this.$wire.cargarMasImagenes();
    },

    abrirSlider(index) {
        console.log('🎯 Abriendo slider en índice:', index);
        this.$wire.abrirSlider(index);
    },

    cerrarSlider() {
        console.log('🚪 Cerrando slider');
        this.$wire.cerrarSlider();
    },

    anteriorImagen() {
        if (this.currentImageIndex > 0) {
            console.log('⬅️ Imagen anterior');
            this.$wire.navegarSlider('anterior');
        }
    },

    siguienteImagen() {
        if (this.currentImageIndex < this.images.length - 1) {
            console.log('➡️ Imagen siguiente');
            this.$wire.navegarSlider('siguiente');
        } else if (this.hasMore && !this.loading) {
            console.log('📥 Cargando más para continuar navegación');
            this.cargarMas();
        }
    },

    precargarImagenes() {
        const start = Math.max(0, this.currentImageIndex - 2);
        const end = Math.min(this.images.length - 1, this.currentImageIndex + 2);

        for (let i = start; i <= end; i++) {
            if (this.images[i] && !this.preloadedImages.has(i)) {
                const img = new Image();
                img.crossOrigin = "anonymous";
                img.src = this.storageUrl + this.images[i].archivo;
                this.preloadedImages.set(i, img);
            }
        }
    },

    toggleReaction(imageId, type) {
        this.$wire.toggleReaction(imageId, type);
    },

    actualizarReaccionLocal(eventData) {
        const { imageId, type, reactionAdded, previousType, newCounts, userReaction } = eventData;
        
        // Actualizar en la galería principal
        const imageIndex = this.images.findIndex(img => img.id === imageId);
        if (imageIndex !== -1) {
            const imagen = this.images[imageIndex];
            
            // Actualizar contadores con los valores del servidor
            imagen.likes = newCounts.likes;
            imagen.unlikes = newCounts.unlikes;
            imagen.hearts = newCounts.hearts;
            
            // Actualizar estado de reacción del usuario
            imagen.user_reaction = userReaction;
        }

        // Actualizar imagen actual del slider si coincide
        if (this.currentSliderImage && this.currentSliderImage.id === imageId) {
            this.currentSliderImage = { ...this.images[imageIndex] };
        }
    },

    mostrarError(message) {
        this.errorMessage = message;
        this.showError = true;
        setTimeout(() => {
            this.showError = false;
        }, 5000);
    },

    destroy() {
        if (this.observer) {
            this.observer.disconnect();
        }
        document.body.style.overflow = 'auto';
    }
}));
</script>
@endscript
