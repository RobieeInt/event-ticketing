<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tiket Saya — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen">

    {{-- NAVBAR --}}
    <nav class="bg-white/90 backdrop-blur border-b border-gray-100 sticky top-0 z-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 flex items-center justify-between h-16">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 font-bold text-gray-900">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                              d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
                {{ config('app.name') }}
            </a>
            <div class="flex items-center gap-2">
                <a href="{{ route('dashboard') }}"
                   class="px-3 py-1.5 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-xl transition-colors">
                    Dashboard
                </a>
                <a href="{{ route('events.index') }}"
                   class="px-3 py-1.5 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-xl hover:bg-indigo-100 transition-colors">
                    Jelajahi Event
                </a>
            </div>
        </div>
    </nav>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-black text-gray-900">Tiket Saya</h1>
            <p class="text-sm text-gray-500 mt-1">Semua pesanan dan tiket event kamu</p>
        </div>

        @if(session('status'))
            <div class="bg-green-50 border border-green-200 rounded-2xl px-4 py-3 text-sm text-green-700 font-medium mb-5">
                {{ session('status') }}
            </div>
        @endif

        @if($orders->isEmpty())
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm text-center py-20">
                <div class="w-20 h-20 bg-indigo-50 rounded-3xl flex items-center justify-center mx-auto mb-5">
                    <svg class="w-10 h-10 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Belum Ada Tiket</h3>
                <p class="text-gray-500 text-sm mb-6">Temukan event seru dan dapatkan tiket pertamamu!</p>
                <a href="{{ route('events.index') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white font-semibold text-sm rounded-xl hover:bg-indigo-700 transition-colors">
                    Jelajahi Event
                </a>
            </div>
        @else
            <div class="space-y-3">
                @foreach($orders as $order)
                    <a href="{{ route('orders.show', $order) }}"
                       class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-4 p-4 overflow-hidden">

                        {{-- Event thumbnail --}}
                        <div class="w-20 h-20 rounded-xl overflow-hidden flex-shrink-0">
                            @if($order->event->banner_image)
                                <img src="{{ Storage::url($order->event->banner_image) }}"
                                     class="w-full h-full object-cover" alt="{{ $order->event->title }}">
                            @else
                                @php
                                    $g = match($order->event->category ?? '') {
                                        'music'      => 'from-purple-500 to-indigo-600',
                                        'sports'     => 'from-green-500 to-emerald-600',
                                        'seminar'    => 'from-blue-500 to-cyan-600',
                                        'exhibition' => 'from-orange-500 to-amber-600',
                                        'conference' => 'from-slate-500 to-gray-600',
                                        default      => 'from-indigo-500 to-purple-600',
                                    };
                                @endphp
                                <div class="w-full h-full bg-gradient-to-br {{ $g }} flex items-center justify-center">
                                    <span class="text-white text-2xl font-black">{{ strtoupper(substr($order->event->title, 0, 1)) }}</span>
                                </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-gray-900 truncate group-hover:text-indigo-700 transition-colors">
                                {{ $order->event->title }}
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ $order->event->event_date->translatedFormat('d M Y') }} · {{ $order->event->event_date->format('H:i') }} WIB
                            </p>
                            <p class="text-xs text-gray-400">{{ $order->event->location }}</p>
                            <p class="text-xs font-mono text-indigo-500 mt-1">{{ $order->order_code }}</p>
                        </div>

                        {{-- Status + price --}}
                        <div class="text-right flex-shrink-0 space-y-1.5">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold
                                {{ $order->status === 'paid'      ? 'bg-green-100 text-green-700' :
                                  ($order->status === 'pending'   ? 'bg-yellow-100 text-yellow-700'
                                                                  : 'bg-red-100 text-red-700') }}">
                                {{ $order->status === 'paid' ? '✓ Lunas' : ($order->status === 'pending' ? '⏳ Pending' : '✕ Batal') }}
                            </span>
                            <p class="text-sm font-black text-gray-900">
                                @if($order->event->isFree()) Gratis
                                @else Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                @endif
                            </p>
                            <p class="text-xs text-gray-400">{{ $order->quantity }} tiket</p>
                        </div>

                        <svg class="w-4 h-4 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($orders->hasPages())
                <div class="mt-6 flex justify-center">
                    {{ $orders->links() }}
                </div>
            @endif
        @endif
    </div>

    <footer class="border-t border-gray-100 mt-12 py-8 text-center">
        <p class="text-xs text-gray-400">{{ config('app.name') }} · Platform Event Ticketing · {{ date('Y') }}</p>
    </footer>

</body>
</html>
