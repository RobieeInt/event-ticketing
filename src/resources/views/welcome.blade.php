<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — Platform Tiket Event Indonesia</title>
    <meta name="description" content="Temukan dan beli tiket event terbaik di Indonesia. Konser, seminar, olahraga, pameran — semua ada di sini.">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white text-gray-900 overflow-x-hidden">

    {{-- ============================================================
         NAVBAR
    ============================================================ --}}
    <nav class="fixed top-0 inset-x-0 z-50 bg-white/80 backdrop-blur-xl border-b border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center gap-2.5">
                <div class="w-9 h-9 bg-indigo-600 rounded-xl flex items-center justify-center shadow-md shadow-indigo-200">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                              d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
                <span class="font-black text-gray-900 text-lg tracking-tight">{{ config('app.name') }}</span>
            </a>

            {{-- Nav links --}}
            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('events.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">Semua Event</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">Dashboard</a>
                @endauth
            </div>

            {{-- CTA --}}
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="px-4 py-2 text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">
                        Dashboard
                    </a>
                    <a href="{{ route('events.index') }}"
                       class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors shadow-sm">
                        Jelajahi Event
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 text-sm font-semibold text-gray-700 hover:text-gray-900 transition-colors">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                       class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors shadow-sm">
                        Daftar Gratis
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- ============================================================
         HERO
    ============================================================ --}}
    <section class="relative min-h-screen flex items-center pt-16 overflow-hidden bg-white">

        {{-- Background blobs --}}
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-40 -right-40 w-[700px] h-[700px] bg-indigo-50 rounded-full opacity-60 blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-[600px] h-[600px] bg-purple-50 rounded-full opacity-60 blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] bg-indigo-100 rounded-full opacity-30 blur-3xl"></div>
        </div>

        {{-- Grid pattern --}}
        <div class="absolute inset-0 opacity-[0.03]"
             style="background-image: url(\"data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%234F46E5' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E\")">
        </div>

        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 py-20 lg:py-32">
            <div class="max-w-3xl">

                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-indigo-50 border border-indigo-100 rounded-full text-xs font-semibold text-indigo-600 mb-6">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                    Platform Tiket Event #1 Indonesia
                </div>

                {{-- Headline --}}
                <h1 class="text-5xl sm:text-6xl lg:text-7xl font-black text-gray-900 leading-[1.05] tracking-tight mb-6">
                    Tiket Event
                    <span class="relative">
                        <span class="relative z-10 text-indigo-600">Favoritmu,</span>
                        <span class="absolute -bottom-1 left-0 right-0 h-3 bg-indigo-100 rounded-full -z-0"></span>
                    </span>
                    <br>Ada di Sini.
                </h1>

                {{-- Subheadline --}}
                <p class="text-lg sm:text-xl text-gray-500 leading-relaxed mb-10 max-w-xl">
                    Temukan ribuan event seru — konser, seminar, olahraga, pameran, dan lainnya.
                    Beli tiket dalam hitungan detik, dapat e-ticket langsung.
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    <a href="{{ route('events.index') }}"
                       class="group inline-flex items-center gap-2.5 px-7 py-4 bg-indigo-600 text-white font-bold text-base rounded-2xl hover:bg-indigo-700 transition-all duration-200 shadow-lg shadow-indigo-200 hover:shadow-xl hover:shadow-indigo-200 hover:-translate-y-0.5">
                        Jelajahi Event Sekarang
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                    @guest
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center gap-2 px-7 py-4 text-gray-700 font-semibold text-base hover:text-gray-900 transition-colors">
                            Daftar Gratis
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @endguest
                </div>

                {{-- Social proof --}}
                <div class="mt-12 flex items-center gap-6">
                    <div class="flex -space-x-3">
                        @foreach(['bg-indigo-400','bg-purple-400','bg-pink-400','bg-orange-400','bg-emerald-400'] as $c)
                            <div class="w-10 h-10 rounded-full {{ $c }} border-2 border-white flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                                </svg>
                            </div>
                        @endforeach
                    </div>
                    <div>
                        <div class="flex items-center gap-1 mb-0.5">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-3.5 h-3.5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            @endfor
                        </div>
                        <p class="text-xs text-gray-500 font-medium">Dipercaya ribuan pengguna</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Floating ticket card decoration --}}
        <div class="hidden lg:block absolute right-0 top-1/2 -translate-y-1/2 pr-6">
            <div class="relative">
                {{-- Main card --}}
                <div class="w-72 bg-white rounded-3xl shadow-2xl border border-gray-100 p-5 rotate-3 hover:rotate-0 transition-transform duration-500">
                    <div class="h-32 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl mb-4 flex items-center justify-center">
                        <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                        </svg>
                    </div>
                    <p class="font-black text-gray-900 text-sm mb-1">Summer Music Festival</p>
                    <p class="text-xs text-gray-400 mb-3">📅 25 Jun 2026 · 📍 Jakarta</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-lg">2 Tiket</span>
                        <span class="text-sm font-black text-gray-900">Rp 350.000</span>
                    </div>
                </div>

                {{-- QR badge --}}
                <div class="absolute -bottom-4 -left-8 bg-white rounded-2xl shadow-xl border border-gray-100 p-3 -rotate-6">
                    <div class="w-14 h-14 bg-indigo-600 rounded-xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                    </div>
                </div>

                {{-- Check badge --}}
                <div class="absolute -top-4 -right-4 bg-emerald-500 text-white rounded-2xl shadow-lg px-3 py-2 rotate-6">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-xs font-bold">Lunas</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         STATS
    ============================================================ --}}
    <section class="bg-indigo-600 py-14">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center text-white">
                @php
                    $stats = [
                        ['number' => '500+', 'label' => 'Event Tersedia'],
                        ['number' => '10K+', 'label' => 'Pengguna Aktif'],
                        ['number' => '50K+', 'label' => 'Tiket Terjual'],
                        ['number' => '99%',  'label' => 'Kepuasan Pelanggan'],
                    ];
                @endphp
                @foreach($stats as $stat)
                    <div>
                        <p class="text-4xl font-black mb-1">{{ $stat['number'] }}</p>
                        <p class="text-indigo-200 text-sm font-medium">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================================================
         FEATURED EVENTS
    ============================================================ --}}
    <section class="py-20 bg-gray-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">

            <div class="flex items-end justify-between mb-10">
                <div>
                    <p class="text-sm font-semibold text-indigo-600 mb-2">Event Pilihan</p>
                    <h2 class="text-3xl sm:text-4xl font-black text-gray-900">Event yang Sedang Trending</h2>
                </div>
                <a href="{{ route('events.index') }}"
                   class="hidden sm:inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">
                    Lihat Semua
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>

            @php
                $featuredEvents = \App\Models\Event::where('status', 'published')
                    ->latest()
                    ->take(6)
                    ->get();
            @endphp

            @if($featuredEvents->isEmpty())
                <div class="text-center py-20">
                    <div class="w-20 h-20 bg-indigo-100 rounded-3xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-gray-500">Belum ada event. <a href="{{ route('events.index') }}" class="text-indigo-600 font-semibold">Cek semua event</a></p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($featuredEvents as $event)
                        @php
                            $gradients = [
                                'music'      => 'from-purple-600 to-indigo-700',
                                'sports'     => 'from-green-500 to-emerald-700',
                                'seminar'    => 'from-blue-500 to-cyan-700',
                                'exhibition' => 'from-orange-500 to-amber-600',
                                'conference' => 'from-slate-600 to-gray-700',
                                'festival'   => 'from-pink-500 to-rose-600',
                                'default'    => 'from-indigo-500 to-purple-700',
                            ];
                            $grad = $gradients[$event->category] ?? $gradients['default'];
                        @endphp
                        <a href="{{ route('events.show', $event) }}"
                           class="group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl border border-gray-100 transition-all duration-300 hover:-translate-y-1 flex flex-col">

                            {{-- Banner --}}
                            @if($event->banner_image)
                                <div class="h-44 overflow-hidden">
                                    <img src="{{ Storage::url($event->banner_image) }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                         alt="{{ $event->title }}">
                                </div>
                            @else
                                <div class="h-44 bg-gradient-to-br {{ $grad }} flex items-center justify-center relative overflow-hidden">
                                    <span class="text-8xl font-black text-white/10 select-none">{{ strtoupper(substr($event->title, 0, 1)) }}</span>
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                                </div>
                            @endif

                            <div class="p-5 flex flex-col flex-1">
                                {{-- Category badge --}}
                                <span class="inline-flex self-start px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-600 mb-3">
                                    {{ \App\Models\Event::categories()[$event->category] ?? $event->category }}
                                </span>

                                <h3 class="font-black text-gray-900 text-base leading-snug mb-3 group-hover:text-indigo-600 transition-colors line-clamp-2">
                                    {{ $event->title }}
                                </h3>

                                <div class="space-y-1.5 text-xs text-gray-500 mb-4 flex-1">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $event->event_date->translatedFormat('d M Y') }} · {{ $event->event_date->format('H:i') }} WIB
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                        {{ Str::limit($event->location, 35) }}
                                    </div>
                                </div>

                                <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                                    @if($event->isFree())
                                        <span class="text-base font-black text-emerald-600">GRATIS</span>
                                    @else
                                        <div>
                                            <p class="text-xs text-gray-400">Mulai dari</p>
                                            <p class="text-base font-black text-gray-900">Rp {{ number_format($event->ticket_price, 0, ',', '.') }}</p>
                                        </div>
                                    @endif
                                    <span class="px-3 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded-xl group-hover:bg-indigo-700 transition-colors">
                                        Beli Tiket →
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="text-center mt-10 sm:hidden">
                    <a href="{{ route('events.index') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white font-bold text-sm rounded-2xl hover:bg-indigo-700 transition-colors">
                        Lihat Semua Event
                    </a>
                </div>
            @endif
        </div>
    </section>

    {{-- ============================================================
         HOW IT WORKS
    ============================================================ --}}
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-14">
                <p class="text-sm font-semibold text-indigo-600 mb-2">Mudah & Cepat</p>
                <h2 class="text-3xl sm:text-4xl font-black text-gray-900">Cara Beli Tiket</h2>
                <p class="text-gray-500 mt-3 max-w-md mx-auto">Proses pembelian tiket yang simpel, aman, dan langsung dapat e-ticket.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                @php
                    $steps = [
                        [
                            'num'   => '01',
                            'icon'  => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',
                            'title' => 'Cari Event',
                            'desc'  => 'Browse ratusan event di berbagai kategori. Filter berdasarkan tanggal, lokasi, atau harga.',
                            'color' => 'bg-blue-50 text-blue-600',
                        ],
                        [
                            'num'   => '02',
                            'icon'  => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z',
                            'title' => 'Pilih Tiket',
                            'desc'  => 'Pilih jumlah tiket yang diinginkan. Kapasitas terbatas, segera pesan!',
                            'color' => 'bg-indigo-50 text-indigo-600',
                        ],
                        [
                            'num'   => '03',
                            'icon'  => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
                            'title' => 'Bayar Mudah',
                            'desc'  => 'Transfer bank, QRIS, GoPay, OVO, atau kartu kredit. Semua tersedia via Midtrans.',
                            'color' => 'bg-purple-50 text-purple-600',
                        ],
                        [
                            'num'   => '04',
                            'icon'  => 'M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z',
                            'title' => 'Dapat E-Ticket',
                            'desc'  => 'QR Code e-ticket langsung tersedia. Tunjukkan ke panitia saat masuk venue.',
                            'color' => 'bg-emerald-50 text-emerald-600',
                        ],
                    ];
                @endphp

                @foreach($steps as $i => $step)
                    <div class="relative flex flex-col items-center text-center">
                        {{-- Connector line --}}
                        @if($i < count($steps) - 1)
                            <div class="hidden md:block absolute top-10 left-1/2 w-full h-0.5 bg-gradient-to-r from-gray-200 to-gray-100"></div>
                        @endif

                        {{-- Icon --}}
                        <div class="relative z-10 w-20 h-20 {{ $step['color'] }} rounded-2xl flex items-center justify-center mb-4 shadow-sm">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $step['icon'] }}"/>
                            </svg>
                            <span class="absolute -top-2 -right-2 w-6 h-6 bg-white border border-gray-100 rounded-full text-xs font-black text-gray-500 flex items-center justify-center shadow-sm">
                                {{ $i + 1 }}
                            </span>
                        </div>

                        <h3 class="font-black text-gray-900 mb-2">{{ $step['title'] }}</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================================================
         CATEGORIES
    ============================================================ --}}
    <section class="py-20 bg-gray-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-black text-gray-900">Semua Kategori Event</h2>
                <p class="text-gray-500 mt-3">Dari konser hingga seminar, kami punya semuanya.</p>
            </div>

            @php
                $categories = [
                    ['key' => 'music',      'label' => 'Musik & Konser',    'emoji' => '🎵', 'color' => 'bg-purple-100 hover:bg-purple-200 text-purple-700'],
                    ['key' => 'sports',     'label' => 'Olahraga',          'emoji' => '⚽', 'color' => 'bg-green-100 hover:bg-green-200 text-green-700'],
                    ['key' => 'seminar',    'label' => 'Seminar',           'emoji' => '🎤', 'color' => 'bg-blue-100 hover:bg-blue-200 text-blue-700'],
                    ['key' => 'conference', 'label' => 'Konferensi',        'emoji' => '💼', 'color' => 'bg-slate-100 hover:bg-slate-200 text-slate-700'],
                    ['key' => 'exhibition', 'label' => 'Pameran',           'emoji' => '🖼️', 'color' => 'bg-orange-100 hover:bg-orange-200 text-orange-700'],
                    ['key' => 'festival',   'label' => 'Festival',          'emoji' => '🎉', 'color' => 'bg-pink-100 hover:bg-pink-200 text-pink-700'],
                    ['key' => 'workshop',   'label' => 'Workshop',          'emoji' => '🛠️', 'color' => 'bg-yellow-100 hover:bg-yellow-200 text-yellow-700'],
                    ['key' => 'other',      'label' => 'Lainnya',           'emoji' => '✨', 'color' => 'bg-indigo-100 hover:bg-indigo-200 text-indigo-700'],
                ];
            @endphp

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach($categories as $cat)
                    <a href="{{ route('events.index', ['category' => $cat['key']]) }}"
                       class="group flex flex-col items-center justify-center p-5 {{ $cat['color'] }} rounded-2xl transition-all duration-200 hover:-translate-y-1 hover:shadow-md cursor-pointer">
                        <span class="text-3xl mb-2">{{ $cat['emoji'] }}</span>
                        <span class="text-sm font-bold text-center leading-tight">{{ $cat['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================================================
         CTA BANNER
    ============================================================ --}}
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="relative bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-700 rounded-3xl overflow-hidden p-10 sm:p-16 text-center text-white">

                {{-- Decorative circles --}}
                <div class="absolute -top-16 -right-16 w-64 h-64 bg-white/5 rounded-full"></div>
                <div class="absolute -bottom-16 -left-16 w-64 h-64 bg-white/5 rounded-full"></div>
                <div class="absolute top-8 left-8 w-32 h-32 bg-white/5 rounded-full"></div>

                <div class="relative">
                    <p class="text-indigo-200 text-sm font-semibold mb-3">Mau jadi Organizer?</p>
                    <h2 class="text-3xl sm:text-5xl font-black mb-4 leading-tight">
                        Buat & Jual Tiket<br>Event Kamu Sekarang
                    </h2>
                    <p class="text-indigo-200 text-base sm:text-lg max-w-xl mx-auto mb-10 leading-relaxed">
                        Daftar sebagai organizer, buat event dalam menit, dan mulai menjual tiket ke ribuan pengguna kami.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        @guest
                            <a href="{{ route('register') }}"
                               class="px-8 py-4 bg-white text-indigo-700 font-black text-base rounded-2xl hover:bg-indigo-50 transition-colors shadow-lg">
                                Daftar Sekarang — Gratis!
                            </a>
                        @else
                            <a href="{{ route('organizer.apply') }}"
                               class="px-8 py-4 bg-white text-indigo-700 font-black text-base rounded-2xl hover:bg-indigo-50 transition-colors shadow-lg">
                                Apply Jadi Organizer
                            </a>
                        @endguest
                        <a href="{{ route('events.index') }}"
                           class="px-8 py-4 border-2 border-white/30 text-white font-bold text-base rounded-2xl hover:bg-white/10 transition-colors">
                            Lihat Event Dulu
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         FOOTER
    ============================================================ --}}
    <footer class="bg-gray-900 text-gray-400 py-14">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 mb-10">

                {{-- Brand --}}
                <div class="lg:col-span-2">
                    <div class="flex items-center gap-2.5 mb-4">
                        <div class="w-9 h-9 bg-indigo-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                      d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                            </svg>
                        </div>
                        <span class="font-black text-white text-lg">{{ config('app.name') }}</span>
                    </div>
                    <p class="text-sm leading-relaxed max-w-xs">
                        Platform tiket event digital terpercaya di Indonesia. Beli, kelola, dan scan tiket dengan mudah.
                    </p>
                </div>

                {{-- Links --}}
                <div>
                    <h4 class="text-white font-bold text-sm mb-4">Platform</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('events.index') }}" class="hover:text-white transition-colors">Jelajahi Event</a></li>
                        @auth
                            <li><a href="{{ route('orders.index') }}" class="hover:text-white transition-colors">Tiket Saya</a></li>
                            <li><a href="{{ route('dashboard') }}" class="hover:text-white transition-colors">Dashboard</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Masuk</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Daftar</a></li>
                        @endauth
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-bold text-sm mb-4">Organizer</h4>
                    <ul class="space-y-2.5 text-sm">
                        @auth
                            <li><a href="{{ route('organizer.apply') }}" class="hover:text-white transition-colors">Jadi Organizer</a></li>
                        @else
                            <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Daftar & Jual Tiket</a></li>
                        @endauth
                        <li><span class="text-gray-600">Bantuan</span></li>
                        <li><span class="text-gray-600">Syarat & Ketentuan</span></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm">© {{ date('Y') }} {{ config('app.name') }}. Dibuat dengan ❤️ menggunakan Laravel & Tailwind CSS.</p>
                <div class="flex items-center gap-4 text-xs">
                    <span class="flex items-center gap-1.5 text-emerald-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        Semua sistem normal
                    </span>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
