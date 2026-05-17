<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $event->title }} — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased overflow-hidden" style="height: 100vh;">

    {{-- ======= WRAPPER: dua panel sejajar, full tinggi layar ======= --}}
    <div class="flex" style="height: 100vh;">

        {{-- ===== LEFT: Poster — scroll sendiri kalau panjang ===== --}}
        <div class="hidden lg:block lg:w-[400px] xl:w-[460px] flex-shrink-0 bg-gray-950 overflow-y-auto">
            @if($event->banner_image)
                <img src="{{ Storage::url($event->banner_image) }}"
                     alt="{{ $event->title }}"
                     class="w-full h-auto block">
            @else
                @php
                    $grad = match($event->category) {
                        'music'      => 'from-purple-700 via-indigo-700 to-blue-900',
                        'sports'     => 'from-green-600 via-emerald-700 to-teal-900',
                        'seminar'    => 'from-blue-600 via-cyan-700 to-sky-900',
                        'exhibition' => 'from-orange-500 via-amber-600 to-yellow-800',
                        'conference' => 'from-slate-600 via-gray-700 to-zinc-900',
                        default      => 'from-indigo-600 via-purple-700 to-pink-900',
                    };
                @endphp
                <div class="h-full bg-gradient-to-br {{ $grad }} flex flex-col items-center justify-center gap-6 p-10">
                    <div class="w-28 h-28 rounded-3xl bg-white/10 flex items-center justify-center">
                        <span class="text-6xl">{{ ['music'=>'🎵','sports'=>'⚽','seminar'=>'🎓','exhibition'=>'🖼️','conference'=>'💼'][$event->category] ?? '🎪' }}</span>
                    </div>
                    <div class="text-center">
                        <h2 class="text-2xl font-black text-white leading-tight">{{ $event->title }}</h2>
                        <p class="text-white/50 text-sm mt-2">Belum ada poster event</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- ===== RIGHT: Detail — topbar fixed + konten scroll ===== --}}
        <div class="flex-1 flex flex-col overflow-hidden bg-gray-50">

            {{-- Topbar (tidak scroll, selalu kelihatan) --}}
            <div class="bg-white border-b border-gray-100 px-5 py-3 flex items-center justify-between flex-shrink-0">
                <a href="{{ route('events.index') }}"
                   class="flex items-center gap-1.5 text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Jelajahi Event
                </a>
                <div class="flex items-center gap-2">
                    @auth
                        @php $role = Auth::user()->role; @endphp
                        <span class="hidden sm:inline text-xs text-gray-400">{{ Auth::user()->name }}</span>
                        <a href="{{ $role === 'organizer' ? route('organizer.dashboard') : ($role === 'admin' ? route('admin.dashboard') : route('dashboard')) }}"
                           class="px-3 py-1.5 text-xs font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors">
                            {{ $role === 'organizer' ? 'Panel Organizer' : ($role === 'admin' ? 'Panel Admin' : 'Dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">Login</a>
                        <a href="{{ route('register') }}"
                           class="px-3 py-1.5 text-xs font-semibold bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">Daftar</a>
                    @endauth
                </div>
            </div>

            {{-- Konten (scroll sendiri) --}}
            <div class="flex-1 overflow-y-auto min-h-0">
                <div class="p-5 sm:p-6 space-y-5 max-w-xl mx-auto w-full pb-10">

                    {{-- Mobile: poster (hanya muncul di layar kecil) --}}
                    @if($event->banner_image)
                        <div class="lg:hidden rounded-2xl overflow-hidden">
                            <img src="{{ Storage::url($event->banner_image) }}"
                                 alt="{{ $event->title }}"
                                 class="w-full h-auto block">
                        </div>
                    @endif

                    {{-- Title + badges --}}
                    @php
                        $categoryIcons = ['music'=>'🎵','sports'=>'⚽','seminar'=>'🎓','exhibition'=>'🖼️','conference'=>'💼','other'=>'✨'];
                        $categoryLabel = \App\Models\Event::categories()[$event->category] ?? $event->category;
                    @endphp
                    <div>
                        <div class="flex flex-wrap gap-2 mb-2">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">
                                {{ $categoryIcons[$event->category] ?? '🎪' }} {{ $categoryLabel }}
                            </span>
                            @if($event->isFree())
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">GRATIS</span>
                            @endif
                        </div>
                        <h1 class="text-2xl font-black text-gray-900 leading-tight">{{ $event->title }}</h1>
                        <p class="text-sm text-gray-400 mt-1">
                            Diselenggarakan oleh <span class="font-semibold text-gray-600">{{ $event->organizer->name }}</span>
                        </p>
                    </div>

                    {{-- Info Cards --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-white rounded-xl border border-gray-100 p-3.5 flex items-center gap-3">
                            <div class="w-9 h-9 bg-indigo-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs text-gray-400">Tanggal</p>
                                <p class="text-sm font-bold text-gray-900 truncate">{{ $event->event_date->translatedFormat('d M Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $event->event_date->format('H:i') }} WIB</p>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl border border-gray-100 p-3.5 flex items-center gap-3">
                            <div class="w-9 h-9 bg-indigo-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs text-gray-400">Lokasi</p>
                                <p class="text-sm font-bold text-gray-900 leading-tight line-clamp-2">{{ $event->location }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Ticket Panel --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="p-4 flex items-center justify-between border-b border-gray-50">
                            <div>
                                <p class="text-xs text-gray-400">Harga Tiket</p>
                                @if($event->isFree())
                                    <p class="text-2xl font-black text-green-600">GRATIS</p>
                                @else
                                    <p class="text-2xl font-black text-gray-900">Rp {{ number_format($event->ticket_price, 0, ',', '.') }}</p>
                                @endif
                                <p class="text-xs text-gray-400">per tiket</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-400">Sisa Tiket</p>
                                <p class="text-2xl font-black {{ $event->remainingCapacity() < 10 ? 'text-red-500' : 'text-indigo-600' }}">
                                    {{ $event->remainingCapacity() }}
                                </p>
                                <p class="text-xs text-gray-400">dari {{ number_format($event->ticket_capacity, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        @php
                            $pct = $event->ticket_capacity > 0
                                ? min(100, round(($event->ticketsSold() / $event->ticket_capacity) * 100))
                                : 0;
                        @endphp
                        <div class="px-4 pt-3 pb-1">
                            <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all {{ $pct >= 90 ? 'bg-red-500' : ($pct >= 70 ? 'bg-orange-400' : 'bg-indigo-500') }}"
                                     style="width: {{ $pct }}%"></div>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">
                                {{ $pct }}% terjual
                                @if($pct >= 80) · <span class="text-orange-500 font-semibold">Hampir habis!</span>@endif
                            </p>
                        </div>

                        <div class="p-4">
                            @if($event->remainingCapacity() <= 0)
                                <button disabled class="w-full py-3 bg-gray-100 text-gray-400 text-sm font-semibold rounded-xl cursor-not-allowed">
                                    Tiket Habis
                                </button>

                            @elseif($userOrder)
                                <div class="bg-green-50 border border-green-200 rounded-xl p-3 text-center mb-3">
                                    <p class="text-sm font-bold text-green-700">✓ Kamu sudah punya tiket</p>
                                    <p class="text-xs text-green-600 mt-0.5">{{ $userOrder->quantity }} tiket · {{ $userOrder->order_code }}</p>
                                </div>
                                <a href="{{ route('orders.show', $userOrder) }}"
                                   class="block w-full py-3 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors text-center">
                                    Lihat Tiket Saya
                                </a>

                            @elseif(!auth()->check())
                                <a href="{{ route('login') }}"
                                   class="block w-full py-3 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors text-center">
                                    Login untuk Beli Tiket
                                </a>
                                <p class="text-xs text-gray-400 text-center mt-2">
                                    Belum punya akun? <a href="{{ route('register') }}" class="text-indigo-600 hover:underline">Daftar gratis</a>
                                </p>

                            @else
                                {{-- Error message --}}
                                @if(session('error'))
                                    <div class="bg-red-50 border border-red-200 rounded-xl px-3 py-2.5 mb-3">
                                        <p class="text-xs text-red-600 font-medium">{{ session('error') }}</p>
                                    </div>
                                @endif

                                {{-- Form beli tiket --}}
                                <form action="{{ route('orders.store') }}" method="POST" id="order-form">
                                    @csrf
                                    <input type="hidden" name="event_id" value="{{ $event->id }}">

                                    <div class="mb-3">
                                        <label class="text-xs text-gray-500 font-medium block mb-1.5">Jumlah Tiket</label>
                                        <div class="flex items-center gap-3">
                                            <button type="button" onclick="changeQty(-1)"
                                                    class="w-9 h-9 rounded-lg border border-gray-200 bg-gray-50 text-gray-700 font-bold text-lg hover:bg-gray-100 transition-colors flex items-center justify-center">
                                                −
                                            </button>
                                            <input type="number" name="quantity" id="qty-input"
                                                   value="1" min="1" max="{{ min(10, $event->remainingCapacity()) }}"
                                                   class="flex-1 text-center text-base font-bold text-gray-900 border border-gray-200 rounded-lg py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                                   onchange="updateTotal()" readonly>
                                            <button type="button" onclick="changeQty(1)"
                                                    class="w-9 h-9 rounded-lg border border-gray-200 bg-gray-50 text-gray-700 font-bold text-lg hover:bg-gray-100 transition-colors flex items-center justify-center">
                                                +
                                            </button>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between text-sm mb-4 py-2.5 border-t border-b border-gray-50">
                                        <span class="text-gray-500">Total</span>
                                        <span class="font-black text-gray-900 text-base" id="total-price">
                                            @if($event->isFree())
                                                Gratis
                                            @else
                                                Rp {{ number_format($event->ticket_price, 0, ',', '.') }}
                                            @endif
                                        </span>
                                    </div>

                                    <button type="submit"
                                            class="w-full py-3 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors shadow-sm shadow-indigo-200">
                                        Pesan Sekarang
                                    </button>
                                </form>

                                <script>
                                const price     = {{ $event->ticket_price }};
                                const isFree    = {{ $event->isFree() ? 'true' : 'false' }};
                                const maxQty    = {{ min(10, $event->remainingCapacity()) }};

                                function changeQty(delta) {
                                    const inp = document.getElementById('qty-input');
                                    const val = Math.min(maxQty, Math.max(1, parseInt(inp.value) + delta));
                                    inp.value = val;
                                    updateTotal();
                                }

                                function updateTotal() {
                                    const qty   = parseInt(document.getElementById('qty-input').value) || 1;
                                    const total = price * qty;
                                    const el    = document.getElementById('total-price');
                                    if (isFree) {
                                        el.textContent = 'Gratis';
                                    } else {
                                        el.textContent = 'Rp ' + total.toLocaleString('id-ID');
                                    }
                                }
                                </script>
                            @endif
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                        <div class="px-5 py-3.5 border-b border-gray-50">
                            <h2 class="text-sm font-bold text-gray-900">Tentang Event</h2>
                        </div>
                        <div class="px-5 py-4">
                            <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ $event->description }}</p>
                        </div>
                    </div>

                    {{-- Share --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Bagikan Event</p>
                        <div class="flex items-center gap-4">
                            <div class="p-1.5 bg-gray-50 rounded-xl border border-gray-100 flex-shrink-0">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ urlencode(route('events.show', $event)) }}&color=4F46E5&bgcolor=FFFFFF"
                                     alt="QR" class="w-16 h-16 rounded-lg" loading="lazy">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-500 mb-2">Scan QR atau salin link</p>
                                <div class="flex items-center gap-2 bg-gray-50 rounded-lg px-3 py-2 border border-gray-100">
                                    <span class="text-xs text-gray-400 flex-1 truncate">{{ route('events.show', $event) }}</span>
                                    <button id="copy-btn"
                                            onclick="navigator.clipboard.writeText('{{ route('events.show', $event) }}').then(() => { document.getElementById('copy-btn').textContent = '✓'; setTimeout(() => document.getElementById('copy-btn').textContent = 'Salin', 2000) })"
                                            class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex-shrink-0">
                                        Salin
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p class="text-xs text-gray-300 text-center">
                        {{ config('app.name') }} · Platform Event Ticketing · {{ date('Y') }}
                    </p>

                </div>
            </div>
        </div>

    </div>

</body>
</html>
