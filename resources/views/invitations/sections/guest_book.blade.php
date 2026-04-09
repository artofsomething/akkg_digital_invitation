@php $content = $sections['guest_book']->content ?? []; @endphp

<section class="py-20 px-6 bg-white">
    <div class="max-w-2xl mx-auto">

        {{-- Header --}}
        <div class="text-center mb-12">
            <p class="text-sm tracking-[0.3em] uppercase text-gray-400 mb-3">Leave</p>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800" style="font-family: 'Playfair Display', serif;">
                {{ $content['title'] ?? 'Guest Book' }}
            </h2>
            @if(!empty($content['subtitle']))
            <p class="text-gray-500 mt-3 text-sm">{{ $content['subtitle'] }}</p>
            @endif
            <div class="w-16 h-0.5 bg-amber-400 mx-auto mt-4"></div>
        </div>

        {{-- Alpine Component --}}
        <div
            x-data="guestBook('{{ $invitation->slug }}')"
            x-init="fetchMessages()"
        >
            {{-- Form --}}
            <form @submit.prevent="submitMessage()" class="bg-gray-50 rounded-2xl p-6 mb-8 shadow-sm border border-gray-100">
                <h3 class="text-gray-700 font-semibold mb-4">Send Your Wishes 💌</h3>

                <div class="space-y-4">
                    {{-- Name --}}
                    <div>
                        <label class="block text-sm text-gray-600 mb-1.5 font-medium">Your Name</label>
                        <input
                            type="text"
                            x-model="form.guest_name"
                            :disabled="loading"
                            placeholder="e.g. John Doe"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-transparent transition bg-white disabled:opacity-60"
                        />
                        <p x-show="errors.guest_name" x-text="errors.guest_name" class="text-red-500 text-xs mt-1"></p>
                    </div>

                    {{-- Message --}}
                    <div>
                        <label class="block text-sm text-gray-600 mb-1.5 font-medium">Your Message</label>
                        <textarea
                            x-model="form.guest_message"
                            :disabled="loading"
                            rows="3"
                            placeholder="Write your wishes here..."
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-transparent transition bg-white resize-none disabled:opacity-60"
                        ></textarea>
                        <div class="flex justify-between items-center mt-1">
                            <p x-show="errors.guest_message" x-text="errors.guest_message" class="text-red-500 text-xs"></p>
                            <span class="text-xs text-gray-400 ml-auto" x-text="(form.guest_message?.length ?? 0) + '/500'"></span>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button
                        type="submit"
                        :disabled="loading"
                        class="w-full py-3 bg-amber-500 hover:bg-amber-600 disabled:bg-amber-300 text-white text-sm font-semibold rounded-xl transition-all duration-300 flex items-center justify-center gap-2"
                    >
                        <span x-show="!loading">Send Wishes 💌</span>
                        <span x-show="loading" class="flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                            </svg>
                            Sending...
                        </span>
                    </button>

                    {{-- Success message --}}
                    <div
                        x-show="successMsg"
                        x-cloak
                        x-transition
                        class="text-center text-green-600 text-sm font-medium py-2 bg-green-50 rounded-xl"
                        x-text="successMsg"
                    ></div>
                </div>
            </form>

            {{-- Messages List --}}
            <div class="space-y-4">
                <div x-show="fetching" class="flex justify-center py-8">
                    <svg class="animate-spin w-6 h-6 text-amber-400" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                </div>

                <template x-for="(msg, index) in messages" :key="msg.id">
                    <div
                        class="flex gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-100"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                    >
                        {{-- Avatar --}}
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 font-bold text-sm uppercase">
                            <span x-text="msg.guest_name.charAt(0)"></span>
                        </div>
                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-semibold text-gray-800 text-sm" x-text="msg.guest_name"></span>
                                <span class="text-gray-400 text-xs" x-text="msg.time_ago"></span>
                            </div>
                            <p class="text-gray-600 text-sm mt-1 leading-relaxed" x-text="msg.guest_message"></p>
                        </div>
                    </div>
                </template>

                <div x-show="!fetching && messages.length === 0" class="text-center text-gray-400 text-sm italic py-8">
                    No messages yet. Be the first! 💌
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function guestBook(slug) {
    return {
        slug,
        messages: [],
        fetching: false,
        loading: false,
        successMsg: '',
        form: { guest_name: '', guest_message: '' },
        errors: {},

        async fetchMessages() {
            this.fetching = true;
            try {
                const res  = await fetch(`/inv/${this.slug}/guest-book`);
                const data = await res.json();
                if (data.success) this.messages = data.guest_books;
            } catch (e) {
                console.error('Failed to fetch guest books', e);
            } finally {
                this.fetching = false;
            }
        },

        async submitMessage() {
            this.errors     = {};
            this.successMsg = '';

            // Client-side validation
            if (!this.form.guest_name.trim()) {
                this.errors.guest_name = 'Name is required.';
            }
            if (!this.form.guest_message.trim()) {
                this.errors.guest_message = 'Message is required.';
            }
            if (Object.keys(this.errors).length > 0) return;

            this.loading = true;
            try {
                const res = await fetch(`/inv/${this.slug}/guest-book`, {
                    method:  'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                    },
                    body: JSON.stringify(this.form),
                });

                const data = await res.json();

                if (res.ok && data.success) {
                    // Prepend new message to top
                    this.messages.unshift(data.data);
                    this.successMsg   = data.message;
                    this.form         = { guest_name: '', guest_message: '' };
                    // Clear success msg after 4s
                    setTimeout(() => { this.successMsg = ''; }, 4000);
                } else {
                    // Handle validation errors from Laravel
                    if (data.errors) this.errors = data.errors;
                }
            } catch (e) {
                console.error('Failed to submit', e);
            } finally {
                this.loading = false;
            }
        },
    }
}
</script>