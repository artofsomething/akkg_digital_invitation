<!-- resources/views/user/invitation/sections/map.blade.php -->
<form onsubmit="saveSection(event, '{{ $invitation->slug }}', '{{ $section->id }}')">
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Section Title</label>
            <input type="text" name="content[title]"
                   value="{{ $section->content['title'] ?? 'Location' }}"
                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
        </div>
        <div class="bg-gray-50 rounded-xl p-3 text-sm text-gray-600">
            📍 Map coordinates are set from Basic Information section
            <br>
            <span class="text-xs text-gray-500">
                Current: {{ $invitation->latitude ?? 'Not set' }}, {{ $invitation->longitude ?? 'Not set' }}
            </span>
        </div>
        <button type="submit"
                class="bg-indigo-600 text-white px-5 py-2 rounded-xl text-sm hover:bg-indigo-700">
            Save Section
        </button>
    </div>
</form>