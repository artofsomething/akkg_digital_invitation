<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $invitation->title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
</head>
<body class="font-serif bg-[#f9f5f0]">

    @php
        $sections = $invitation->sections->keyBy('section_type');
    @endphp

    <!-- ===== OPENING SECTION ===== -->
    @if($sections->get('opening')?->is_visible)
    @php $opening = $sections->get('opening')->content; @endphp
    <section id="opening" class="fixed inset-0 z-50 flex items-center justify-center bg-[#2c1810]"
             x-data="{ opened: false }" x-show="!opened">
        <div class="text-center text-white p-8">
            <p class="text-sm tracking-widest mb-4 opacity-75">WE ARE GETTING MARRIED</p>
            <h1 class="text-5xl font-bold mb-2">{{ $opening['bride_name'] ?? '' }}</h1>
            <p class="text-2xl my-3">&</p>
            <h1 class="text-5xl font-bold mb-8">{{ $opening['groom_name'] ?? '' }}</h1>
            <p class="text-sm mb-6 opacity-75">{{ $invitation->event_date?->format('d F Y') }}</p>
            <button @click="opened = true"
                    class="border border-white px-10 py-3 tracking-widest text-sm hover:bg-white hover:text-[#2c1810] transition-all">
                OPEN INVITATION
            </button>
        </div>
    </section>
    @endif

    <!-- ===== MAIN CONTENT ===== -->
    <main>

        <!-- Profile Section -->
        @if($sections->get('profile')?->is_visible)
        @php $profile = $sections->get('profile')->content; @endphp
        <section id="profile" class="min-h-screen flex flex-col items-center justify-center py-20 px-6">
            <p class="text-sm tracking-widest text-gray-500 mb-8">THE COUPLE</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 max-w-4xl w-full">
                @foreach(($profile['persons'] ?? []) as $person)
                <div class="text-center">
                    <div class="w-48 h-48 rounded-full overflow-hidden mx-auto mb-4 border-4 border-amber-200">
                        <img src="{{ asset('storage/' . ($person['photo'] ?? '')) }}" 
                             class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $person['name'] ?? '' }}</h3>
                    <p class="text-gray-500 mt-1">{{ $person['parent'] ?? '' }}</p>
                    <p class="text-gray-400 text-sm mt-1">{{ $person['role'] ?? '' }}</p>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        <!-- The Date Section -->
        @if($sections->get('the_date')?->is_visible)
        <section id="the-date" class="py-20 bg-[#2c1810] text-white text-center px-6">
            <p class="tracking-widest text-amber-300 mb-4 text-sm">SAVE THE DATE</p>
            <h2 class="text-4xl font-bold mb-8">{{ $invitation->event_date?->format('d F Y') }}</h2>

            <!-- Countdown Timer -->
            <div class="flex justify-center gap-8 mb-10"
                 x-data="countdown('{{ $invitation->event_date }}')" x-init="start()">
                <div class="text-center">
                    <span class="text-4xl font-bold" x-text="days">00</span>
                    <p class="text-xs tracking-widest opacity-75">DAYS</p>
                </div>
                <div class="text-center">
                    <span class="text-4xl font-bold" x-text="hours">00</span>
                    <p class="text-xs tracking-widest opacity-75">HOURS</p>
                </div>
                <div class="text-center">
                    <span class="text-4xl font-bold" x-text="minutes">00</span>
                    <p class="text-xs tracking-widest opacity-75">MINUTES</p>
                </div>
                <div class="text-center">
                    <span class="text-4xl font-bold" x-text="seconds">00</span>
                    <p class="text-xs tracking-widest opacity-75">SECONDS</p>
                </div>
            </div>
            
            <p class="text-lg">{{ $invitation->event_location }}</p>
            <p class="text-sm opacity-75 mt-2">{{ $invitation->event_address }}</p>
        </section>
        @endif

        <!-- Gallery Section -->
        @if($sections->get('gallery')?->is_visible && $invitation->galleries->isNotEmpty())
        <section id="gallery" class="py-20 px-6">
            <p class="text-center tracking-widest text-gray-500 text-sm mb-8">OUR MOMENTS</p>
            <div class="swiper max-w-4xl mx-auto">
                <div class="swiper-wrapper">
                    @foreach($invitation->galleries as $image)
                    <div class="swiper-slide">
                        <img src="{{ asset('storage/' . $image->image_path) }}" 
                             class="w-full h-80 object-cover rounded-xl">
                        @if($image->caption)
                        <p class="text-center text-gray-500 mt-2 text-sm">{{ $image->caption }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
                <div class="swiper-pagination mt-4"></div>
            </div>
        </section>
        @endif

        <!-- Map Section -->
        @if($sections->get('map')?->is_visible && $invitation->latitude)
        <section id="map-section" class="py-20 bg-gray-50 px-6">
            <p class="text-center tracking-widest text-gray-500 text-sm mb-8">LOCATION</p>
            <div id="map" class="h-80 max-w-4xl mx-auto rounded-xl overflow-hidden shadow-lg"></div>
            <div class="text-center mt-6">
                <a href="https://maps.google.com/?q={{ $invitation->latitude }},{{ $invitation->longitude }}" 
                   target="_blank"
                   class="bg-[#2c1810] text-white px-8 py-3 rounded-full text-sm tracking-widest hover:bg-[#4a2c1c]">
                    OPEN IN MAPS
                </a>
            </div>
        </section>
        @endif

        <!-- Guest Book Section -->
        @if($sections->get('guest_book')?->is_visible)
        <section id="guestbook" class="py-20 px-6 max-w-2xl mx-auto">
            <p class="text-center tracking-widest text-gray-500 text-sm mb-8">GUEST BOOK</p>
            
            <!-- Messages -->
            <div class="space-y-4 mb-10 max-h-96 overflow-y-auto">
                @foreach($invitation->guestBooks as $message)
                <div class="bg-white rounded-xl p-4 shadow-sm">
                    <p class="font-semibold text-gray-800">{{ $message->guest_name }}</p>
                    <p class="text-gray-600 text-sm mt-1">{{ $message->guest_message }}</p>
                    <p class="text-gray-400 text-xs mt-2">{{ $message->created_at->diffForHumans() }}</p>
                </div>
                @endforeach
            </div>
            
            <!-- Form -->
            <form action="{{ route('invitation.guestbook', $invitation) }}" method="POST" class="space-y-4">
                @csrf
                <input type="text" name="guest_name" placeholder="Your Name"
                       class="w-full border rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-amber-300">
                <textarea name="guest_message" placeholder="Your message..." rows="4"
                          class="w-full border rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-amber-300"></textarea>
                <button type="submit"
                        class="w-full bg-[#2c1810] text-white py-3 rounded-xl tracking-widest text-sm hover:bg-[#4a2c1c]">
                    SEND MESSAGE 💌
                </button>
            </form>
        </section>
        @endif

        <!-- RSVP Section -->
        @if($sections->get('rsvp')?->is_visible)
        <section id="rsvp" class="py-20 bg-[#f0e8df] px-6">
            <div class="max-w-2xl mx-auto">
                <p class="text-center tracking-widest text-gray-500 text-sm mb-8">RSVP</p>
                
                @if(session('success'))
                <div class="bg-green-100 text-green-700 p-4 rounded-xl mb-6 text-center">
                    {{ session('success') }}
                </div>
                @endif
                
                <form action="{{ route('invitation.rsvp', $invitation) }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="text" name="guest_name" placeholder="Your Full Name" required
                           class="w-full border bg-white rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-amber-300">
                    <input type="text" name="guest_phone" placeholder="Phone Number (optional)"
                           class="w-full border bg-white rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-amber-300">
                    
                    <div class="grid grid-cols-3 gap-3">
                        @foreach(['yes' => '✅ Will Attend', 'no' => '❌ Cannot Attend', 'maybe' => '🤔 Maybe'] as $val => $label)
                        <label class="cursor-pointer">
                            <input type="radio" name="attendance" value="{{ $val }}" class="peer hidden" required>
                            <div class="border-2 bg-white rounded-xl p-3 text-center text-sm peer-checked:border-amber-400 peer-checked:bg-amber-50 hover:border-amber-300">
                                {{ $label }}
                            </div>
                        </label>
                        @endforeach
                    </div>
                    
                    <select name="total_person" 
                            class="w-full border bg-white rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-amber-300">
                        @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}">{{ $i }} Person(s)</option>
                        @endfor
                    </select>
                    
                    <textarea name="message" placeholder="Additional message (optional)" rows="3"
                              class="w-full border bg-white rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-amber-300"></textarea>
                    
                    <button type="submit"
                            class="w-full bg-[#2c1810] text-white py-4 rounded-xl tracking-widest text-sm hover:bg-[#4a2c1c]">
                        CONFIRM ATTENDANCE
                    </button>
                </form>
            </div>
        </section>
        @endif

    </main>

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // Swiper
        new Swiper('.swiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            pagination: { el: '.swiper-pagination', clickable: true },
            breakpoints: {
                768: { slidesPerView: 2 }
            }
        });

        // Leaflet Map
        @if($invitation->latitude)
        const map = L.map('map').setView([{{ $invitation->latitude }}, {{ $invitation->longitude }}], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        L.marker([{{ $invitation->latitude }}, {{ $invitation->longitude }}])
            .addTo(map)
            .bindPopup(`<b>{{ $invitation->event_location }}</b>`)
            .openPopup();
        @endif

        // Alpine.js Countdown
        function countdown(dateStr) {
            return {
                days: '00', hours: '00', minutes: '00', seconds: '00',
                start() {
                    setInterval(() => {
                        const diff = new Date(dateStr) - new Date();
                        if (diff <= 0) return;
                        this.days    = String(Math.floor(diff / 86400000)).padStart(2, '0');
                        this.hours   = String(Math.floor((diff % 86400000) / 3600000)).padStart(2, '0');
                        this.minutes = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
                        this.seconds = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
                    }, 1000);
                }
            }
        }
    </script>
</body>
</html>