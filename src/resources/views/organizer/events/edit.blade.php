<x-organizer-layout>
    <x-slot name="header">Edit Event</x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100">
                <h1 class="text-base font-semibold text-gray-900">Edit Event</h1>
                <p class="text-sm text-gray-500 mt-0.5 truncate">{{ $event->title }}</p>
            </div>

            <form method="POST" action="{{ route('organizer.events.update', $event) }}"
                  enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                {{-- Banner Image --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Foto / Poster Event
                        <span class="text-gray-400 font-normal">(opsional, maks. 3MB)</span>
                    </label>

                    {{-- Current / Preview Image --}}
                    <div id="image-preview" class="{{ $event->banner_image || old('_has_image') ? '' : 'hidden' }} mb-3 relative group cursor-pointer" onclick="document.getElementById('banner_image').click()">
                        <img id="preview-img"
                             src="{{ $event->banner_image ? Storage::url($event->banner_image) : '' }}"
                             alt="Preview"
                             class="w-full h-52 object-cover rounded-xl border border-gray-200">
                        <div class="absolute inset-0 bg-black/40 rounded-xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <p class="text-white text-sm font-medium">Klik untuk ganti foto</p>
                        </div>
                    </div>

                    {{-- Drop Zone (hidden if image exists) --}}
                    <label id="upload-zone" for="banner_image"
                           class="{{ $event->banner_image ? 'hidden' : '' }} flex flex-col items-center justify-center w-full h-44 border-2 border-dashed rounded-xl cursor-pointer transition-colors border-gray-300 hover:border-indigo-400 hover:bg-indigo-50">
                        <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm font-medium text-gray-500">Klik atau drag foto/poster event</p>
                        <p class="text-xs text-gray-400 mt-1">PNG, JPG, WEBP · Maksimal 3MB</p>
                    </label>
                    <input type="file" id="banner_image" name="banner_image" accept="image/*" class="hidden"
                           onchange="previewBanner(this)">
                    @error('banner_image')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Judul --}}
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                        Judul Event <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title', $event->title) }}"
                           class="w-full px-3 py-2.5 text-sm border rounded-xl transition-colors
                                  {{ $errors->has('title') ? 'border-red-400 bg-red-50' : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500' }}
                                  focus:outline-none focus:ring-1">
                    @error('title')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                        Deskripsi <span class="text-red-500">*</span>
                    </label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full px-3 py-2.5 text-sm border rounded-xl transition-colors
                                     {{ $errors->has('description') ? 'border-red-400 bg-red-50' : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500' }}
                                     focus:outline-none focus:ring-1">{{ old('description', $event->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kategori + Tanggal --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select id="category" name="category"
                                class="w-full px-3 py-2.5 text-sm border rounded-xl transition-colors
                                       {{ $errors->has('category') ? 'border-red-400 bg-red-50' : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500' }}
                                       focus:outline-none focus:ring-1">
                            @foreach($categories as $value => $label)
                                <option value="{{ $value }}" {{ old('category', $event->category) === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="event_date" class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal & Waktu <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" id="event_date" name="event_date"
                               value="{{ old('event_date', $event->event_date->format('Y-m-d\TH:i')) }}"
                               class="w-full px-3 py-2.5 text-sm border rounded-xl transition-colors
                                      {{ $errors->has('event_date') ? 'border-red-400 bg-red-50' : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500' }}
                                      focus:outline-none focus:ring-1">
                        @error('event_date')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Lokasi --}}
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-1">
                        Lokasi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="location" name="location" value="{{ old('location', $event->location) }}"
                           class="w-full px-3 py-2.5 text-sm border rounded-xl transition-colors
                                  {{ $errors->has('location') ? 'border-red-400 bg-red-50' : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500' }}
                                  focus:outline-none focus:ring-1">
                    @error('location')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Harga + Kapasitas --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="ticket_price" class="block text-sm font-medium text-gray-700 mb-1">
                            Harga Tiket (Rp) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium">Rp</span>
                            <input type="number" id="ticket_price" name="ticket_price"
                                   value="{{ old('ticket_price', $event->ticket_price) }}" min="0" step="1000"
                                   class="w-full pl-10 pr-3 py-2.5 text-sm border rounded-xl transition-colors
                                          {{ $errors->has('ticket_price') ? 'border-red-400 bg-red-50' : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500' }}
                                          focus:outline-none focus:ring-1">
                        </div>
                        @error('ticket_price')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="ticket_capacity" class="block text-sm font-medium text-gray-700 mb-1">
                            Kapasitas Tiket <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="ticket_capacity" name="ticket_capacity"
                               value="{{ old('ticket_capacity', $event->ticket_capacity) }}" min="1"
                               class="w-full px-3 py-2.5 text-sm border rounded-xl transition-colors
                                      {{ $errors->has('ticket_capacity') ? 'border-red-400 bg-red-50' : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500' }}
                                      focus:outline-none focus:ring-1">
                        @error('ticket_capacity')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Status --}}
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                    <p class="text-sm font-medium text-gray-700 mb-3">Status Publikasi</p>
                    <div class="flex flex-col sm:flex-row gap-3">
                        @foreach([
                            'draft'     => ['label' => 'Draft',     'desc' => 'Simpan dulu, belum publik'],
                            'published' => ['label' => 'Published',  'desc' => 'Tampil ke publik'],
                            'cancelled' => ['label' => 'Cancelled',  'desc' => 'Batalkan event ini'],
                        ] as $val => $opt)
                            <label class="flex-1 flex items-start gap-3 p-3 bg-white border-2 rounded-xl cursor-pointer transition-colors
                                          {{ old('status', $event->status) === $val ? 'border-indigo-500' : 'border-gray-200 hover:border-gray-300' }}">
                                <input type="radio" name="status" value="{{ $val }}"
                                       {{ old('status', $event->status) === $val ? 'checked' : '' }}
                                       class="mt-0.5 text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $opt['label'] }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $opt['desc'] }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex items-center justify-between pt-2">
                    <a href="{{ route('organizer.events.show', $event) }}"
                       class="text-sm text-gray-500 hover:text-gray-700 transition-colors">← Kembali</a>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('organizer.events.index') }}"
                           class="px-4 py-2.5 text-sm font-medium text-gray-600 border border-gray-300 rounded-xl hover:bg-gray-50">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-5 py-2.5 text-sm font-semibold bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
    function previewBanner(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('image-preview').classList.remove('hidden');
                document.getElementById('upload-zone').classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    </script>
</x-organizer-layout>
