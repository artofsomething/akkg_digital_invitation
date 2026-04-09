<!-- resources/views/admin/templates/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Templates')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-lg font-semibold text-gray-800">All Templates</h2>
    <a href="{{ route('admin.templates.create') }}"
       class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-indigo-700">
        + Add Template
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($templates as $template)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="relative h-44">
            <img src="{{ asset('storage/' . $template->thumbnail) }}"
                 class="w-full h-full object-cover"
                 onerror="this.src='https://placehold.co/400x200'">
            <div class="absolute top-3 right-3 flex gap-1">
                @if($template->is_premium)
                <span class="bg-amber-400 text-amber-900 text-xs px-2 py-0.5 rounded-full font-bold">⭐ Premium</span>
                @endif
                <span class="text-xs px-2 py-0.5 rounded-full font-medium
                    {{ $template->is_active ? 'bg-green-500 text-white' : 'bg-gray-500 text-white' }}">
                    {{ $template->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>
        <div class="p-4">
            <p class="text-xs text-gray-500">{{ $template->category->name ?? '-' }}</p>
            <h3 class="font-semibold text-gray-800 mt-0.5">{{ $template->name }}</h3>
            <p class="text-xs text-gray-500 mt-1">
                Used {{ $template->invitations_count }} times
            </p>
            <div class="flex gap-2 mt-4">
                <a href="{{ route('admin.templates.edit', $template) }}"
                   class="flex-1 text-center bg-indigo-50 text-indigo-600 py-2 rounded-xl text-sm hover:bg-indigo-100">
                    Edit
                </a>
                <form method="POST" action="{{ route('admin.templates.destroy', $template) }}"
                      onsubmit="return confirm('Delete this template?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="bg-red-50 text-red-500 px-3 py-2 rounded-xl text-sm hover:bg-red-100">
                        🗑
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-3 bg-white rounded-xl p-12 text-center text-gray-400">
        No templates yet
    </div>
    @endforelse
</div>

@endsection