<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Check-in · {{ $event->title }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
</head>
<body class="font-sans antialiased" style="height:100vh;overflow:hidden;background:#0f172a;">

    {{-- TOP BAR --}}
    <div class="bg-gray-900/80 backdrop-blur border-b border-white/10 flex-shrink-0">
        <div class="flex items-center justify-between px-5 py-3" style="height:56px;">
            <div class="flex items-center gap-3 min-w-0">
                <a href="{{ route('organizer.events.show', $event) }}"
                   class="p-1.5 rounded-lg text-gray-400 hover:text-white hover:bg-white/10 transition-colors flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div class="min-w-0">
                    <p class="text-xs text-gray-400 leading-none">Check-in Scanner</p>
                    <p class="text-sm font-bold text-white truncate max-w-xs">{{ $event->title }}</p>
                </div>
                {{-- Event info pills --}}
                <div class="hidden lg:flex items-center gap-2 ml-2">
                    <span class="inline-flex items-center gap-1 text-xs text-gray-400 bg-white/10 px-2.5 py-1 rounded-full">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $event->event_date->format('d M Y') }}
                    </span>
                    <span class="inline-flex items-center gap-1 text-xs text-gray-400 bg-white/10 px-2.5 py-1 rounded-full">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                        {{ Str::limit($event->location, 25) }}
                    </span>
                </div>
            </div>
            <div class="flex items-center gap-5 flex-shrink-0">
                <div class="text-right">
                    <p class="text-xs text-gray-400">Hadir</p>
                    <p class="text-base font-black text-emerald-400" id="stat-checked">{{ $totalCheckedIn }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-400">Tiket Lunas</p>
                    <p class="text-base font-black text-white">{{ $totalPaid }}</p>
                </div>
                <div class="text-right hidden sm:block">
                    <p class="text-xs text-gray-400">Kehadiran</p>
                    <p class="text-base font-black text-indigo-400" id="stat-pct">{{ $totalPaid > 0 ? round($totalCheckedIn / $totalPaid * 100) : 0 }}%</p>
                </div>
            </div>
        </div>
        {{-- Progress bar --}}
        <div class="h-0.5 bg-white/10">
            <div id="progress-bar" class="h-full bg-emerald-500 transition-all duration-500"
                 style="width: {{ $totalPaid > 0 ? round($totalCheckedIn / $totalPaid * 100) : 0 }}%"></div>
        </div>
    </div>

    {{-- MAIN LAYOUT --}}
    <div class="flex" style="height:calc(100vh - 56px);">

        {{-- LEFT: CAMERA SCANNER --}}
        <div class="flex flex-col items-center justify-center bg-gray-950 flex-shrink-0"
             style="width:480px;">

            {{-- Viewfinder frame --}}
            <div class="relative">
                <div id="qr-reader" class="rounded-2xl overflow-hidden" style="width:340px;"></div>

                {{-- Corner decorations --}}
                <div class="absolute inset-0 pointer-events-none">
                    <div class="absolute top-3 left-3 w-8 h-8 border-t-4 border-l-4 border-emerald-400 rounded-tl-lg"></div>
                    <div class="absolute top-3 right-3 w-8 h-8 border-t-4 border-r-4 border-emerald-400 rounded-tr-lg"></div>
                    <div class="absolute bottom-3 left-3 w-8 h-8 border-b-4 border-l-4 border-emerald-400 rounded-bl-lg"></div>
                    <div class="absolute bottom-3 right-3 w-8 h-8 border-b-4 border-r-4 border-emerald-400 rounded-br-lg"></div>
                </div>
            </div>

            {{-- Scan line animation --}}
            <div class="mt-5 flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></div>
                <p class="text-sm text-gray-400" id="scan-status">Menginisialisasi kamera...</p>
            </div>

            {{-- Manual input fallback --}}
            <div class="mt-6 w-full px-10">
                <p class="text-xs text-gray-600 text-center mb-2">Atau masukkan kode manual</p>
                <div class="flex gap-2">
                    <input type="text" id="manual-code" placeholder="ORD-XXXXXXXX-XXXXXX"
                           class="flex-1 bg-gray-800 border border-gray-700 text-white text-xs rounded-xl px-3 py-2.5 placeholder-gray-600 focus:outline-none focus:border-emerald-500 font-mono uppercase">
                    <button onclick="submitManual()"
                            class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-colors">
                        Cek
                    </button>
                </div>
            </div>
        </div>

        {{-- RIGHT: RESULT PANEL --}}
        <div class="flex-1 bg-gray-50 overflow-y-auto flex flex-col">

            {{-- Default state --}}
            <div id="panel-idle" class="flex flex-col items-center justify-center h-full text-center px-8">
                <div class="w-24 h-24 bg-white rounded-3xl shadow-sm flex items-center justify-center mb-5 border border-gray-100">
                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-black text-gray-900 mb-2">Siap Scan</h3>
                <p class="text-sm text-gray-400 max-w-xs">Arahkan kamera ke QR code pada e-ticket peserta. Hasil verifikasi akan muncul di sini.</p>
            </div>

            {{-- Result state (hidden by default) --}}
            <div id="panel-result" class="hidden p-6 space-y-4">

                {{-- Status badge --}}
                <div id="result-banner" class="rounded-3xl p-5 relative overflow-hidden">
                    <div class="relative flex items-center gap-4">
                        <div id="result-icon" class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0"></div>
                        <div>
                            <p id="result-label" class="text-xs font-bold uppercase tracking-wider mb-0.5"></p>
                            <p id="result-title" class="text-xl font-black"></p>
                            <p id="result-subtitle" class="text-sm mt-0.5 opacity-75"></p>
                        </div>
                    </div>
                </div>

                {{-- Ticket detail card --}}
                <div id="ticket-card" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-50">
                        <h3 class="text-sm font-bold text-gray-900">Detail Tiket</h3>
                    </div>
                    <div class="p-5 space-y-3 text-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Kode Order</span>
                            <span id="t-code" class="font-mono font-bold text-indigo-600 text-xs"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Nama Peserta</span>
                            <span id="t-name" class="font-semibold text-gray-900"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Jumlah Tiket</span>
                            <span id="t-qty" class="font-bold text-gray-900 text-base"></span>
                        </div>
                        <div class="flex justify-between items-center border-t border-gray-50 pt-3">
                            <span class="text-gray-500">Waktu Check-in</span>
                            <span id="t-time" class="text-gray-700 text-xs font-medium"></span>
                        </div>
                    </div>
                </div>

                {{-- Scan next button --}}
                <button onclick="resetScan()"
                        class="w-full py-3.5 bg-gray-900 hover:bg-gray-800 text-white font-bold text-sm rounded-xl transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Scan Berikutnya
                </button>

                {{-- Check-in history --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                    <div class="px-5 py-3.5 border-b border-gray-50">
                        <h3 class="text-sm font-bold text-gray-900">Riwayat Scan Sesi Ini</h3>
                    </div>
                    <div id="history-list" class="divide-y divide-gray-50 max-h-48 overflow-y-auto">
                        <p class="text-xs text-gray-400 text-center py-4">Belum ada scan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    const SCAN_URL   = '{{ route('organizer.events.scan', $event) }}';
    const CSRF_TOKEN = '{{ csrf_token() }}';
    let isProcessing = false;
    let scanHistory  = [];
    let qrScanner   = null;

    // --- QR Scanner init ---
    function startScanner() {
        qrScanner = new Html5Qrcode('qr-reader');

        Html5Qrcode.getCameras().then(cameras => {
            if (!cameras || cameras.length === 0) {
                setScanStatus('Kamera tidak ditemukan.', false);
                return;
            }

            const cameraId = cameras[cameras.length - 1].id;

            qrScanner.start(
                cameraId,
                { fps: 10, qrbox: { width: 240, height: 240 }, aspectRatio: 1.0 },
                onQrSuccess,
                () => {}
            ).then(() => {
                setScanStatus('Arahkan ke QR tiket...', true);
                cleanupScannerUI();
            }).catch(err => {
                setScanStatus('Gagal akses kamera: ' + err, false);
            });
        }).catch(() => {
            setScanStatus('Tidak dapat mengakses kamera.', false);
        });
    }

    function cleanupScannerUI() {
        setTimeout(() => {
            const btns = document.querySelectorAll('#qr-reader button');
            btns.forEach(b => b.style.display = 'none');
            const selects = document.querySelectorAll('#qr-reader select');
            selects.forEach(s => s.style.display = 'none');
            const imgs = document.querySelectorAll('#qr-reader img');
            imgs.forEach(i => i.style.display = 'none');
        }, 800);
    }

    function setScanStatus(msg, active) {
        const el = document.getElementById('scan-status');
        el.textContent = msg;
        el.previousElementSibling.className = active
            ? 'w-2 h-2 rounded-full bg-emerald-400 animate-pulse'
            : 'w-2 h-2 rounded-full bg-red-400';
    }

    // --- QR scan callback ---
    function onQrSuccess(decodedText) {
        if (isProcessing) return;
        submitCode(decodedText.trim().toUpperCase());
    }

    function submitManual() {
        const code = document.getElementById('manual-code').value.trim().toUpperCase();
        if (!code) return;
        submitCode(code);
        document.getElementById('manual-code').value = '';
    }

    async function submitCode(orderCode) {
        isProcessing = true;
        setScanStatus('Memverifikasi...', true);

        try {
            const res = await fetch(SCAN_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ order_code: orderCode }),
            });

            const data = await res.json();
            showResult(data);
            addHistory(data, orderCode);

        } catch(e) {
            showError('Gagal menghubungi server. Cek koneksi internet.');
        }
    }

    function showResult(data) {
        document.getElementById('panel-idle').classList.add('hidden');
        document.getElementById('panel-result').classList.remove('hidden');

        const banner   = document.getElementById('result-banner');
        const icon     = document.getElementById('result-icon');
        const label    = document.getElementById('result-label');
        const title    = document.getElementById('result-title');
        const subtitle = document.getElementById('result-subtitle');
        const card     = document.getElementById('ticket-card');

        if (data.success && data.type === 'ok') {
            // SUCCESS
            banner.className = 'rounded-3xl p-5 relative overflow-hidden bg-gradient-to-r from-emerald-500 to-green-600 text-white';
            icon.className   = 'w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0 bg-white/20';
            icon.innerHTML   = '<svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>';
            label.textContent    = 'Check-in Berhasil';
            title.textContent    = data.order.attendee;
            subtitle.textContent = data.order.quantity + ' tiket · ' + data.order.order_code;

            card.classList.remove('hidden');
            document.getElementById('t-code').textContent = data.order.order_code;
            document.getElementById('t-name').textContent = data.order.attendee;
            document.getElementById('t-qty').textContent  = data.order.quantity + ' tiket';
            document.getElementById('t-time').textContent = data.order.checked_in_at;

            // Update counter
            const stat = document.getElementById('stat-checked');
            stat.textContent = parseInt(stat.textContent) + 1;

            setScanStatus('Check-in berhasil! Siap scan berikutnya.', true);
            playBeep('success');

        } else if (data.type === 'already_used') {
            // ALREADY USED
            banner.className = 'rounded-3xl p-5 relative overflow-hidden bg-gradient-to-r from-orange-500 to-amber-500 text-white';
            icon.className   = 'w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0 bg-white/20';
            icon.innerHTML   = '<svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>';
            label.textContent    = 'Tiket Sudah Digunakan!';
            title.textContent    = data.order.attendee;
            subtitle.textContent = 'Check-in: ' + data.checked_in_at;

            card.classList.remove('hidden');
            document.getElementById('t-code').textContent = data.order.order_code;
            document.getElementById('t-name').textContent = data.order.attendee;
            document.getElementById('t-qty').textContent  = data.order.quantity + ' tiket';
            document.getElementById('t-time').textContent = data.checked_in_at + ' (sudah check-in)';

            setScanStatus('Tiket sudah digunakan!', false);
            playBeep('warning');

        } else {
            // ERROR
            showError(data.message);
        }

        setTimeout(() => { isProcessing = false; }, 2000);
    }

    function showError(msg) {
        document.getElementById('panel-idle').classList.add('hidden');
        document.getElementById('panel-result').classList.remove('hidden');

        const banner   = document.getElementById('result-banner');
        const icon     = document.getElementById('result-icon');
        const label    = document.getElementById('result-label');
        const title    = document.getElementById('result-title');
        const subtitle = document.getElementById('result-subtitle');

        banner.className = 'rounded-3xl p-5 relative overflow-hidden bg-gradient-to-r from-red-500 to-rose-600 text-white';
        icon.className   = 'w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0 bg-white/20';
        icon.innerHTML   = '<svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';
        label.textContent    = 'Tiket Tidak Valid';
        title.textContent    = 'Gagal!';
        subtitle.textContent = msg;

        document.getElementById('ticket-card').classList.add('hidden');

        setScanStatus('Scan gagal. Coba lagi.', false);
        playBeep('error');

        setTimeout(() => { isProcessing = false; }, 1500);
    }

    function resetScan() {
        document.getElementById('panel-idle').classList.remove('hidden');
        document.getElementById('panel-result').classList.add('hidden');
        document.getElementById('ticket-card').classList.remove('hidden');
        isProcessing = false;
        setScanStatus('Arahkan ke QR tiket...', true);
    }

    function addHistory(data, code) {
        const list = document.getElementById('history-list');

        if (list.querySelector('p')) list.innerHTML = '';

        const time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        const isOk = data.success && data.type === 'ok';
        const isWarn = data.type === 'already_used';

        const item = document.createElement('div');
        item.className = 'flex items-center gap-3 px-5 py-3';
        item.innerHTML = `
            <div class="w-2 h-2 rounded-full flex-shrink-0 ${isOk ? 'bg-emerald-400' : isWarn ? 'bg-orange-400' : 'bg-red-400'}"></div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-mono font-semibold text-gray-900 truncate">${code}</p>
                <p class="text-xs text-gray-400">${isOk ? (data.order?.attendee || '') : (isWarn ? 'Sudah digunakan' : data.message)}</p>
            </div>
            <span class="text-xs text-gray-400 flex-shrink-0">${time}</span>
        `;
        list.insertBefore(item, list.firstChild);
    }

    function playBeep(type) {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);

            osc.frequency.value = type === 'success' ? 880 : type === 'warning' ? 440 : 220;
            osc.type = 'sine';
            gain.gain.setValueAtTime(0.3, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.3);
            osc.start(ctx.currentTime);
            osc.stop(ctx.currentTime + 0.3);
        } catch(e) {}
    }

    // Handle Enter key on manual input
    document.getElementById('manual-code').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') submitManual();
    });

    startScanner();
    </script>

</body>
</html>
