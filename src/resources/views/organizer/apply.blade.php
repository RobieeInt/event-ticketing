<x-app-layout>
    <div class="max-w-xl mx-auto p-6">

        <h1 class="text-xl font-semibold mb-4">Apply Jadi Event Organizer</h1>

        <form method="POST" action="{{ route('organizer.apply.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="text-sm">Nama Brand / Komunitas</label>
                <input class="w-full border rounded p-2" name="company_name"
                    value="{{ old('company_name', $application->company_name ?? '') }}">
            </div>

            <div>
                <label class="text-sm">Phone</label>
                <input class="w-full border rounded p-2" name="phone"
                    value="{{ old('phone', $application->phone ?? '') }}">
            </div>

            <div>
                <label class="text-sm">Alasan</label>
                <textarea class="w-full border rounded p-2" name="reason">{{ old('reason', $application->reason ?? '') }}</textarea>
            </div>

            <button class="px-4 py-2 bg-black text-white rounded">
                Kirim Pengajuan
            </button>
        </form>
    </div>
</x-app-layout>
