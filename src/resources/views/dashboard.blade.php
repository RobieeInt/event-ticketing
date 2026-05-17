<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen">

    {{-- ======= NAVBAR ======= --}}
    <nav class="bg-white/90 backdrop-blur border-b border-gray-100 sticky top-0 z-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 flex items-center justify-between h-16">
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
                <a href="{{ route('events.index') }}"
                   class="hidden sm:block px-3 py-1.5 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-xl transition-colors">
                    Jelajahi Event
                </a>
                <a href="{{ route('orders.index') }}"
                   class="px-3 py-1.5 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-xl transition-colors">
                    Tiket Saya
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="px-3 py-1.5 text-sm font-medium text-red-500 hover:bg-red-50 rounded-xl transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8 space-y-6">

        {{-- Flash --}}
        @if(session('status'))
            <div class="bg-green-50 border border-green-200 rounded-2xl px-4 py-3 text-sm text-green-700 font-medium">
                {{ session('status') }}
            </div>
        @endif

        {{-- ===== Welcome Banner ===== --}}
        <div class="relative bg-gray-900 rounded-3xl overflow-hidden px-6 py-8">
            <div class="absolute inset-0 opacity-20"
                 style="background-image: radial-gradient(circle at 20% 50%, #6366f1 0%, transparent 50%), radial-gradient(circle at 80% 20%, #a855f7 0%, transparent 50%)"></div>
            <div class="relative flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <p class="text-indigo-300 text-sm font-semibold mb-1">Selamat datang kembali 👋</p>
                    <h1 class="text-2xl font-black text-white">{{ $user->name }}</h1>
                    <p class="text-gray-400 text-sm mt-0.5">{{ $user->email }}</p>
                </div>
                <div class="flex gap-3">
                    <div class="bg-white/10 rounded-2xl px-4 py-3 text-center">
                        <p class="text-2xl font-black text-white">{{ $totalTickets }}</p>
                        <p class="text-xs text-gray-400">Tiket Aktif</p>
                    </div>
                    <div class="bg-white/10 rounded-2xl px-4 py-3 text-center">
                        <p class="text-2xl font-black text-white">Rp {{ number_format($totalSpent, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-400">Total Belanja</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ===== LEFT: Organizer Status ===== --}}
            <div class="space-y-4">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-50">
                        <h2 class="text-sm font-bold text-gray-900">Status Organizer</h2>
                    </div>
                    <div class="p-5">
                        @if(!$application)
                            <div class="text-center py-2">
                                <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-500 mb-4">Ingin mengelola event sendiri? Daftar jadi organizer!</p>
                                <a href="{{ route('organizer.apply') }}"
                                   class="block w-full py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors text-center">
                                    Apply Jadi Organizer
                                </a>
                            </div>

                        @elseif($application->status === 'pending')
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 bg-yellow-50 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-yellow-700">Menunggu Review</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Pengajuan sedang diproses oleh admin.</p>
                                </div>
                            </div>

                        @elseif($application->status === 'rejected')
                            <div class="flex items-start gap-3 mb-4">
                                <div class="w-9 h-9 bg-red-50 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-red-700">Pengajuan Ditolak</p>
                                    @if($application->review_note)
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $application->review_note }}</p>
                                    @endif
                                </div>
                            </div>
                            <a href="{{ route('organizer.apply') }}"
                               class="block w-full py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-xl hover:bg-gray-800 transition-colors text-center">
                                Ajukan Ulang
                            </a>

                        @elseif($application->status === 'approved')
                            <div class="flex items-start gap-3 mb-4">
                                <div class="w-9 h-9 bg-green-50 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-green-700">Aktif sebagai Organizer 🎉</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Kamu bisa mulai buat dan kelola event.</p>
                                </div>
                            </div>
                            <a href="{{ route('organizer.dashboard') }}"
                               class="block w-full py-2.5 bg-green-600 text-white text-sm font-semibold rounded-xl hover:bg-green-700 transition-colors text-center">
                                Masuk Panel Organizer
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Quick links --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 space-y-1">
                    <a href="{{ route('events.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                        <span class="text-sm text-gray-700 font-medium">Jelajahi Event</span>
                        <svg class="w-4 h-4 text-gray-300 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    <a href="{{ route('orders.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                        <span class="text-sm text-gray-700 font-medium">Tiket Saya</span>
                        <svg class="w-4 h-4 text-gray-300 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="text-sm text-gray-700 font-medium">Edit Profil</span>
                        <svg class="w-4 h-4 text-gray-300 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- ===== RIGHT: Recent Orders ===== --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
                        <h2 class="text-sm font-bold text-gray-900">Pesanan Terbaru</h2>
                        <a href="{{ route('orders.index') }}" class="text-xs text-indigo-600 hover:underline font-medium">
                            Lihat Semua
                        </a>
                    </div>

                    @if($recentOrders->isEmpty())
                        <div class="text-center py-14">
                            <div class="w-16 h-16 bg-indigo-50 rounded-3xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-gray-700 mb-1">Belum ada tiket</p>
                            <p class="text-xs text-gray-400 mb-5">Yuk jelajahi event dan dapatkan tiketmu!</p>
                            <a href="{{ route('events.index') }}"
                               class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors">
                                Jelajahi Event
                            </a>
                        </div>
                    @else
                        <div class="divide-y divide-gray-50">
                            @foreach($recentOrders as $order)
                                <a href="{{ route('orders.show', $order) }}"
                                   class="flex items-center gap-4 px-5 py-4 hover:bg-gray-50 transition-colors">
                                    {{-- Event image / gradient --}}
                                    <div class="w-14 h-14 rounded-xl overflow-hidden flex-shrink-0">
                                        @if($order->event->banner_image)
                                            <img src="{{ Storage::url($order->event->banner_image) }}"
                                                 class="w-full h-full object-cover" alt="">
                                        @else
                                            @php
                                                $g = match($order->event->category ?? '') {
                                                    'music' => 'from-purple-500 to-indigo-600',
                                                    'sports' => 'from-green-500 to-emerald-600',
                                                    'seminar' => 'from-blue-500 to-cyan-600',
                                                    default => 'from-indigo-500 to-purple-600',
                                                };
                                            @endphp
                                            <div class="w-full h-full bg-gradient-to-br {{ $g }} flex items-center justify-center">
                                                <span class="text-white text-xl font-black">{{ strtoupper(substr($order->event->title, 0, 1)) }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $order->event->title }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $order->quantity }} tiket · {{ $order->order_code }}</p>
                                        <p class="text-xs text-gray-400">{{ $order->event->event_date->translatedFormat('d M Y') }}</p>
                                    </div>

                                    <div class="text-right flex-shrink-0">
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold
                                            {{ $order->status === 'paid' ? 'bg-green-100 text-green-700' :
                                              ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-700'
                                                                           : 'bg-red-100 text-red-700') }}">
                                            {{ $order->status === 'paid' ? 'Lunas' : ($order->status === 'pending' ? 'Pending' : 'Batal') }}
                                        </span>
                                        <p class="text-xs font-semibold text-gray-700 mt-1">
                                            @if($order->event->isFree()) Gratis
                                            @else Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                            @endif
                                        </p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <footer class="border-t border-gray-100 mt-12 py-8 text-center">
        <p class="text-xs text-gray-400">{{ config('app.name') }} · Platform Event Ticketing · {{ date('Y') }}</p>
    </footer>

</body>
</html>
