@php
    $content   = $sections['the_date']->content ?? [];
    $eventDate = $invitation->event_date;
    $eventTime = $invitation->event_time;
@endphp

<section class="py-20 px-6 bg-gray-50">
    <div class="max-w-3xl mx-auto text-center">

        {{-- Header --}}
        <p class="text-sm tracking-[0.3em] uppercase text-gray-400 mb-3">Mark Your</p>
        <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4" style="font-family: 'Playfair Display', serif;">
            {{ $content['title'] ?? 'Save The Date' }}
        </h2>
        <div class="w-16 h-0.5 bg-amber-400 mx-auto mb-12"></div>

        {{-- Date display --}}
        @if($eventDate)
        <div class="flex items-center justify-center gap-4 mb-12">
            <div class="text-center">
                <div class="text-5xl md:text-7xl font-bold text-gray-800" style="font-family: 'Playfair Display', serif;">
                    {{ $eventDate->format('d') }}
                </div>
                <div class="text-sm tracking-widest uppercase text-gray-500 mt-1">
                    {{ $eventDate->translatedFormat('F') }}
                </div>
            </div>
            <div class="text-amber-400 text-4xl font-light">·</div>
            <div class="text-center">
                <div class="text-5xl md:text-7xl font-bold text-gray-800" style="font-family: 'Playfair Display', serif;">
                    {{ $eventDate->format('Y') }}
                </div>
                <div class="text-sm tracking-widest uppercase text-gray-500 mt-1">
                    {{ $eventDate->translatedFormat('l') }}
                </div>
            </div>
            @if($eventTime)
            <div class="text-amber-400 text-4xl font-light">·</div>
            <div class="text-center">
                <div class="text-5xl md:text-7xl font-bold text-gray-800" style="font-family: 'Playfair Display', serif;">
                    {{ \Carbon\Carbon::parse($eventTime)->format('H:i') }}
                </div>
                <div class="text-sm tracking-widest uppercase text-gray-500 mt-1">WIB</div>
            </div>
            @endif
        </div>
        @endif

        {{-- ── COUNTDOWN TIMER ── --}}
        @if(($content['show_countdown'] ?? true) && $eventDate)
        <div
            x-data="countdown('{{ $eventDate->format('Y-m-d') }} {{ $eventTime ?? '00:00:00' }}')"
            x-init="start()"
            class="grid grid-cols-4 gap-3 md:gap-6 max-w-md mx-auto"
        >
            {{-- Days --}}
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 md:w-20 md:h-20 bg-white rounded-xl shadow-md flex items-center justify-center border border-gray-100">
                    <span class="text-2xl md:text-3xl font-bold text-gray-800" x-text="days" style="font-family: 'Playfair Display', serif;"></span>
                </div>
                <span class="text-xs text-gray-400 tracking-widest uppercase mt-2">Days</span>
            </div>
            {{-- Hours --}}
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 md:w-20 md:h-20 bg-white rounded-xl shadow-md flex items-center justify-center border border-gray-100">
                    <span class="text-2xl md:text-3xl font-bold text-gray-800" x-text="hours" style="font-family: 'Playfair Display', serif;"></span>
                </div>
                <span class="text-xs text-gray-400 tracking-widest uppercase mt-2">Hours</span>
            </div>
            {{-- Minutes --}}
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 md:w-20 md:h-20 bg-white rounded-xl shadow-md flex items-center justify-center border border-gray-100">
                    <span class="text-2xl md:text-3xl font-bold text-gray-800" x-text="minutes" style="font-family: 'Playfair Display', serif;"></span>
                </div>
                <span class="text-xs text-gray-400 tracking-widest uppercase mt-2">Minutes</span>
            </div>
            {{-- Seconds --}}
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 md:w-20 md:h-20 bg-white rounded-xl shadow-md flex items-center justify-center border border-gray-100">
                    <span class="text-2xl md:text-3xl font-bold text-amber-500" x-text="seconds" style="font-family: 'Playfair Display', serif;"></span>
                </div>
                <span class="text-xs text-gray-400 tracking-widest uppercase mt-2">Seconds</span>
            </div>

            {{-- Event passed message --}}
            <div x-show="isPast" x-cloak class="col-span-4 text-center text-gray-500 text-sm mt-2">
                🎉 The event has taken place. Thank you!
            </div>
        </div>

        <script>
            function countdown(eventDateTimeStr) {
                return {
                    days: '00', hours: '00', minutes: '00', seconds: '00',
                    isPast: false,
                    interval: null,

                    start() {
                        this.calculate();
                        this.interval = setInterval(() => this.calculate(), 1000);
                    },

                    calculate() {
                        const target = new Date(eventDateTimeStr).getTime();
                        const now    = new Date().getTime();
                        const diff   = target - now;

                        if (diff <= 0) {
                            this.isPast = true;
                            this.days = this.hours = this.minutes = this.seconds = '00';
                            clearInterval(this.interval);
                            return;
                        }

                        this.days    = String(Math.floor(diff / (1000 * 60 * 60 * 24))).padStart(2, '0');
                        this.hours   = String(Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                        this.minutes = String(Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                        this.seconds = String(Math.floor((diff % (1000 * 60)) / 1000)).padStart(2, '0');
                    }
                }
            }
        </script>
        @endif

        {{-- Venue --}}
        @if(!empty($content['venue']) || $invitation->event_location)
        <div class="mt-12 text-center">
            <p class="text-gray-500 text-sm tracking-widest uppercase mb-2">Venue</p>
            <p class="text-gray-800 text-lg font-semibold">
                {{ $content['venue'] ?? $invitation->event_location }}
            </p>
        </div>
        @endif
    </div>
</section>