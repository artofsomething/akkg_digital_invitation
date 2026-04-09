<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $invitation->title }}</title>
    <meta name="description" content="You're invited to {{ $invitation->title }}">
    <meta property="og:title" content="{{ $invitation->title }}" />
    <meta property="og:description" content="You are cordially invited 💌" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 overflow-x-hidden">

    {{-- ── OPENING ANIMATION WRAPPER ── --}}
    <div
        x-data="openingAnimation()"
        x-init="init()"
        class="relative"
    >
        {{-- Opening Screen --}}
        <div
            x-show="!isOpen"
            x-cloak
            @click="openInvitation()"
            class="fixed inset-0 z-50 flex flex-col items-center justify-center cursor-pointer select-none"
            :class="isClosing ? 'animate-fade-out' : ''"
            style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);"
        >
            {{-- Stars background --}}
            <div class="absolute inset-0 overflow-hidden">
                <div class="stars"></div>
            </div>

            {{-- Envelope --}}
            <div class="relative z-10 flex flex-col items-center gap-6 px-8 text-center">

                {{-- Animated envelope --}}
                <div class="envelope-wrapper" :class="{ 'envelope-shake': showHint }">
                    <div class="envelope">
                        {{-- Envelope flap --}}
                        <div class="envelope-flap" :class="{ 'open': isOpening }"></div>
                        {{-- Envelope body --}}
                        <div class="envelope-body">
                            {{-- Letter peek --}}
                            <div class="letter-peek" :class="{ 'peek': isOpening }">
                                <span class="text-2xl">💌</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Guest greeting --}}
                @if($guestName)
                <div class="text-white/70 font-light text-sm tracking-widest uppercase">
                    Dear,
                </div>
                <div class="text-white text-2xl font-semibold" style="font-family: 'Playfair Display', serif;">
                    {{ $guestName }}
                </div>
                @endif

                {{-- Title --}}
                <div class="text-white/60 text-sm tracking-[0.3em] uppercase">
                    You Are Invited To
                </div>
                <h1 class="text-white text-3xl md:text-4xl font-bold" style="font-family: 'Playfair Display', serif;">
                    {{ $invitation->title }}
                </h1>

                {{-- CTA --}}
                <div
                    class="mt-4 flex flex-col items-center gap-2"
                    x-show="showHint"
                    x-transition:enter="transition ease-out duration-700"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                >
                    <div class="text-white/80 text-sm tracking-widest uppercase animate-bounce">
                        Tap to Open
                    </div>
                    <svg class="w-5 h-5 text-white/60 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- ── INVITATION CONTENT ── --}}
        <div
            x-show="isOpen"
            x-cloak
            x-transition:enter="transition ease-out duration-700"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
        >
            @include($templateView)
        </div>
    </div>

    {{-- Leaflet JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    {{-- Opening Animation Script --}}
    <script>
        function openingAnimation() {
            return {
                isOpen: false,
                isOpening: false,
                isClosing: false,
                showHint: false,

                init() {
                    // Show hint after 1.5s
                    setTimeout(() => { this.showHint = true; }, 1500);
                },

                openInvitation() {
                    if (this.isOpening) return;
                    this.isOpening  = true;
                    this.isClosing  = true;

                    // Envelope animation then reveal
                    setTimeout(() => {
                        this.isOpen = true;
                    }, 800);
                },
            }
        }
    </script>

    {{-- Envelope & Stars CSS --}}
    <style>
        /* ── Stars ── */
        .stars {
            width: 100%;
            height: 100%;
            background-image:
                radial-gradient(2px 2px at 20px 30px, #eee, transparent),
                radial-gradient(2px 2px at 40px 70px, #fff, transparent),
                radial-gradient(1px 1px at 90px 40px, #fff, transparent),
                radial-gradient(1px 1px at 160px 120px, #ddd, transparent),
                radial-gradient(2px 2px at 200px 50px, #fff, transparent),
                radial-gradient(1px 1px at 300px 80px, #fff, transparent),
                radial-gradient(2px 2px at 350px 200px, #eee, transparent),
                radial-gradient(1px 1px at 400px 30px, #fff, transparent),
                radial-gradient(2px 2px at 500px 100px, #fff, transparent),
                radial-gradient(1px 1px at 600px 60px, #ddd, transparent),
                radial-gradient(2px 2px at 700px 150px, #fff, transparent),
                radial-gradient(1px 1px at 800px 40px, #fff, transparent);
            background-repeat: repeat;
            animation: twinkle 4s infinite alternate;
            opacity: 0.6;
        }

        @keyframes twinkle {
            0%   { opacity: 0.4; }
            100% { opacity: 0.8; }
        }

        /* ── Envelope ── */
        .envelope-wrapper {
            position: relative;
            width: 180px;
            height: 130px;
            filter: drop-shadow(0 20px 40px rgba(0,0,0,0.4));
        }

        .envelope-shake {
            animation: shake 2s ease-in-out infinite;
        }

        @keyframes shake {
            0%, 100% { transform: rotate(0deg); }
            25%       { transform: rotate(-3deg); }
            75%       { transform: rotate(3deg); }
        }

        .envelope {
            position: relative;
            width: 180px;
            height: 130px;
        }

        /* Envelope body */
        .envelope-body {
            position: absolute;
            bottom: 0;
            width: 180px;
            height: 110px;
            background: linear-gradient(145deg, #f5e6d3, #f0d9c0);
            border-radius: 4px 4px 8px 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        /* V-shape flap */
        .envelope-flap {
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 0;
            border-left: 90px solid transparent;
            border-right: 90px solid transparent;
            border-top: 70px solid #e8c9a0;
            z-index: 10;
            transform-origin: top center;
            transition: transform 0.6s ease;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }

        .envelope-flap.open {
            transform: rotateX(180deg);
        }

        /* Letter peek */
        .letter-peek {
            background: white;
            width: 140px;
            height: 90px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transform: translateY(60px);
            transition: transform 0.6s ease 0.3s;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
        }

        .letter-peek.peek {
            transform: translateY(-10px);
        }

        /* ── Fade out ── */
        .animate-fade-out {
            animation: fadeOut 0.8s ease forwards;
        }

        @keyframes fadeOut {
            0%   { opacity: 1; transform: scale(1); }
            100% { opacity: 0; transform: scale(1.05); }
        }
    </style>
</body>
</html>