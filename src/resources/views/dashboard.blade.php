<x-app-layout>
    <div class="max-w-4xl mx-auto p-6 space-y-6">

        <h1 class="text-xl font-semibold">Dashboard</h1>

        @if (session('status'))
            <div class="text-green-600 text-sm">{{ session('status') }}</div>
        @endif

        {{-- INFO USER --}}
        <div class="border rounded p-4">
            <div><b>Nama:</b> {{ $user->name }}</div>
            <div><b>Email:</b> {{ $user->email }}</div>
            <div><b>Role:</b> {{ strtoupper($user->role) }}</div>
        </div>

        {{-- ORGANIZER STATUS --}}
        <div class="border rounded p-4">
            <h2 class="font-semibold mb-2">Status Organizer</h2>

            @if (!$application)
                <p class="text-sm text-gray-600 mb-3">
                    Kamu belum mengajukan sebagai Event Organizer.
                </p>
                <a href="{{ route('organizer.apply') }}" class="inline-block px-4 py-2 bg-black text-white rounded">
                    Apply Jadi Organizer
                </a>
            @elseif($application->status === 'pending')
                <p class="text-yellow-600">Pengajuan sedang diproses.</p>
            @elseif($application->status === 'rejected')
                <p class="text-red-600">
                    Pengajuan ditolak.
                    @if ($application->review_note)
                        <br>Catatan: {{ $application->review_note }}
                    @endif
                </p>
                <a href="{{ route('organizer.apply') }}"
                    class="inline-block mt-3 px-4 py-2 bg-black text-white rounded">
                    Ajukan Ulang
                </a>
            @elseif($application->status === 'approved')
                <p class="text-green-600">Kamu sudah Organizer 🎉</p>
                <a href="{{ route('organizer.dashboard') }}"
                    class="inline-block mt-3 px-4 py-2 bg-emerald-600 text-white rounded">
                    Masuk Dashboard Organizer
                </a>
            @endif
        </div>

    </div>
</x-app-layout>
