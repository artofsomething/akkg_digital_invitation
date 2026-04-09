@php $content = $sections['rsvp']->content ?? []; @endphp

<section class="py-20 px-6 bg-gray-50">
    <div class="max-w-lg mx-auto">

        {{-- Header --}}
        <div class="text-center mb-12">
            <p class="text-sm tracking-[0.3em] uppercase text-gray-400 mb-3">Kindly</p>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800" style="font-family: 'Playfair Display', serif;">
                {{ $content['title'] ?? 'RSVP' }}
            </h2>
            @if(!empty($content['subtitle']))
            <p class="text-gray-500 mt-3 text-sm">{{ $content['subtitle'] }}</p>
            @endif
            @if(!empty($content['deadline']))
            <p class="text-amber-600 text-xs mt-2 font-medium">
                Please respond by {{ \Carbon\Carbon::parse($content['deadline'])->translatedFormat('d F Y') }}
            </p>
            @endif
            <div class="w-16 h-0.5 bg-amber-400 mx-auto mt-4"></div>
        </div>

        {{-- Alpine RSVP Component --}}
        <div x-data="rsvpForm('{{ $invitation->slug }}', '{{ $guestName }}')">

            {{-- ── Success State