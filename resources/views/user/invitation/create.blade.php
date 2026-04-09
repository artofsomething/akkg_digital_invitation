<!-- resources/views/user/invitation/create.blade.php -->
@extends('layouts.app')

@section('title', 'Create Invitation')

@section('content')

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
            <a href="{{ route('user.dashboard') }}" class="hover:text-indigo-600">Dashboard</a>
            <span>/</span>
            <a href="{{ route('user.invitation.category') }}" class="hover:text-indigo-600">Category</a>
            <span>/</span>
            <span class="text-gray-800">Create</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-800">Create Invitation</h1>
        <p class="text-gray-500 mt-1">Fill in the details for your invitation</p>
    </div>

    <!-- Steps Indicator -->
    <div class="flex items-center gap-2 mb-8">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center text-sm">✓</div>
            <span class="text-sm text-green-600">Category</span>
        </div>
        <div class="flex-1 h-px bg-green-200"></div>
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center text-sm">✓</div>
            <span class="text-sm text-green-600">Template</span>
        </div>
        <div class="flex-1 h-px bg-green-200"></div>
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold">3</div>
            <span class="text-sm font-medium text-indigo-600">Details</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Form -->
        <div class="lg:col-span-2">
            <form action="{{ route('user.invitation.store') }}" method="POST"
                  class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                @csrf

                <!-- Hidden Template ID -->
                <input type="hidden" name="template_id" value="{{ $template->id }}">

                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Invitation Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="title"
                           value="{{ old('title') }}"
                           placeholder="e.g. Wedding of John & Jane"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent @error('title') border-red-300 @enderror">
                    @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Event Date & Time -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Event Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date"
                               name="event_date"
                               value="{{ old('event_date') }}"
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('event_date') border-red-300 @enderror">
                        @error('event_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Event Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time"
                               name="event_time"
                               value="{{ old('event_time') }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('event_time') border-red-300 @enderror">
                        @error('event_time')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Event Location -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Venue Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="event_location"
                           value="{{ old('event_location') }}"
                           placeholder="e.g. Grand Ballroom Hotel XYZ"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('event_location') border-red-300 @enderror">
                    @error('event_location')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Event Address -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Full Address
                    </label>
                    <textarea name="event_address"
                              rows="2"
                              placeholder="e.g. Jl. Sudirman No. 1, Jakarta Pusat"
                              class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">{{ old('event_address') }}</textarea>
                </div>

                <!-- Map Location -->
                <div x-data="mapPicker()">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Pin Location on Map
                    </label>

                    <!-- Search Box -->
                    <div class="flex gap-2 mb-3">
                        <input type="text"
                               x-model="searchQuery"
                               @keydown.enter.prevent="searchLocation()"
                               placeholder="Search location..."
                               class="flex-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        <button type="button"
                                @click="searchLocation()"
                                class="bg-indigo-600 text-white px-4 py-2.5 rounded-xl text-sm hover:bg-indigo-700">
                            Search
                        </button>
                    </div>

                    <!-- Map Container -->
                    <div id="map-picker" class="h-64 rounded-xl overflow-hidden border border-gray-200 mb-3"></div>

                    <!-- Coordinates (Hidden) -->
                    <input type="hidden" name="latitude" x-model="latitude" id="latitude">
                    <input type="hidden" name="longitude" x-model="longitude" id="longitude">

                    <p class="text-xs text-gray-500">
                        📍 Selected:
                        <span x-text="latitude ? latitude + ', ' + longitude : 'None'"></span>
                    </p>
                </div>

                <!-- Submit -->
                <div class="flex gap-3 pt-2">
                    <a href="{{ route('user.invitation.template', $template->category->slug) }}"
                       class="flex-1 text-center border border-gray-200 text-gray-600 py-3 rounded-xl text-sm hover:bg-gray-50">
                        ← Back
                    </a>
                    <button type="submit"
                                                        class="flex-1 bg-indigo-600 text-white py-3 rounded-xl text-sm font-medium hover:bg-indigo-700 transition-colors">
                        Create Invitation →
                    </button>
                </div>

            </form>
        </div>

        <!-- Template Preview Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-4">
                <img src="{{ asset('storage/' . $template->thumbnail) }}"
                     class="w-full h-48 object-cover"
                     onerror="this.src='https://placehold.co/400x200/e2e8f0/94a3b8?text=Preview'">
                <div class="p-4">
                    <h3 class="font-semibold text-gray-800">{{ $template->name }}</h3>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $template->category->name }}
                    </p>

                    @if($template->color_scheme)
                    <div class="mt-3">
                        <p class="text-xs text-gray-500 mb-2">Color Scheme</p>
                        <div class="flex gap-2">
                            @foreach($template->color_scheme as $name => $color)
                            <div class="text-center">
                                <div class="w-8 h-8 rounded-lg border border-gray-200 mb-1"
                                     style="background-color: {{ $color }}">
                                </div>
                                <p class="text-xs text-gray-400">{{ ucfirst($name) }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($template->font_family)
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <p class="text-xs text-gray-500">Font</p>
                        <p class="text-sm text-gray-700 font-medium">{{ $template->font_family }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <!-- Leaflet Map Script -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        function mapPicker() {
            return {
                latitude: '{{ old('latitude') }}',
                longitude: '{{ old('longitude') }}',
                searchQuery: '',
                map: null,
                marker: null,

                init() {
                    this.$nextTick(() => {
                        this.initMap();
                    });
                },

                initMap() {
                    const defaultLat = this.latitude || -6.2088;
                    const defaultLng = this.longitude || 106.8456;

                    this.map = L.map('map-picker').setView([defaultLat, defaultLng], 13);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap contributors'
                    }).addTo(this.map);

                    // Click to set marker
                    this.map.on('click', (e) => {
                        this.setMarker(e.latlng.lat, e.latlng.lng);
                    });

                    // Set marker if old values exist
                    if (this.latitude && this.longitude) {
                        this.setMarker(this.latitude, this.longitude);
                    }
                },

                setMarker(lat, lng) {
                    this.latitude  = lat.toFixed(8);
                    this.longitude = lng.toFixed(8);

                    if (this.marker) {
                        this.marker.setLatLng([lat, lng]);
                    } else {
                        this.marker = L.marker([lat, lng], { draggable: true }).addTo(this.map);
                        this.marker.on('dragend', (e) => {
                            const pos = e.target.getLatLng();
                            this.latitude  = pos.lat.toFixed(8);
                            this.longitude = pos.lng.toFixed(8);
                        });
                    }
                },

                searchLocation() {
                    if (!this.searchQuery) return;

                    fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(this.searchQuery)}&format=json&limit=1`)
                        .then(r => r.json())
                        .then(data => {
                            if (data.length > 0) {
                                const lat = parseFloat(data[0].lat);
                                const lng = parseFloat(data[0].lon);
                                this.map.setView([lat, lng], 15);
                                this.setMarker(lat, lng);
                            } else {
                                alert('Location not found. Try a different search term.');
                            }
                        });
                }
            }
        }
    </script>

@endsection