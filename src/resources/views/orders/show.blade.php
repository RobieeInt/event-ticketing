<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $order->order_code }} — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen">

    {{-- NAVBAR --}}
    <nav class="bg-white/90 backdrop-blur border-b border-gray-100 sticky top-0 z-20">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 flex items-center justify-between h-16">
            <a href="{{ route('orders.index') }}"
               class="flex items-center gap-1.5 text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Tiket Saya
            </a>
            <a href="{{ route('dashboard') }}"
               class="px-3 py-1.5 text-xs font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors">
                Dashboard
            </a>
        </div>
    </nav>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-8 space-y-5">

        {{-- Flash --}}
        @if(session('status'))
            <div class="bg-green-50 border border-green-200 rounded-2xl px-4 py-3 text-sm text-green-700 font-medium">
                {{ session('status') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-2xl px-4 py-3 text-sm text-red-700 font-medium">
                {{ session('error') }}
            </div>
        @endif

        {{-- Status Banner --}}
        @if($order->status === 'paid')
            <div class="relative bg-gradient-to-r from-green-500 to-emerald-600 rounded-3xl p-6 text-white overflow-hidden">
                <div class="absolute right-6 top-1/2 -translate-y-1/2 opacity-10">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="relative">
                    <p class="text-green-100 text-sm font-semibold mb-1">Tiket Aktif ✓</p>
                    <p class="text-2xl font-black">Pembayaran Lunas</p>
                    <p class="text-green-100 text-sm mt-1">Tunjukkan QR code di bawah kepada panitia event</p>
                </div>
            </div>

        @elseif($order->status === 'pending')
            <div class="relative bg-gradient-to-r from-yellow-400 to-orange-500 rounded-3xl p-6 text-white overflow-hidden">
                <div class="absolute right-6 top-1/2 -translate-y-1/2 opacity-10">
                    <svg class="w-32 h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="relative">
                    <p class="text-yellow-100 text-sm font-semibold mb-1">Menunggu Pembayaran</p>
                    <p class="text-2xl font-black">Segera Bayar</p>
                    <p class="text-yellow-100 text-sm mt-1">Selesaikan pembayaran untuk mengaktifkan tiket</p>
                </div>
            </div>

        @else
            <div class="bg-gray-200 rounded-3xl p-6">
                <p class="text-sm font-semibold text-gray-600 mb-1">Pesanan Dibatalkan</p>
                <p class="text-2xl font-black text-gray-700">Order Cancelled</p>
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

            {{-- ===== LEFT: Detail Pesanan ===== --}}
            <div class="space-y-4">

                {{-- Event info --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    @if($order->event->banner_image)
                        <div class="h-36 overflow-hidden">
                            <img src="{{ Storage::url($order->event->banner_image) }}"
                                 class="w-full h-full object-cover" alt="{{ $order->event->title }}">
                        </div>
                    @else
                        @php
                            $g = match($order->event->category ?? '') {
                                'music'      => 'from-purple-600 to-indigo-700',
                                'sports'     => 'from-green-600 to-emerald-700',
                                'seminar'    => 'from-blue-600 to-cyan-700',
                                'exhibition' => 'from-orange-500 to-amber-600',
                                'conference' => 'from-slate-600 to-gray-700',
                                default      => 'from-indigo-600 to-purple-700',
                            };
                        @endphp
                        <div class="h-24 bg-gradient-to-br {{ $g }} flex items-center justify-center">
                            <span class="text-5xl font-black text-white/20">{{ strtoupper(substr($order->event->title, 0, 1)) }}</span>
                        </div>
                    @endif

                    <div class="p-4">
                        <p class="text-xs text-indigo-600 font-semibold mb-1">
                            {{ \App\Models\Event::categories()[$order->event->category] ?? $order->event->category }}
                        </p>
                        <h2 class="font-bold text-gray-900 leading-snug mb-3">{{ $order->event->title }}</h2>
                        <div class="space-y-2 text-xs text-gray-500">
                            <div class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ $order->event->event_date->translatedFormat('l, d F Y') }} · {{ $order->event->event_date->format('H:i') }} WIB</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                <span>{{ $order->event->location }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span>{{ $order->event->organizer->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Order detail --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                    <div class="px-4 py-3.5 border-b border-gray-50">
                        <h3 class="text-sm font-bold text-gray-900">Rincian Pesanan</h3>
                    </div>
                    <div class="p-4 space-y-2.5 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Kode Order</span>
                            <span class="font-mono font-semibold text-indigo-600 text-xs">{{ $order->order_code }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Jumlah Tiket</span>
                            <span class="font-semibold text-gray-900">{{ $order->quantity }} tiket</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Harga Satuan</span>
                            <span class="font-semibold text-gray-900">
                                @if($order->event->isFree()) Gratis
                                @else Rp {{ number_format($order->event->ticket_price, 0, ',', '.') }}
                                @endif
                            </span>
                        </div>
                        <div class="border-t border-gray-50 pt-2.5 flex justify-between">
                            <span class="font-bold text-gray-900">Total</span>
                            <span class="font-black text-gray-900 text-base">
                                @if($order->event->isFree()) Gratis
                                @else Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between pt-0.5">
                            <span class="text-gray-500">Tanggal Pesan</span>
                            <span class="text-gray-600 text-xs">{{ $order->created_at->translatedFormat('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== RIGHT: QR Ticket + Actions ===== --}}
            <div class="space-y-4">

                @if($order->status === 'paid')
                    {{-- E-Ticket QR --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 text-center">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">E-Ticket</p>

                        {{-- Decorative ticket cutout --}}
                        <div class="relative mx-auto bg-indigo-600 rounded-2xl p-5 w-fit">
                            <div class="bg-white rounded-xl p-2.5">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data={{ urlencode($order->order_code) }}&color=4F46E5&bgcolor=FFFFFF"
                                     alt="QR Tiket"
                                     class="w-40 h-40 rounded-lg"
                                     loading="lazy">
                            </div>
                        </div>

                        <div class="mt-4 space-y-1">
                            <p class="font-mono font-bold text-gray-900 tracking-widest text-sm">{{ $order->order_code }}</p>
                            <p class="text-xs text-gray-400">{{ $order->quantity }} tiket · {{ auth()->user()->name }}</p>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-50">
                            <p class="text-xs text-gray-400">Tunjukkan QR ini kepada panitia</p>
                        </div>
                    </div>

                    {{-- Event link --}}
                    <a href="{{ route('events.show', $order->event) }}"
                       class="block bg-white rounded-2xl border border-gray-100 shadow-sm px-4 py-3.5 text-center text-sm font-semibold text-indigo-600 hover:bg-indigo-50 transition-colors">
                        Lihat Halaman Event
                    </a>

                @elseif($order->status === 'pending')
                    {{-- Midtrans Snap Payment --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Pembayaran</p>

                        <div class="bg-gray-50 rounded-xl p-4 mb-5 text-center">
                            <p class="text-xs text-gray-400 mb-1">Total yang harus dibayar</p>
                            <p class="text-3xl font-black text-gray-900">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </p>
                            <p class="text-xs font-mono text-indigo-500 mt-1">{{ $order->order_code }}</p>
                        </div>

                        <button id="pay-btn" onclick="payWithSnap()"
                                class="w-full py-3.5 bg-indigo-600 text-white font-bold text-sm rounded-xl hover:bg-indigo-700 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            Bayar Sekarang
                        </button>

                        <p class="text-xs text-gray-400 text-center mt-2">
                            Transfer Bank · QRIS · Kartu Kredit · dan lainnya
                        </p>
                    </div>

                    {{-- Cancel --}}
                    <form action="{{ route('orders.cancel', $order) }}" method="POST"
                          onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                        @csrf
                        <button type="submit"
                                class="w-full py-3 border border-red-200 text-red-500 text-sm font-semibold rounded-xl hover:bg-red-50 transition-colors">
                            Batalkan Pesanan
                        </button>
                    </form>

                    {{-- Midtrans Snap JS --}}
                    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
                            data-client-key="{{ config('midtrans.client_key') }}"></script>
                    <script>
                    async function payWithSnap() {
                        const btn = document.getElementById('pay-btn');
                        btn.disabled = true;
                        btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Memproses...';

                        try {
                            const res = await fetch('{{ route('orders.checkout', $order) }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                },
                            });

                            const data = await res.json();

                            if (!data.snap_token) {
                                throw new Error(data.error || 'Gagal mendapatkan token pembayaran.');
                            }

                            window.snap.pay(data.snap_token, {
                                onSuccess: async function(result) {
                                    await fetch('{{ route('orders.verify', $order) }}', {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Accept': 'application/json',
                                        },
                                    });
                                    window.location.reload();
                                },
                                onPending: async function(result) {
                                    await fetch('{{ route('orders.verify', $order) }}', {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Accept': 'application/json',
                                        },
                                    });
                                    window.location.reload();
                                },
                                onError: function(result) {
                                    alert('Pembayaran gagal. Silakan coba lagi.');
                                    btn.disabled = false;
                                    btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg> Bayar Sekarang';
                                },
                                onClose: function() {
                                    btn.disabled = false;
                                    btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg> Bayar Sekarang';
                                },
                            });
                        } catch(e) {
                            alert(e.message || 'Terjadi kesalahan. Coba lagi.');
                            btn.disabled = false;
                            btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg> Bayar Sekarang';
                        }
                    }
                    </script>

                @else
                    {{-- Cancelled --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-600 mb-1">Pesanan Dibatalkan</p>
                        <p class="text-xs text-gray-400 mb-4">Tiket ini sudah tidak berlaku</p>
                        <a href="{{ route('events.show', $order->event) }}"
                           class="block w-full py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors text-center">
                            Pesan Lagi
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <footer class="border-t border-gray-100 mt-12 py-8 text-center">
        <p class="text-xs text-gray-400">{{ config('app.name') }} · Platform Event Ticketing · {{ date('Y') }}</p>
    </footer>

</body>
</html>
