<!-- resources/views/user/invitation/select-category.blade.php -->
@extends('layouts.app')

@section('title', 'Choose Category')

@section('content')

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
            <a href="{{ route('user.dashboard') }}" class="hover:text-indigo-600">Dashboard</a>
            <span>/</span>
            <span class="text-gray-800">Create Invitation</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-800">Choose Category</h1>
        <p class="text-gray-500 mt-1">Select the type of your event</p>
    </div>

    <!-- Steps Indicator -->
    <div class="flex items-center gap-2 mb-8">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold">1</div>
            <span class="text-sm font-medium text-indigo-600">Category</span>
        </div>
        <div class="flex-1 h-px bg-gray-200"></div>
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center text-sm font-bold">2</div>
            <span class="text-sm text-gray-400">Template</span>
        </div>
        <div class="flex-1 h-px bg-gray-200"></div>
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center text-sm font-bold">3</div>
            <span class="text-sm text-gray-400">Details</span>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($categories as $category)
        <a href="{{ route('user.invitation.template', $category->slug) }}"
           class="group bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center hover:border-indigo-300 hover:shadow-md transition-all">

            <div class="text-5xl mb-4 group-hover:scale-110 transition-transform inline-block">
                {{ $category->icon }}
            </div>

            <h3 class="font-semibold text-gray-800 text-lg">{{ $category->name }}</h3>

            <p class="text-sm text-gray-500 mt-1">
                {{ $category->templates_count }} templates available
            </p>

            <div class="mt-4 text-indigo-600 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                Choose →
            </div>
        </a>
        @endforeach
    </div>

@endsection