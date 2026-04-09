<!-- resources/views/user/invitation/sections/gallery-upload.blade.php -->
<div x-data="galleryManager('{{ $invitation->slug }}')">

    <!-- Upload Area -->
    <div class="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center mb-4"
         @dragover.prevent @drop.prevent="handleDrop($event)">
        <div class="text-4xl mb-2">📷</div>
        <p class="text-sm text-gray-600 mb-3">Drag & drop images or click to upload</p>
        <label class="bg-indigo-600 text-white px-5 py-2 rounded-xl text-sm cursor-pointer hover:bg-indigo-700">
            Choose Images
            <input type="file" multiple accept="image/*" class="hidden"
                   @change="uploadImages($event.target.files)">
        </label>
        <p class="text-xs text-gray-400 mt-2">JPG, PNG, WEBP • Max 2MB each</p>
    </div>

    <!-- Upload Progress -->
    <div x-show="uploading" class="mb-4">
        <div class="bg-gray-100 rounded-full h-2">
            <div class="bg-indigo-600 h-2 rounded-full transition-all" :style="`width: ${progress}%`"></div>
        </div>
        <p class="text-xs text-gray-500 mt-1 text-center" x-text="`Uploading... ${progress}%`"></p>
    </div>

    <!-- Gallery Grid -->
    <div class="grid grid-cols-3 gap-3" id="gallery-grid">
        @foreach($invitation->galleries as $image)
        <div class="relative group rounded-xl overflow-hidden" id="gallery-item-{{ $image->id }}">
            <img src="{{ asset('storage/' . $image->image_path) }}"
                 class="w-full h-28 object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all">
                <button onclick="deleteImage('{{ $image->id }}')"
                        class="absolute top-2 right-2 bg-red-500 text-white w-6 h-6 rounded-full text-xs opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                    ✕
                </button>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
    function galleryManager(slug) {
        return {
            uploading: false,
            progress: 0,

            uploadImages(files) {
                if (!files.length) return;

                const formData = new FormData();
                Array.from(files).forEach(file => formData.append('images[]', file));

                this.uploading = true;
                this.progress  = 0;

                fetch(`/dashboard/gallery/${slug}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: formData,
                })
                .then(r => r.json())
                .then(data => {
                    this.uploading = false;
                    this.progress  = 100;

                    if (data.success) {
                        // Add new images to grid
                        const grid = document.getElementById('gallery-grid');
                        data.images.forEach(img => {
                            grid.innerHTML += `
                                <div class="relative group rounded-xl overflow-hidden" id="gallery-item-${img.id}">
                                    <img src="${img.image_url}" class="w-full h-28 object-cover">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all">
                                        <button onclick="deleteImage('${img.id}')"
                                                class="absolute top-2 right-2 bg-red-500 text-white w-6 h-6 rounded-full text-xs opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            ✕
                                        </button>
                                    </div>
                                </div>`;
                        });
                    }
                })
                .catch(() => { this.uploading = false; });
            },

            handleDrop(event) {
                this.uploadImages(event.dataTransfer.files);
            }
        }
    }

    function deleteImage(imageId) {
        if (!confirm('Delete this image?')) return;

        fetch(`/dashboard/gallery/${imageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`gallery-item-${imageId}`)?.remove();
            }
        });
    }

    // Save section via AJAX
    function saveSection(event, slug, sectionId) {
        event.preventDefault();
        const form    = event.target;
        const data    = Object.fromEntries(new FormData(form));

        fetch(`/dashboard/invitation/${slug}/section/${sectionId}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ content: data.content ?? data }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // Show success toast
                showToast('Section saved! ✅');
            }
        });
    }

    function showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 bg-gray-900 text-white px-4 py-2 rounded-xl text-sm z-50';
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }
</script>