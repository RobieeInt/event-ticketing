<x-organizer-layout>
    <x-slot name="header">
        Event Saya
    </x-slot>

    {{-- Flash Message --}}
    @if(session('status'))
        <div class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('status') }}
        </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-6 py-4 border-b border-gray-100">
            <h1 class="text-base font-semibold text-gray-900">Daftar Event</h1>
            <a href="{{ route('organizer.events.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Event
            </a>
        </div>

        {{-- Filter Tabs --}}
        <div class="flex gap-1 px-6 pt-4 pb-0 overflow-x-auto">
            @foreach(['all' => 'Semua', 'published' => 'Published', 'draft' => 'Draft', 'cancelled' => 'Cancelled'] as $key => $label)
                <a href="{{ route('organizer.events.index', ['status' => $key]) }}"
                   class="px-4 py-2 text-sm font-medium rounded-t-lg border-b-2 transition-colors whitespace-nowrap
                          {{ $status === $key
                              ? 'border-indigo-600 text-indigo-700 bg-indigo-50'
                              : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="border-b border-gray-100"></div>

        {{-- Table (Desktop) --}}
        <div class="hidden md:block overflow-x-auto">
            @if($events->isEmpty())
                <div class="text-center py-16">
                    <div class="w-16 h-16 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-500">Belum ada event dengan status ini.</p>
                    <a href="{{ route('organizer.events.create') }}"
                       class="mt-3 inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition-colors">
                        Buat Event Baru
                    </a>
                </div>
            @else
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-3">Event</th>
                            <th class="px-6 py-3">Tanggal</th>
                            <th class="px-6 py-3">Harga</th>
                            <th class="px-6 py-3">Kapasitas</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($events as $event)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <span class="text-sm font-bold text-indigo-700">{{ strtoupper(substr($event->title, 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 max-w-xs truncate">{{ $event->title }}</p>
                                            <p class="text-xs text-gray-400">{{ \App\Models\Event::categories()[$event->category] ?? $event->category }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $event->event_date->format('d M Y') }}<br>
                                    <span class="text-xs text-gray-400">{{ $event->event_date->format('H:i') }} WIB</span>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    @if($event->isFree())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700">Gratis</span>
                                    @else
                                        Rp {{ number_format($event->ticket_price, 0, ',', '.') }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ number_format($event->ticket_capacity, 0, ',', '.') }} tiket
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $badgeClass = match($event->status) {
                                            'published' => 'bg-green-100 text-green-700',
                                            'draft'     => 'bg-yellow-100 text-yellow-700',
                                            'cancelled' => 'bg-red-100 text-red-700',
                                            default     => 'bg-gray-100 text-gray-600',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                        <span class="w-1.5 h-1.5 rounded-full bg-current opacity-60"></span>
                                        {{ ucfirst($event->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($event->status === 'published')
                                        <a href="{{ route('organizer.events.checkin', $event) }}"
                                           class="px-3 py-1.5 text-xs font-medium text-emerald-600 border border-emerald-200 rounded-lg hover:bg-emerald-50 transition-colors flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                            </svg>
                                            Check-in
                                        </a>
                                        @endif
                                        <a href="{{ route('organizer.events.show', $event) }}"
                                           class="px-3 py-1.5 text-xs font-medium text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                            Detail
                                        </a>
                                        <a href="{{ route('organizer.events.edit', $event) }}"
                                           class="px-3 py-1.5 text-xs font-medium text-indigo-600 border border-indigo-200 rounded-lg hover:bg-indigo-50 transition-colors">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('organizer.events.destroy', $event) }}"
                                              onsubmit="return confirm('Hapus event ini? Tindakan tidak bisa dibatalkan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-3 py-1.5 text-xs font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition-colors">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- Cards (Mobile) --}}
        <div class="md:hidden divide-y divide-gray-100">
            @forelse($events as $event)
                <div class="p-4 space-y-3">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-900 text-sm truncate">{{ $event->title }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ \App\Models\Event::categories()[$event->category] ?? $event->category }}</p>
                        </div>
                        @php
                            $badgeClass = match($event->status) {
                                'published' => 'bg-green-100 text-green-700',
                                'draft'     => 'bg-yellow-100 text-yellow-700',
                                'cancelled' => 'bg-red-100 text-red-700',
                                default     => 'bg-gray-100 text-gray-600',
                            };
                        @endphp
                        <span class="flex-shrink-0 inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badgeClass }}">
                            {{ ucfirst($event->status) }}
                        </span>
                    </div>
                    <div class="flex items-center gap-4 text-xs text-gray-500">
                        <span>📅 {{ $event->event_date->format('d M Y') }}</span>
                        <span>📍 {{ Str::limit($event->location, 30) }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($event->status === 'published')
                        <a href="{{ route('organizer.events.checkin', $event) }}"
                           class="flex-1 text-center px-3 py-2 text-xs font-medium text-emerald-600 border border-emerald-200 rounded-lg hover:bg-emerald-50">
                            Check-in
                        </a>
                        @endif
                        <a href="{{ route('organizer.events.show', $event) }}"
                           class="flex-1 text-center px-3 py-2 text-xs font-medium text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50">
                            Detail
                        </a>
                        <a href="{{ route('organizer.events.edit', $event) }}"
                           class="flex-1 text-center px-3 py-2 text-xs font-medium text-indigo-600 border border-indigo-200 rounded-lg hover:bg-indigo-50">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('organizer.events.destroy', $event) }}"
                              onsubmit="return confirm('Hapus event ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="px-3 py-2 text-xs font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <p class="text-sm text-gray-500">Belum ada event dengan status ini.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($events->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $events->appends(['status' => $status])->links() }}
            </div>
        @endif
    </div>
</x-organizer-layout>
