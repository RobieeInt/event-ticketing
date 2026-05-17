<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Akun Disuspend — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full text-center space-y-6">

        {{-- Icon --}}
        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto">
            <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
            </svg>
        </div>

        {{-- Title --}}
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Akun Kamu Disuspend</h1>
            <p class="text-gray-500 mt-2 text-sm leading-relaxed">
                Akun kamu telah disuspend oleh admin. Kamu tidak bisa mengakses platform ini untuk sementara waktu.
            </p>
        </div>

        {{-- Info box --}}
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-left">
            <p class="text-sm font-medium text-red-800 mb-1">Yang perlu kamu lakukan:</p>
            <ul class="text-sm text-red-700 space-y-1 list-disc list-inside">
                <li>Hubungi admin untuk informasi lebih lanjut</li>
                <li>Pastikan kamu tidak melanggar ketentuan platform</li>
                <li>Setelah masalah selesai, akun akan diaktifkan kembali oleh admin</li>
            </ul>
        </div>

        {{-- Action --}}
        <div class="flex flex-col gap-3">
            <a href="{{ route('login') }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Halaman Login
            </a>
        </div>

        <p class="text-xs text-gray-400">{{ config('app.name') }} &mdash; Platform Event Ticketing</p>
    </div>

</body>
</html>
