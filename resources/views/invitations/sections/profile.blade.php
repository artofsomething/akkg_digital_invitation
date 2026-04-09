@php $content = $sections['profile']->content ?? []; @endphp

<section class="py-20 px-6">
    <div class="max-w-4xl mx-auto">

        {{-- Section Header --}}
        <div class="text-center mb-16">
            <p class="text-sm tracking-[0.3em] uppercase text-gray-400 mb-3">The</p>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800" style="font-family: 'Playfair Display', serif;">
                Couple
            </h2>
            <div class="w-16 h-0.5 bg-amber-400 mx-auto mt-4"></div>
        </div>

        {{-- Persons Grid --}}
        <div class="flex flex-col md:flex-row items-center justify-center gap-8 md:gap-16">
            @foreach($content['persons'] ?? [] as $index => $person)

                <div class="flex flex-col items-center text-center gap-4" data-aos="fade-up">
                    {{-- Photo --}}
                    <div class="relative">
                        <div class="w-40 h-40 rounded-full overflow-hidden border-4 border-amber-200 shadow-xl">
                            @if(!empty($person['photo']))
                                <img
                                    src="{{ Storage::url($person['photo']) }}"
                                    alt="{{ $person['name'] }}"
                                    class="w-full h-full object-cover"
                                />
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-amber-100 to-amber-200 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Info --}}
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800" style="font-family: 'Playfair Display', serif;">
                            {{ $person['name'] ?? 'Name' }}
                        </h3>
                        @if(!empty($person['role']))
                        <p class="text-amber-600 text-sm font-medium tracking-wide mt-1">
                            {{ $person['role'] }}
                        </p>
                        @endif
                        @if(!empty($person['parent']))
                        <p class="text-gray-500 text-sm mt-2 leading-relaxed">
                            {{ $person['parent'] }}
                        </p>
                        @endif
                        @if(!empty($person['instagram']))
                        <a
                            href="https://instagram.com/{{ ltrim($person['instagram'], '@') }}"
                            target="_blank"
                            class="inline-flex items-center gap-1.5 mt-3 text-sm text-pink-500 hover:text-pink-600 transition-colors"
                        >
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                            {{ $person['instagram'] }}
                        </a>
                        @endif
                    </div>
                </div>

                {{-- Divider between persons --}}
                @if(!$loop->last)
                <div class="flex flex-col items-center gap-2 text-gray-300">
                    <div class="hidden md:block w-0.5 h-20 bg-gradient-to-b from-transparent via-amber-300 to-transparent"></div>
                    <span class="text-3xl text-amber-400">&</span>
                    <div class="hidden md:block w-0.5 h-20 bg-gradient-to-b from-transparent via-amber-300 to-transparent"></div>
                </div>
                @endif

            @endforeach
        </div>
    </div>
</section>