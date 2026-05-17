<x-organizer-layout>
    <x-slot name="header">
        Detail Event
    </x-slot>

    {{-- Flash --}}
    @if(session('status'))
        <div class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('status') }}
        </div>
    @endif

    <div class="max-w-3xl mx-auto space-y-4">

        {{-- ====== HEADER CARD ====== --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            {{-- Top stripe indigo --}}
            <div class="h-1.5 bg-gradient-to-r from-indigo-500 to-purple-500"></div>

            <div class="p-6">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="text-xl font-bold text-indigo-700">{{ strtoupper(substr($event->title, 0, 1)) }}</span>
                        </div>
                        <div class="min-w-0">
                            <h1 class="text-lg font-bold text-gray-900 leading-tight">{{ $event->title }}</h1>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-sm text-gray-500">{{ \App\Models\Event::categories()[$event->category] ?? $event->category }}</span>
                                @if($event->isFree())
                                    <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700">Gratis</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @php
                        $badgeClass = match($event->status) {
                            'published' => 'bg-green-100 text-green-700 border border-green-200',
                            'draft'     => 'bg-yellow-100 text-yellow-700 border border-yellow-200',
                            'cancelled' => 'bg-red-100 text-red-700 border border-red-200',
                            default     => 'bg-gray-100 text-gray-600',
                        };
                    @endphp
                    <span class="flex-shrink-0 inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-current opacity-60"></span>
                        {{ ucfirst($event->status) }}
                    </span>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-wrap items-center gap-2 mt-5 pt-5 border-t border-gray-100">
                    <a href="{{ route('organizer.events.edit', $event) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Event
                    </a>

                    @if($event->status === 'published')
                        <a href="{{ route('events.show', $event) }}" target="_blank"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            Halaman Publik
                        </a>
                    @endif

                    <form method="POST" action="{{ route('organizer.events.destroy', $event) }}"
                          onsubmit="return confirm('Hapus event ini? Tidak bisa dibatalkan.')" class="ml-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-red-600 border border-red-200 rounded-xl hover:bg-red-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ====== EVENT DETAIL + QR CODE ====== --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

            {{-- Detail Info --}}
            <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm divide-y divide-gray-50">
                <div class="grid grid-cols-2 gap-4 p-5">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Tanggal & Waktu</p>
                        <p class="text-sm font-bold text-gray-900">{{ $event->event_date->format('d M Y') }}</p>
                        <p class="text-sm text-gray-500">{{ $event->event_date->format('H:i') }} WIB</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Lokasi</p>
                        <p class="text-sm font-bold text-gray-900">{{ $event->location }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4 p-5">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Harga Tiket</p>
                        @if($event->isFree())
                            <p class="text-sm font-bold text-green-600">Gratis</p>
                        @else
                            <p class="text-sm font-bold text-gray-900">Rp {{ number_format($event->ticket_price, 0, ',', '.') }}</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Kapasitas</p>
                        <p class="text-sm font-bold text-gray-900">{{ number_format($event->ticket_capacity, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-400">tiket</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Terjual</p>
                        <p class="text-sm font-bold text-indigo-600">{{ $event->ticketsSold() }}</p>
                        <p class="text-xs text-gray-400">tiket terjual</p>
                    </div>
                </div>

                <div class="p-5">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Deskripsi</p>
                    <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $event->description }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4 px-5 py-3 bg-gray-50 rounded-b-2xl text-xs text-gray-400">
                    <div>Dibuat: {{ $event->created_at->format('d M Y, H:i') }}</div>
                    <div>Update: {{ $event->updated_at->format('d M Y, H:i') }}</div>
                </div>
            </div>

            {{-- QR Code Panel --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col items-center justify-center text-center">
                @if($event->status === 'published')
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">QR Halaman Event</p>
                    @php $publicUrl = route('events.show', $event); @endphp
                    <div class="bg-white border-2 border-gray-100 rounded-xl p-2 mb-3">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data={{ urlencode($publicUrl) }}&color=4F46E5&bgcolor=ffffff"
                             alt="QR Code {{ $event->title }}"
                             class="w-40 h-40 rounded-lg"
                             loading="lazy">
                    </div>
                    <p class="text-xs text-gray-500 mb-3 break-all leading-relaxed">{{ $publicUrl }}</p>
                    <a href="{{ $publicUrl }}" target="_blank"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 border border-indigo-200 rounded-lg hover:bg-indigo-50 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Buka Halaman Publik
                    </a>
                @else
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-500">QR tersedia setelah</p>
                    <p class="text-sm font-medium text-gray-500">event dipublish</p>
                    <a href="{{ route('organizer.events.edit', $event) }}"
                       class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 border border-indigo-200 rounded-lg hover:bg-indigo-50 transition-colors">
                        Publish Sekarang →
                    </a>
                @endif
            </div>
        </div>

        {{-- ====== TICKET BUYERS + CHECK-IN TABS ====== --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">

            {{-- Tab Header --}}
            <div class="px-6 pt-4 border-b border-gray-100">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex gap-1">
                        <button onclick="switchTab('buyers')" id="tab-buyers"
                                class="tab-btn px-4 py-2 text-sm font-semibold rounded-t-lg border-b-2 border-indigo-600 text-indigo-700 bg-indigo-50 transition-colors">
                            Pembeli Tiket
                            <span class="ml-1.5 px-1.5 py-0.5 rounded-full text-xs bg-indigo-100 text-indigo-600">{{ $orders->total() }}</span>
                        </button>
                        <button onclick="switchTab('checkin')" id="tab-checkin"
                                class="tab-btn px-4 py-2 text-sm font-semibold rounded-t-lg border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors">
                            Sudah Check-in
                            <span class="ml-1.5 px-1.5 py-0.5 rounded-full text-xs {{ $totalCheckedIn > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">{{ $totalCheckedIn }}</span>
                        </button>
                    </div>
                    {{-- Stats mini --}}
                    <div class="flex items-center gap-4 text-xs">
                        <div class="text-center">
                            <p class="font-bold text-green-600 text-sm">{{ $totalPaid }}</p>
                            <p class="text-gray-400">Lunas</p>
                        </div>
                        <div class="text-center">
                            <p class="font-bold text-yellow-600 text-sm">{{ $orders->where('status', 'pending')->count() }}</p>
                            <p class="text-gray-400">Pending</p>
                        </div>
                        <div class="text-center">
                            <p class="font-bold text-red-500 text-sm">{{ $orders->where('status', 'cancelled')->count() }}</p>
                            <p class="text-gray-400">Batal</p>
                        </div>
                        @if($event->status === 'published')
                        <a href="{{ route('organizer.events.checkin', $event) }}"
                           class="ml-2 inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 text-white text-xs font-bold rounded-lg hover:bg-emerald-700 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                            </svg>
                            Scan QR
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- TAB: Pembeli Tiket --}}
            <div id="pane-buyers">

            @if($orders->isEmpty())
                <div class="text-center py-14">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-500">Belum ada tiket terjual</p>
                    @if($event->status !== 'published')
                        <p class="text-xs text-gray-400 mt-1">Publish event dulu agar bisa dibeli</p>
                    @else
                        <p class="text-xs text-gray-400 mt-1">Share QR code di atas untuk mulai menjual tiket</p>
                    @endif
                </div>
            @else
                {{-- Desktop Table --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-6 py-3">Pembeli</th>
                                <th class="px-6 py-3">Kode Order</th>
                                <th class="px-6 py-3">Jumlah</th>
                                <th class="px-6 py-3">Total</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($orders as $order)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0">
                                                <span class="text-xs font-bold text-indigo-700">{{ strtoupper(substr($order->user->name, 0, 1)) }}</span>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $order->user->name }}</p>
                                                <p class="text-xs text-gray-400">{{ $order->user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <code class="text-xs font-mono text-gray-600 bg-gray-100 px-2 py-0.5 rounded">{{ $order->order_code }}</code>
                                    </td>
                                    <td class="px-6 py-4 text-gray-700">{{ $order->quantity }} tiket</td>
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        @if($order->total_price == 0)
                                            <span class="text-green-600">Gratis</span>
                                        @else
                                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusClass = match($order->status) {
                                                'paid'      => 'bg-green-100 text-green-700',
                                                'pending'   => 'bg-yellow-100 text-yellow-700',
                                                'cancelled' => 'bg-red-100 text-red-700',
                                                default     => 'bg-gray-100 text-gray-600',
                                            };
                                            $statusLabel = match($order->status) {
                                                'paid'      => 'Lunas',
                                                'pending'   => 'Pending',
                                                'cancelled' => 'Batal',
                                                default     => $order->status,
                                            };
                                        @endphp
                                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-400">
                                        {{ $order->created_at->format('d M Y') }}<br>
                                        {{ $order->created_at->format('H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards --}}
                <div class="md:hidden divide-y divide-gray-50">
                    @foreach($orders as $order)
                        <div class="p-4 space-y-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                        <span class="text-xs font-bold text-indigo-700">{{ strtoupper(substr($order->user->name, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $order->user->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $order->quantity }} tiket</p>
                                    </div>
                                </div>
                                @php
                                    $statusClass = match($order->status) {
                                        'paid'      => 'bg-green-100 text-green-700',
                                        'pending'   => 'bg-yellow-100 text-yellow-700',
                                        'cancelled' => 'bg-red-100 text-red-700',
                                        default     => 'bg-gray-100 text-gray-600',
                                    };
                                    $statusLabel = match($order->status) {
                                        'paid' => 'Lunas', 'pending' => 'Pending', 'cancelled' => 'Batal', default => $order->status,
                                    };
                                @endphp
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold {{ $statusClass }}">{{ $statusLabel }}</span>
                            </div>
                            <div class="flex items-center justify-between text-xs text-gray-400">
                                <code class="font-mono bg-gray-100 px-2 py-0.5 rounded">{{ $order->order_code }}</code>
                                <span>{{ $order->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($orders->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $orders->links() }}
                    </div>
                @endif
            @endif
            </div>{{-- end pane-buyers --}}

            {{-- TAB: Sudah Check-in --}}
            <div id="pane-checkin" class="hidden">
                @if($checkedInOrders->isEmpty())
                    <div class="text-center py-14">
                        <div class="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-500">Belum ada peserta yang check-in</p>
                        @if($event->status === 'published')
                            <a href="{{ route('organizer.events.checkin', $event) }}"
                               class="mt-3 inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-xl hover:bg-emerald-700 transition-colors">
                                Mulai Scan QR
                            </a>
                        @endif
                    </div>
                @else
                    {{-- Summary bar --}}
                    <div class="mx-6 mt-4 mb-3 bg-emerald-50 border border-emerald-100 rounded-xl px-4 py-3 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-emerald-800">{{ $totalCheckedIn }} dari {{ $totalPaid }} peserta hadir</p>
                                <p class="text-xs text-emerald-600">
                                    @if($totalPaid > 0)
                                        {{ round($totalCheckedIn / $totalPaid * 100) }}% kehadiran
                                    @else
                                        0% kehadiran
                                    @endif
                                </p>
                            </div>
                        </div>
                        {{-- Progress bar --}}
                        <div class="w-32 bg-emerald-100 rounded-full h-2">
                            <div class="bg-emerald-500 h-2 rounded-full transition-all"
                                 style="width: {{ $totalPaid > 0 ? round($totalCheckedIn / $totalPaid * 100) : 0 }}%"></div>
                        </div>
                    </div>

                    {{-- Desktop table --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <th class="px-6 py-3">Peserta</th>
                                    <th class="px-6 py-3">Kode Order</th>
                                    <th class="px-6 py-3">Jumlah Tiket</th>
                                    <th class="px-6 py-3">Waktu Check-in</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($checkedInOrders as $order)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                                                    <span class="text-xs font-bold text-emerald-700">{{ strtoupper(substr($order->user->name, 0, 1)) }}</span>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ $order->user->name }}</p>
                                                    <p class="text-xs text-gray-400">{{ $order->user->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <code class="text-xs font-mono text-gray-600 bg-gray-100 px-2 py-0.5 rounded">{{ $order->order_code }}</code>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-bold text-gray-900">{{ $order->quantity }}</span>
                                            <span class="text-gray-400 text-xs"> tiket</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                {{ $order->checked_in_at->format('d M Y, H:i') }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile cards --}}
                    <div class="md:hidden divide-y divide-gray-50">
                        @foreach($checkedInOrders as $order)
                            <div class="p-4 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-9 h-9 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-xs font-bold text-emerald-700">{{ strtoupper(substr($order->user->name, 0, 1)) }}</span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $order->user->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $order->quantity }} tiket · {{ $order->order_code }}</p>
                                    </div>
                                </div>
                                <span class="text-xs text-emerald-600 font-medium whitespace-nowrap">{{ $order->checked_in_at->format('H:i') }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>{{-- end pane-checkin --}}

        </div>

    </div>

    <script>
    function switchTab(tab) {
        const buyers  = document.getElementById('pane-buyers');
        const checkin = document.getElementById('pane-checkin');
        const tabB    = document.getElementById('tab-buyers');
        const tabC    = document.getElementById('tab-checkin');

        if (tab === 'buyers') {
            buyers.classList.remove('hidden');
            checkin.classList.add('hidden');
            tabB.className = 'tab-btn px-4 py-2 text-sm font-semibold rounded-t-lg border-b-2 border-indigo-600 text-indigo-700 bg-indigo-50 transition-colors';
            tabC.className = 'tab-btn px-4 py-2 text-sm font-semibold rounded-t-lg border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors';
        } else {
            checkin.classList.remove('hidden');
            buyers.classList.add('hidden');
            tabC.className = 'tab-btn px-4 py-2 text-sm font-semibold rounded-t-lg border-b-2 border-indigo-600 text-indigo-700 bg-indigo-50 transition-colors';
            tabB.className = 'tab-btn px-4 py-2 text-sm font-semibold rounded-t-lg border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors';
        }
    }
    </script>
</x-organizer-layout>
