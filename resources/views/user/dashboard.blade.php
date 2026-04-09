@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')

    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                👋 Welcome, {{ auth()->user()->name }}
            </h1>
            <p class="text-gray-500 mt-1">Manage your digital invitations</p>
        </div>
        <a href="{{ route('user.invitation.category') }}"
           class="bg-indigo-600 text-white px-6 py-3 rounded-xl hover:bg-indigo-700 transition-colors font-medium">
            + Create Invitation
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Total</p>
            <h3 class="text-2xl font-bold text-gray-800 mt-1">
                {{ $stats['total_invitations'] }}
            </h3>
            <p class="text-xs text-gray-400 mt-1">Invitations</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Published</p>
            <h3 class="text-2xl font-bold text-green-600 mt-1">
                {{ $stats['total_published'] }}
            </h3>
            <p class="text-xs text-gray-400 mt-1">Live Now</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Total</p>
            <h3 class="text-2xl font-bold text-blue-600 mt-1">
                {{ $stats['total_views'] }}
            </h3>
            <p class="text-xs text-gray-400 mt-1">Views</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Total</p>
            <h3 class="text-2xl font-bold text-orange-600 mt-1">
                {{ $stats['total_rsvps'] }}
            </h3>
            <p class="text-xs text-gray-400 mt-1">RSVPs</p>
        </div>
    </div>

    <!-- Invitations Grid -->
    @if($invitations->isEmpty())
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
        <div class="text-6xl mb-4">💌</div>
        <h3 class="text-lg font-semibold text-gray-800 mb-2">No invitations yet</h3>
        <p class="text-gray-500 mb-6">Create your first digital invitation!</p>
        <a href="{{ route('user.invitation.category') }}"
           class="bg-indigo-600 text-white px-8 py-3 rounded-xl hover:bg-indigo-700 transition-colors">
            Create Now
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($invitations as $invitation)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">

            <!-- Thumbnail -->
            <div class="relative h-48 bg-gray-100">
                <img src="{{ asset('storage/' . $invitation->template->thumbnail) }}"
                     class="w-full h-full object-cover"
                     onerror="this.src='https://placehold.co/400x200'">

                <!-- Status Badge -->
                <div class="absolute top-3 right-3">
                    <span class="px-3 py-1 rounded-full text-xs font-medium
                        {{ $invitation->status === 'published'
                            ? 'bg-green-500 text-white'
                            : 'bg-gray-800 text-white opacity-75' }}">
                        {{ ucfirst($invitation->status) }}
                    </span>
                </div>

                <!-- Category Badge -->
                <div class="absolute top-3 left-3">
                    <span class="px-2 py-1 rounded-full text-xs bg-white text-gray-600 shadow-sm">
                        {{ $invitation->template->category->icon ?? '🎉' }}
                        {{ $invitation->template->category->name ?? '' }}
                    </span>
                </div>
            </div>

            <!-- Content -->
            <div class="p-5">
                <h3 class="font-semibold text-gray-800 truncate">
                    {{ $invitation->title }}
                </h3>
                <p class="text-sm text-gray-500 mt-1">
                    📅 {{ $invitation->event_date?->format('d M Y') ?? 'No date set' }}
                </p>
                <p class="text-sm text-gray-500">
                    📍 {{ Str::limit($invitation->event_location, 40) ?? 'No location' }}
                </p>

                <!-- Stats -->
                <div class="flex gap-4 mt-3 pt-3 border-t border-gray-50">
                    <span class="text-xs text-gray-500">
                        👁 {{ $invitation->views->count() }} views
                    </span>
                    <span class="text-xs text-gray-500">
                        ✉️ {{ $invitation->rsvps->count() }} rsvps
                    </span>
                    <span class="text-xs text-gray-500">
                        💬 {{ $invitation->guestBooks->count() }} messages
                    </span>
                </div>

                <!-- Actions -->
                <div class="flex gap-2 mt-4">
                    <a href="{{ route('user.invitation.edit', $invitation->slug) }}"
                       class="flex-1 text-center bg-indigo-50 text-indigo-600 py-2 rounded-lg text-sm hover:bg-indigo-100 transition-colors">
                        ✏️ Edit
                    </a>

                    @if($invitation->status === 'published')
                    <a href="{{ route('invitation.show', $invitation->slug) }}"
                       target="_blank"
                       class="flex-1 text-center bg-green-50 text-green-600 py-2 rounded-lg text-sm hover:bg-green-100 transition-colors">
                        👁 View
                    </a>
                    @else
                    <form method="POST"
                          action="{{ route('user.invitation.publish', $invitation->slug) }}"
                          class="flex-1">
                        @csrf @method('PATCH')
                        <button type="submit"
                                class="w-full bg-green-50 text-green-600 py-2 rounded-lg text-sm hover:bg-green-100 transition-colors">
                            🚀 Publish
                        </button>
                    </form>
                    @endif

                    <!-- Delete -->
                    <form method="POST"
                          action="{{ route('user.invitation.destroy', $invitation->slug) }}"
                          onsubmit="return confirm('Delete this invitation?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="bg-red-50 text-red-500 p-2 rounded-lg hover:bg-red-100 transition-colors">
                            🗑
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

@endsection