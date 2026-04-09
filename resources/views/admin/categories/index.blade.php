<!-- resources/views/admin/categories/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Categories')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Add Category Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 h-fit">
        <h3 class="font-semibold text-gray-800 mb-4">Add New Category</h3>
        <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category Name</label>
                <input type="text" name="name" placeholder="e.g. Wedding"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Icon (Emoji)</label>
                <input type="text" name="icon" placeholder="e.g. 💍"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
            </div>
            <button type="submit"
                    class="w-full bg-indigo-600 text-white py-2.5 rounded-xl text-sm font-medium hover:bg-indigo-700">
                Add Category
            </button>
        </form>
    </div>

    <!-- Categories List -->
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">All Categories</h3>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($categories as $category)
            <div class="p-5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">{{ $category->icon }}</span>
                    <div>
                        <p class="font-medium text-gray-800">{{ $category->name }}</p>
                        <p class="text-xs text-gray-500">
                            {{ $category->templates_count }} templates •
                            <span class="{{ $category->is_active ? 'text-green-600' : 'text-red-500' }}">
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <!-- Toggle Active -->
                    <form method="POST" action="{{ route('admin.categories.update', $category) }}">
                        @csrf @method('PUT')
                        <input type="hidden" name="name" value="{{ $category->name }}">
                        <input type="hidden" name="icon" value="{{ $category->icon }}">
                        <input type="hidden" name="is_active" value="{{ $category->is_active ? 0 : 1 }}">
                        <button type="submit"
                                class="text-xs px-3 py-1.5 rounded-lg border
                                    {{ $category->is_active
                                        ? 'border-green-200 text-green-600 bg-green-50'
                                        : 'border-gray-200 text-gray-500' }}">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </button>
                    </form>

                    <!-- Delete -->
                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                          onsubmit="return confirm('Delete this category?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="text-xs px-3 py-1.5 rounded-lg border border-red-200 text-red-500 bg-red-50 hover:bg-red-100">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-gray-400">No categories yet</div>
            @endforelse
        </div>
    </div>
</div>

@endsection