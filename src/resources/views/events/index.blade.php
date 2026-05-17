<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jelajahi Event — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen">

    {{-- ======= NAVBAR ======= --}}
    <nav class="bg-white/90 backdrop-blur border-b border-gray-100 sticky top-0 z-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 flex items-center justify-between h-16">
            <a href="{{ route('events.index') }}" class="flex items-center gap-2.5 font-bold text-gray-900">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                              d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
                {{ config('app.name') }}
            </a>
            <div class="flex items-center gap-2">
                @auth
                    @php $role = Auth::user()->role; @endphp
                    <a href="{{ $role === 'organizer' ? route('organizer.dashboard') : ($role === 'admin' ? route('admin.dashboard') : route('dashboard')) }}"
                       class="px-4 py-2 text-sm font-medium text-indigo-600 hover:bg-indigo-50 rounded-xl transition-colors">
                        {{ $role === 'organizer' ? 'Panel Organizer' : ($role === 'admin' ? 'Panel Admin' : 'Dashboard') }}
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-xl transition-colors">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                       class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- ======= HERO ======= --}}
    <div class="relative bg-gray-900 overflow-hidden">
        {{-- Background pattern --}}
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 20% 50%, #6366f1 0%, transparent 50%), radial-gradient(circle at 80% 20%, #a855f7 0%, transparent 50%), radial-gradient(circle at 60% 80%, #3b82f6 0%, transparent 40%)"></div>

        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 py-16 text-center">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-indigo-300 text-xs font-semibold tracking-wider mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-pulse"></span>
                EVENT TICKETING
            </span>
            <h1 class="text-4xl sm:text-5xl font-black text-white mb-4 leading-tight">
                Temukan Event<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">yang Kamu Suka</span>
            </h1>
            <p class="text-gray-400 text-lg mb-8 max-w-lg mx-auto">
                Konser, seminar, olahraga, pameran — semua ada di sini.
            </p>

            {{-- Search --}}
            <form method="GET" action="{{ route('events.index') }}" class="max-w-xl mx-auto flex gap-2">
                <div class="flex-1 relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ $search }}"
                           placeholder="Cari event atau lokasi..."
                           class="w-full pl-11 pr-4 py-3.5 rounded-2xl bg-white text-gray-900 text-sm font-medium placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm">
                </div>
                @if($category)
                    <input type="hidden" name="category" value="{{ $category }}">
                @endif
                <button type="submit"
                        class="px-6 py-3.5 bg-indigo-600 text-white font-semibold text-sm rounded-2xl hover:bg-indigo-700 transition-colors shadow-sm whitespace-nowrap">
                    Cari
                </button>
            </form>
        </div>
    </div>

    {{-- ======= CONTENT ======= --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8">

        {{-- Category Filter --}}
        <div class="flex gap-2 flex-wrap mb-6 -mx-1">
            @php
                $activeClass   = 'bg-indigo-600 text-white shadow-sm';
                $inactiveClass = 'bg-white text-gray-600 border border-gray-200 hover:border-indigo-300 hover:text-indigo-700';
            @endphp
            <a href="{{ route('events.index', array_filter(['search' => $search])) }}"
               class="px-4 py-1.5 rounded-full text-sm font-medium transition-all {{ !$category ? $activeClass : $inactiveClass }}">
                🎯 Semua
            </a>
            @php $categoryIcons = ['music' => '🎵', 'sports' => '⚽', 'seminar' => '🎓', 'exhibition' => '🖼️', 'conference' => '💼', 'other' => '✨']; @endphp
            @foreach($categories as $key => $label)
                <a href="{{ route('events.index', array_filter(['category' => $key, 'search' => $search])) }}"
                   class="px-4 py-1.5 rounded-full text-sm font-medium transition-all {{ $category === $key ? $activeClass : $inactiveClass }}">
                    {{ $categoryIcons[$key] ?? '🎪' }} {{ $label }}
                </a>
            @endforeach
        </div>

        {{-- Result Info --}}
        @if($search || $category)
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm text-gray-500">
                    {{ $events->total() }} event ditemukan
                    @if($search) untuk "<strong class="text-gray-900">{{ $search }}</strong>"@endif
                </p>
                <a href="{{ route('events.index') }}" class="text-xs text-indigo-600 hover:underline">Reset filter</a>
            </div>
        @endif

        {{-- Events Grid --}}
        @if($events->isEmpty())
            <div class="text-center py-24">
                <div class="w-20 h-20 bg-indigo-50 rounded-3xl flex items-center justify-center mx-auto mb-5">
                    <span class="text-4xl">🔍</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Tidak Ada Event Ditemukan</h3>
                <p class="text-gray-500 text-sm mb-5">Coba cari dengan kata kunci yang berbeda atau hapus filter.</p>
                <a href="{{ route('events.index') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors">
                    Lihat Semua Event
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($events as $event)
                    <a href="{{ route('events.show', $event) }}"
                       class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col">

                        {{-- Event Image --}}
                        <div class="relative h-44 overflow-hidden flex-shrink-0">
                            @if($event->banner_image)
                                <img src="{{ Storage::url($event->banner_image) }}"
                                     alt="{{ $event->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                            @else
                                @php
                                    $grad = match($event->category) {
                                        'music'      => 'from-purple-600 via-indigo-600 to-blue-700',
                                        'sports'     => 'from-green-500 via-emerald-600 to-teal-700',
                                        'seminar'    => 'from-blue-500 via-cyan-600 to-sky-700',
                                        'exhibition' => 'from-orange-500 via-amber-500 to-yellow-600',
                                        'conference' => 'from-slate-600 via-gray-700 to-zinc-800',
                                        default      => 'from-indigo-500 via-purple-600 to-pink-600',
                                    };
                                @endphp
                                <div class="w-full h-full bg-gradient-to-br {{ $grad }} flex items-center justify-center">
                                    <span class="text-6xl font-black text-white/20 select-none">{{ strtoupper(substr($event->title, 0, 1)) }}</span>
                                </div>
                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                            @endif

                            {{-- Category Badge on image --}}
                            <div class="absolute top-3 left-3">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-white/90 backdrop-blur text-gray-800">
                                    {{ $categoryIcons[$event->category] ?? '🎪' }}
                                    {{ \App\Models\Event::categories()[$event->category] ?? $event->category }}
                                </span>
                            </div>

                            {{-- Price Badge on image --}}
                            <div class="absolute top-3 right-3">
                                @if($event->isFree())
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-green-500 text-white">GRATIS</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-black/60 backdrop-blur text-white">
                                        Rp {{ number_format($event->ticket_price, 0, ',', '.') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="p-4 flex flex-col flex-1">
                            <h3 class="font-bold text-gray-900 text-sm leading-snug mb-3 line-clamp-2 group-hover:text-indigo-700 transition-colors">
                                {{ $event->title }}
                            </h3>

                            <div class="space-y-1.5 text-xs text-gray-500 flex-1">
                                <div class="flex items-start gap-2">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0 mt-0.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>{{ $event->event_date->translatedFormat('d M Y') }}, {{ $event->event_date->format('H:i') }} WIB</span>
                                </div>
                                <div class="flex items-start gap-2">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0 mt-0.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    <span class="line-clamp-1">{{ $event->location }}</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-50">
                                <span class="text-xs text-gray-400">{{ $event->remainingCapacity() }} tiket tersisa</span>
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 group-hover:gap-2 transition-all">
                                    Lihat Detail
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($events->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $events->appends(array_filter(['category' => $category, 'search' => $search]))->links() }}
                </div>
            @endif
        @endif
    </div>

    <footer class="border-t border-gray-100 mt-16 py-10 text-center">
        <div class="flex items-center justify-center gap-2 mb-2">
            <div class="w-6 h-6 bg-indigo-600 rounded-md flex items-center justify-center">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
            </div>
            <span class="font-bold text-gray-700 text-sm">{{ config('app.name') }}</span>
        </div>
        <p class="text-xs text-gray-400">Platform Event Ticketing · {{ date('Y') }}</p>
    </footer>

</body>
</html>
