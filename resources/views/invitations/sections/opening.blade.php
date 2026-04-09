@php $content = $sections['opening']->content ?? []; @endphp

<section
    class="relative min-h-screen flex items-center justify-center overflow-hidden"
    style="
        @if(!empty($content['background_image']))
            background-image: url('{{ Storage::url($content['background_image']) }}');
            background-size: cover;
            background-position: center;
        @else
            background: linear-gradient(135deg, var(--color-primary, #1a1a2e) 0%, var(--color-secondary, #16213e) 100%);
        @endif
    "
>
    {{-- Overlay --}}
    <div class="absolute inset-0 bg-black/40"></div>

    <div class="relative z-10 text-center px-6 py-20">
        <p class="text-white/70 text-sm tracking-[0.3em] uppercase mb-4 font-light">
            {{ $content['subtitle'] ?? 'We invite you to celebrate with us' }}
        </p>
        <h1 class="text-white text-4xl md:text-6xl font-bold mb-6" style="font-family: 'Playfair Display', serif;">
            {{ $content['title'] ?? $invitation->title }}
        </h1>
        @if($invitation->event_date)
        <p class="text-white/80 text-lg">
            {{ $invitation->event_date->translatedFormat('l, d F Y') }}
        </p>
        @endif

        {{-- Scroll down hint --}}
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-white/60">
            <span class="text-xs tracking-widest uppercase">Scroll</span>
            <svg class="w-5 h-5 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
    </div>
</section>