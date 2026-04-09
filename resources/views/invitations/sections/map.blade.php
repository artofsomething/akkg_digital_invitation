@php $content = $sections['map']->content ?? []; @endphp

<section class="py-20 px-6 bg-gray-50">
    <div class="max-w-4xl mx-auto">

        {{-- Header --}}
        <div class="text-center mb-12">
            <p class="text-sm tracking-[0.3em] uppercase text-gray-400 mb-3">Find</p>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800" style="font-family: 'Playfair Display', serif;">
                {{ $content['title'] ?? 'Location' }}
            </h2>
            <div class="w-16 h-0.5 bg-amber-400 mx-auto mt-4"></div>
        </div>

        {{-- Address --}}
        @if($invitation->event_location || $invitation->event_address)
        <div class="text-center mb-8">
            @if($invitation->event_location)
            <h3 class="text-xl font-semibold text-gray-800 mb-2">
                {{ $invitation->event_location }}
            </h3>
            @endif
            @if($invitation->event_address)
            <p class="text-gray-500 text-sm leading-relaxed max-w-md mx-auto">
                {{ $invitation->event_address }}
            </p>
            @endif
        </div>
        @endif

        {{-- Map --}}
        @if($invitation->latitude && $invitation->longitude)
        <div
            x-data="leafletMap({{ $invitation->latitude }}, {{ $invitation->longitude }}, '{{ addslashes($invitation->event_location ?? 'Event Location') }}')"
            x-init="init()"
        >
            {{-- Map Container --}}
            <div
                id="invitation-map"
                class="w-full h-72 md:h-96 rounded-2xl overflow-hidden shadow-lg border border-gray-200"
            ></div>

            {{-- Open in Google Maps button --}}
            <div class="flex justify-center mt-4">
                <a
                    href="https://www.google.com/maps?q={{ $invitation->latitude }},{{ $invitation->longitude }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-gray-200 rounded-full text-gray-700 text-sm font-medium shadow-sm hover:shadow-md hover:bg-gray-50 transition-all duration-300"
                >
                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                    Open in Google Maps
                </a>
            </div>
        </div>

        <script>
            function leafletMap(lat, lng, label) {
                return {
                    map: null,
                    init() {
                        this.$nextTick(() => {
                            this.map = L.map('invitation-map', {
                                zoomControl: true,
                                scrollWheelZoom: false,
                            }).setView([lat, lng], 16);

                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '© OpenStreetMap contributors',
                            }).addTo(this.map);

                            // Custom marker
                            const icon = L.divIcon({
                                html: `<div style="
                                    background: #f59e0b;
                                    width: 40px;
                                    height: 40px;
                                    border-radius: 50% 50% 50% 0;
                                    transform: rotate(-45deg);
                                    border: 4px solid white;
                                    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
                                "></div>`,
                                iconSize: [40, 40],
                                iconAnchor: [20, 40],
                                className: '',
                            });

                            L.marker([lat, lng], { icon })
                                .addTo(this.map)
                                .bindPopup(`<strong>${label}</strong>`)
                                .openPopup();
                        });
                    }
                }
            }
        </script>
        @else
        <div class="text-center text-gray-400 italic">Location not specified.</div>
        @endif
    </div>
</section>