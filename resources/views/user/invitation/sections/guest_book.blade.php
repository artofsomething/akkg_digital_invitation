<!-- resources/views/user/invitation/sections/guest_book.blade.php -->
<form onsubmit="saveSection(event, '{{ $invitation->slug }}', '{{ $section->id }}')">
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Section Title</label>
            <input type="text" name="content[title]"
                   value="{{ $section->content['title'] ?? 'Guest Book' }}"
                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
            <input type="text" name="content[subtitle]"
                   value="{{ $section->content['subtitle'] ?? 'Leave your wishes here' }}"
                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
        </div>
        <button type="submit"
                class="bg-indigo-600 text-white px-5 py-2 rounded-xl text-sm hover:bg-indigo-700">
            Save Section
        </button>
    </div>
</form>