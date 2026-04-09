@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Users</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">
                        {{ $stats['total_users'] }}
                    </h3>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-2xl">
                    👥
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Invitations</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">
                        {{ $stats['total_invitations'] }}
                    </h3>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-2xl">
                    💌
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Published</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">
                        {{ $stats['total_published'] }}
                    </h3>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-2xl">
                    ✅
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Templates</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">
                        {{ $stats['total_templates'] }}
                    </h3>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center text-2xl">
                    🎨
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Popular Templates -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h2 class="font-semibold text-gray-800">🔥 Popular Templates</h2>
            </div>
            <div class="p-6">
                @forelse($popularTemplates as $template)
                <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('storage/' . $template->thumbnail) }}"
                             class="w-10 h-10 rounded-lg object-cover bg-gray-100"
                             onerror="this.src='https://placehold.co/40x40'">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $template->name }}</p>
                            <p class="text-xs text-gray-500">{{ $template->category->name ?? '-' }}</p>
                        </div>
                    </div>
                    <span class="text-sm font-semibold text-indigo-600">
                        {{ $template->invitations_count }} used
                    </span>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-4">No templates yet</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Users -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h2 class="font-semibold text-gray-800">👥 Recent Users</h2>
                <a href="{{ route('admin.users') }}"
                   class="text-sm text-indigo-600 hover:underline">View All</a>
            </div>
            <div class="p-6">
                @forelse($recentUsers as $user)
                <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center">
                            <span class="text-indigo-600 font-semibold text-sm">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.user.detail', $user) }}"
                       class="text-xs text-indigo-600 hover:underline">
                        {{ $user->invitations_count }} invitations
                    </a>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-4">No users yet</p>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Recent Invitations -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mt-6">
        <div class="p-6 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">💌 Recent Invitations</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left">
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Title</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Template</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($recentInvitations as $invitation)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $invitation->title }}
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $invitation->user->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $invitation->template->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                {{ $invitation->status === 'published'
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($invitation->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            {{ $invitation->created_at->format('d M Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                            No invitations yet
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection