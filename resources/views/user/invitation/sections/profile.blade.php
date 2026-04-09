<!-- resources/views/user/invitation/sections/profile.blade.php -->
<form onsubmit="saveSection(event, '{{ $invitation->slug }}', '{{ $section->id }}')">
    <div class="space-y-6">
        @php $persons = $section->content['persons'] ?? [[], []] @endphp

        @foreach($persons as $index => $person)
        <div class="border border-gray-100 rounded-xl p-4">
            <h4 class="font-medium text-gray-700 mb-3">
                Person {{ $index + 1 }}
            </h4>
            <div class="space-y-3">
                <div>
                    <label class="text-xs text-gray-600 mb-1 block">Full Name</label>
                    <input type="text"
                           name="content[persons][{{ $index }}][name]"
                           value="{{ $person['name'] ?? '' }}"
                           placeholder="Full Name"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>
                <div>
                    <label class="text-xs text-gray-600 mb-1 block">Role (e.g. Bride / Groom)</label>
                    <input type="text"
                           name="content[persons][{{ $index }}][role]"
                           value="{{ $person['role'] ?? '' }}"
                           placeholder="e.g. Bride"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>
                <div>
                    <label class="text-xs text-gray-600 mb-1 block">Parent / Child of</label>
                    <input type="text"
                           name="content[persons][{{ $index }}][parent]"
                           value="{{ $person['parent'] ?? '' }}"
                           placeholder="e.g. Son of Mr. & Mrs. Smith"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>
                <div>
                    <label class="text-xs text-gray-600 mb-1 block">Instagram (optional)</label>
                    <input type="text"
                           name="content[persons][{{ $index }}][instagram]"
                           value="{{ $person['instagram'] ?? '' }}"
                           placeholder="@username"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>
            </div>
        </div>
        @endforeach

        <button type="submit"
                class="bg-indigo-600 text-white px-5 py-2 rounded-xl text-sm hover:bg-indigo-700">
            Save Section
        </button>
    </div>
</form>