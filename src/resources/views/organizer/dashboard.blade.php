<x-organizer-layout>
    <x-slot name="header">
        Dashboard Organizer
    </x-slot>

    {{-- Welcome Banner --}}
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold">Halo, {{ Auth::user()->name }}! 👋</h1>
                <p class="text-indigo-100 mt-1 text-sm">Kelola event-mu dari sini. Buat, publish, dan pantau perkembangan eventmu.</p>
            </div>
            <a href="{{ route('organizer.events.create') }}"
               class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2.5 bg-white text-indigo-700 font-semibold text-sm rounded-xl hover:bg-indigo-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Event
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Event</p>
                <div class="w-9 h-9 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </div>

        <a href="{{ route('organizer.events.index', ['status' => 'published']) }}"
           class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:border-green-300 hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Published</p>
                <div class="w-9 h-9 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['published'] }}</p>
        </a>

        <a href="{{ route('organizer.events.index', ['status' => 'draft']) }}"
           class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:border-yellow-300 hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Draft</p>
                <div class="w-9 h-9 bg-yellow-100 rounded-lg flex items-center justify-center group-hover:bg-yellow-200 transition-colors">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['draft'] }}</p>
        </a>

        <a href="{{ route('organizer.events.index', ['status' => 'cancelled']) }}"
           class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:border-red-300 hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Cancelled</p>
                <div class="w-9 h-9 bg-red-100 rounded-lg flex items-center justify-center group-hover:bg-red-200 transition-colors">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['cancelled'] }}</p>
        </a>
    </div>

    {{-- Recent Events --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-900">Event Terbaru</h2>
            <a href="{{ route('organizer.events.index') }}"
               class="text-xs font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
                Lihat semua →
            </a>
        </div>

        @if($recentEvents->isEmpty())
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="text-sm text-gray-500 mb-4">Belum ada event. Yuk buat event pertamamu!</p>
                <a href="{{ route('organizer.events.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Event Sekarang
                </a>
            </div>
        @else
            <div class="divide-y divide-gray-50">
                @foreach($recentEvents as $event)
                    <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $event->title }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $event->event_date->format('d M Y, H:i') }} · {{ $event->location }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 flex-shrink-0 ml-4">
                            @php
                                $badgeClass = match($event->status) {
                                    'published' => 'bg-green-100 text-green-700',
                                    'draft'     => 'bg-yellow-100 text-yellow-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                    default     => 'bg-gray-100 text-gray-600',
                                };
                                $badgeLabel = match($event->status) {
                                    'published' => 'Published',
                                    'draft'     => 'Draft',
                                    'cancelled' => 'Cancelled',
                                    default     => $event->status,
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                {{ $badgeLabel }}
                            </span>
                            <a href="{{ route('organizer.events.show', $event) }}"
                               class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">Detail</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-organizer-layout>
