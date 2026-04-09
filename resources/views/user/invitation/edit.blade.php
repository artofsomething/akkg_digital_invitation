<!-- resources/views/user/invitation/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Edit Invitation')

@section('content')

<div class="flex justify-between items-center mb-6">
    <div>
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
            <a href="{{ route('user.dashboard') }}" class="hover:text-indigo-600">Dashboard</a>
            <span>/</span>
            <span class="text-gray-800">Edit</span>
        </div>
        <h1 class="text-xl font-bold text-gray-800">{{ $invitation->title }}</h1>
        <div class="flex items-center gap-3 mt-1">
            <span class="text-xs px-2 py-1 rounded-full
                {{ $invitation->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                {{ ucfirst($invitation->status) }}
            </span>
            @if($invitation->status === 'published')
            <a href="{{ route('invitation.show', $invitation->slug) }}"
               target="_blank"
               class="text-xs text-indigo-600 hover:underline">
                View Live →
            </a>
            @endif
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex gap-3">
        @if($invitation->status === 'draft')
        <form method="POST" action="{{ route('user.invitation.publish', $invitation->slug) }}">
            @csrf @method('PATCH')
            <button type="submit"
                    class="bg-green-600 text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-green-700">
                🚀 Publish
            </button>
        </form>
        @else
        <form method="POST" action="{{ route('user.invitation.unpublish', $invitation->slug) }}">
            @csrf @method('PATCH')
            <button type="submit"
                    class="bg-gray-600 text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-700">
                Unpublish
            </button>
        </form>
        @endif

        <a href="{{ route('user.dashboard') }}"
           class="border border-gray-200 text-gray-600 px-5 py-2.5 rounded-xl text-sm hover:bg-gray-50">
            ← Dashboard
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Left: Section Editor -->
    <div class="lg:col-span-2 space-y-4">

        <!-- Basic Info Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-center justify-between p-5 border-b border-gray-100 cursor-pointer"
                 x-data="{ open: true }" @click="open = !open">
                <div class="flex items-center gap-3">
                    <span class="text-xl">📋</span>
                    <h3 class="font-semibold text-gray-800">Basic Information</h3>
                </div>
                <svg class="w-5 h-5 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>

            <div x-show="open" x-data="{ open: true }">
                <form method="POST"
                      action="{{ route('user.invitation.update', $invitation->slug) }}"
                      class="p-5 space-y-4">
                    @csrf @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title" value="{{ $invitation->title }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Event Date</label>
                            <input type="date" name="event_date"
                                   value="{{ $invitation->event_date?->format('Y-m-d') }}"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Event Time</label>
                            <input type="time" name="event_time"
                                   value="{{ $invitation->event_time }}"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Venue Name</label>
                        <input type="text" name="event_location"
                               value="{{ $invitation->event_location }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Address</label>
                        <textarea name="event_address" rows="2"
                                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">{{ $invitation->event_address }}</textarea>
                    </div>

                    <button type="submit"
                            class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-indigo-700">
                        Save Changes
                    </button>
                </form>
            </div>
        </div>

        <!-- Dynamic Sections -->
        @foreach($sections as $section)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden"
             x-data="{ open: false }">

            <!-- Section Header -->
            <div class="flex items-center justify-between p-5 border-b border-gray-100">
                <div class="flex items-center gap-3 cursor-pointer flex-1" @click="open = !open">
                    <span class="text-xl">
                        @switch($section->section_type)
                            @case('opening')    🎊 @break
                            @case('profile')    👤 @break
                            @case('the_date')   📅 @break
                            @case('gallery')    🖼️ @break
                            @case('map')        📍 @break
                            @case('guest_book') 📖 @break
                            @case('rsvp')       ✉️ @break
                        @endswitch
                    </span>
                    <div>
                        <h3 class="font-semibold text-gray-800">
                            {{ ucwords(str_replace('_', ' ', $section->section_type)) }}
                        </h3>
                        <p class="text-xs text-gray-500">
                            {{ $section->is_visible ? 'Visible' : 'Hidden' }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <!-- Toggle Visibility -->
                    <button type="button"
                            onclick="toggleSection('{{ $section->id }}', '{{ $invitation->slug }}')"
                            class="text-xs px-3 py-1.5 rounded-lg border
                                {{ $section->is_visible
                                    ? 'border-green-200 text-green-600 bg-green-50'
                                    : 'border-gray-200 text-gray-500 bg-gray-50' }}"
                            id="toggle-btn-{{ $section->id }}">
                        {{ $section->is_visible ? '👁 Visible' : '🙈 Hidden' }}
                    </button>

                    <!-- Expand -->
                    <button @click="open = !open"
                            class="p-1.5 text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5 transition-transform" :class="open ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Section Content Editor -->
            <div x-show="open" class="p-5">
                @include('user.invitation.sections.' . $section->section_type, [
                    'section'    => $section,
                    'invitation' => $invitation,
                ])
            </div>
        </div>
        @endforeach

        <!-- Gallery Upload -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden"
             x-data="{ open: false }">
            <div class="flex items-center justify-between p-5 border-b border-gray-100 cursor-pointer"
                 @click="open = !open">
                <div class="flex items-center gap-3">
                    <span class="text-xl">🖼️</span>
                    <h3 class="font-semibold text-gray-800">Gallery Images</h3>
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">
                        {{ $invitation->galleries->count() }} photos
                    </span>
                </div>
                <svg class="w-5 h-5 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>

            <div x-show="open" class="p-5">
                @include('user.invitation.sections.gallery-upload', ['invitation' => $invitation])
            </div>
        </div>

    </div>

    <!-- Right: Info Sidebar -->
    <div class="space-y-4">

        <!-- Invitation Link -->
        @if($invitation->status === 'published')
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-800 mb-3">🔗 Invitation Link</h3>
            <div class="bg-gray-50 rounded-xl p-3 flex items-center gap-2">
                <p class="text-xs text-gray-600 flex-1 truncate">
                    {{ route('invitation.show', $invitation->slug) }}
                </p>
                <button onclick="copyLink('{{ route('invitation.show', $invitation->slug) }}')"
                        class="text-indigo-600 text-xs hover:underline whitespace-nowrap">
                    Copy
                </button>
            </div>
        </div>
        @endif

        <!-- Quick Stats -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-800 mb-3">📊 Quick Stats</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">👁 Total Views</span>
                    <span class="font-semibold text-gray-800">{{ $invitation->views->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">✉️ RSVPs</span>
                    <span class="font-semibold text-gray-800">{{ $invitation->rsvps->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">💬 Messages</span>
                    <span class="font-semibold text-gray-800">{{ $invitation->guestBooks->count() }}</span>
                </div>
            </div>
        </div>

        <!-- Template Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <img src="{{ asset('storage/' . $invitation->template->thumbnail) }}"
                 class="w-full h-32 object-cover"
                 onerror="this.src='https://placehold.co/400x200'">
            <div class="p-4">
                <p class="text-xs text-gray-500">Template</p>
                <p class="font-medium text-gray-800">{{ $invitation->template->name }}</p>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-5">
            <h3 class="font-semibold text-red-600 mb-3">⚠️ Danger Zone</h3>
            <form method="POST"
                  action="{{ route('user.invitation.destroy', $invitation->slug) }}"
                  onsubmit="return confirm('Are you sure? This cannot be undone!')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="w-full bg-red-50 text-red-600 py-2.5 rounded-xl text-sm hover:bg-red-100 transition-colors">
                    🗑 Delete Invitation
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Toggle Section Visibility
    function toggleSection(sectionId, invitationSlug) {
        fetch(`/dashboard/invitation/${invitationSlug}/section/${sectionId}/toggle`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }
        })
        .then(r => r.json())
        .then(data => {
            const btn = document.getElementById(`toggle-btn-${sectionId}`);
            if (data.is_visible) {
                btn.textContent = '👁 Visible';
                btn.className = 'text-xs px-3 py-1.5 rounded-lg border border-green-200 text-green-600 bg-green-50';
            } else {
                btn.textContent = '🙈 Hidden';
                btn.className = 'text-xs px-3 py-1.5 rounded-lg border border-gray-200 text-gray-500 bg-gray-50';
            }
        });
    }

    // Copy Link
    function copyLink(url) {
        navigator.clipboard.writeText(url).then(() => {
            alert('Link copied! 🎉');
        });
    }
</script>

@endsection