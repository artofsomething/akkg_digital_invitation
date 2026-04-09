@php $content = $sections['gallery']->content ?? []; @endphp

<section class="py-20 px-6 bg-white">
    <div class="max-w-5xl mx-auto">

        {{-- Header --}}
        <div class="text-center mb-12">
            <p class="text-sm tracking-[0.3em] uppercase text-gray-400 mb-3">Our</p>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800" style="font-family: 'Playfair Display', serif;">
                {{ $content['title'] ?? 'Gallery' }}
            </h2>
            @if(!empty($content['subtitle']))
            <p class="text-gray-500 mt-3">{{ $content['subtitle'] }}</p>
            @endif
            <div class="w-16 h-0.5 bg-amber-400 mx-auto mt-4"></div>
        </div>

        {{-- Gallery Grid --}}
        @if($invitation->galleries->count() > 0)
        <div
            x-data="galleryLightbox()"
            class="columns-2 md:columns-3 gap-3 space-y-3"
        >
            @foreach($invitation->galleries as $index => $photo)
            <div
                class="break-inside-avoid cursor-pointer overflow-hidden rounded-lg group"
                @click="openLightbox({{ $index }})"
            >
                <img
                    src="{{ Storage::url($photo->image_path) }}"
                    alt="{{ $photo->caption ?? 'Gallery photo' }}"
                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                    loading="lazy"
                />
            </div>
            @endforeach

            {{-- Lightbox --}}
            <div
                x-show="isOpen"
                x-cloak
                @click.self="closeLightbox()"
                @keydown.escape.window="closeLightbox()"
                @keydown.arrow-left.window="prev()"
                @keydown.arrow-right.window="next()"
                class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
            >
                <button @click="closeLightbox()" class="absolute top-4 right-4 text-white/80 hover:text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <button @click="prev()" class="absolute left-4 text-white/80 hover:text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <img
                    :src="photos[current].src"
                    :alt="photos[current].caption"
                    class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl"
                />
                <button @click="next()" class="absolute right-4 text-white/80 hover:text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <div x-show="photos[current].caption" class="absolute bottom-4 text-white/80 text-sm" x-text="photos[current].caption"></div>
            </div>
        </div>

        <script>
            function galleryLightbox() {
                return {
                    isOpen: false,
                    current: 0,
                    photos: @json($invitation->galleries->map(fn($g) => [
                        'src'     => Storage::url($g->image_path),
                        'caption' => $g->caption,
                    ])),

                    openLightbox(index) {
                        this.current = index;
                        this.isOpen  = true;
                        document.body.style.overflow = 'hidden';
                    },
                    closeLightbox() {
                        this.isOpen = false;
                        document.body.style.overflow = '';
                    },
                    prev() {
                        this.current = this.current > 0 ? this.current - 1 : this.photos.length - 1;
                    },
                    next() {
                        this.current = this.current < this.photos.length - 1 ? this.current + 1 : 0;
                    },
                }
            }
        </script>
        @else
        <p class="text-center text-gray-400 italic">No photos yet.</p>
        @endif
    </div>
</section>