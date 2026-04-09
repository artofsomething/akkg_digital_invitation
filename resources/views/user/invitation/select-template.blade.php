<!-- resources/views/user/invitation/select-template.blade.php -->
@extends('layouts.app')

@section('title', 'Choose Template')

@section('content')

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
            <a href="{{ route('user.dashboard') }}" class="hover:text-indigo-600">Dashboard</a>
            <span>/</span>
            <a href="{{ route('user.invitation.category') }}" class="hover:text-indigo-600">Category</a>
            <span>/</span>
            <span class="text-gray-800">Template</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-800">
            {{ $category->icon }} {{ $category->name }} Templates
        </h1>
        <p class="text-gray-500 mt-1">Choose a template for your invitation</p>
    </div>

    <!-- Steps Indicator -->
    <div class="flex items-center gap-2 mb-8">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center text-sm">✓</div>
            <span class="text-sm text-green-600">Category</span>
        </div>
        <div class="flex-1 h-px bg-green-200"></div>
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold">2</div>
            <span class="text-sm font-medium text-indigo-600">Template</span>
        </div>
        <div class="flex-1 h-px bg-gray-200"></div>
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center text-sm font-bold">3</div>
            <span class="text-sm text-gray-400">Details</span>
        </div>
    </div>

    <!-- Templates Grid -->
    @if($templates->isEmpty())
    <div class="bg-white rounded-xl p-12 text-center border border-gray-100">
        <div class="text-5xl mb-4">🎨</div>
        <h3 class="text-lg font-semibold text-gray-800">No templates available</h3>
        <p class="text-gray-500 mt-1">Templates for this category coming soon!</p>
        <a href="{{ route('user.invitation.category') }}"
           class="inline-block mt-4 text-indigo-600 hover:underline text-sm">
            ← Back to Categories
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($templates as $template)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all group">

            <!-- Thumbnail -->
            <div class="relative h-52 bg-gray-100 overflow-hidden">
                <img src="{{ asset('storage/' . $template->thumbnail) }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                     onerror="this.src='https://placehold.co/400x200/e2e8f0/94a3b8?text=Preview'">

                <!-- Premium Badge -->
                @if($template->is_premium)
                <div class="absolute top-3 right-3">
                    <span class="bg-amber-400 text-amber-900 text-xs font-bold px-2 py-1 rounded-full">
                        ⭐ Premium
                    </span>
                </div>
                @endif

                <!-- Preview Overlay -->
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all flex items-center justify-center">
                    <a href="{{ route('user.invitation.create', $template->slug) }}"
                       class="bg-white text-gray-800 px-6 py-2 rounded-xl text-sm font-medium opacity-0 group-hover:opacity-100 transition-all transform translate-y-2 group-hover:translate-y-0">
                        Use This Template
                    </a>
                </div>
            </div>

            <!-- Info -->
            <div class="p-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-semibold text-gray-800">{{ $template->name }}</h3>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Used {{ $template->used_count }} times
                        </p>
                    </div>

                    <!-- Color Scheme Preview -->
                    @if($template->color_scheme)
                    <div class="flex gap-1">
                        @foreach(array_slice($template->color_scheme ?? [], 0, 3) as $color)
                        <div class="w-4 h-4 rounded-full border border-gray-200"
                             style="background-color: {{ $color }}">
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                <a href="{{ route('user.invitation.create', $template->slug) }}"
                   class="block mt-4 text-center bg-indigo-600 text-white py-2.5 rounded-xl text-sm font-medium hover:bg-indigo-700 transition-colors">
                    Select Template →
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Back Button -->
    <div class="mt-8">
        <a href="{{ route('user.invitation.category') }}"
           class="text-gray-500 hover:text-gray-700 text-sm">
            ← Back to Categories
        </a>
    </div>

@endsection