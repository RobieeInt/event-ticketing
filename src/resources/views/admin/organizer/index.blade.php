<x-admin-layout>
    <x-slot name="breadcrumb">Pengajuan Organizer</x-slot>

    <div class="p-6 space-y-6">

        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Pengajuan Organizer</h1>
                <p class="text-sm text-gray-500 mt-0.5">Review, setujui, tolak, atau suspend organizer yang melanggar ketentuan.</p>
            </div>
        </div>

        {{-- Flash Success --}}
        @if (session('status'))
            <div class="flex items-start gap-3 bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-sm">
                <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        {{-- Flash Error --}}
        @if ($errors->has('msg'))
            <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 text-sm">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ $errors->first('msg') }}</span>
            </div>
        @endif

        {{-- Filter Tabs --}}
        <div class="flex gap-2 flex-wrap">
            @foreach ([
                'pending'  => ['label' => 'Pending'],
                'approved' => ['label' => 'Disetujui'],
                'rejected' => ['label' => 'Ditolak'],
                'all'      => ['label' => 'Semua'],
            ] as $key => $cfg)
                <a href="{{ route('admin.organizer.index', ['status' => $key]) }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium border transition-all
                          {{ $status === $key
                              ? 'bg-indigo-600 text-white border-indigo-600 shadow-sm'
                              : 'bg-white text-gray-600 border-gray-200 hover:border-indigo-300 hover:text-indigo-600' }}">
                    {{ $cfg['label'] }}
                    @php $count = $key === 'all' ? \App\Models\OrganizerApplication::count() : \App\Models\OrganizerApplication::where('status', $key)->count(); @endphp
                    <span class="ml-1.5 text-xs {{ $status === $key ? 'text-indigo-200' : 'text-gray-400' }}">{{ $count }}</span>
                </a>
            @endforeach
        </div>

        {{-- Table Card --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">

            @if ($applications->isEmpty())
                <div class="py-16 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <p class="text-gray-500 text-sm font-medium">Tidak ada pengajuan</p>
                    <p class="text-gray-400 text-xs mt-1">Belum ada pengajuan dengan status ini.</p>
                </div>
            @else

                {{-- Desktop Table --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pemohon</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Perusahaan</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Alasan</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($applications as $app)
                                <tr class="hover:bg-gray-50/70 transition-colors {{ $app->user->isSuspended() ? 'bg-red-50/30' : '' }}">
                                    {{-- Pemohon --}}
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full {{ $app->user->isSuspended() ? 'bg-red-100' : 'bg-indigo-100' }} flex items-center justify-center text-xs font-bold {{ $app->user->isSuspended() ? 'text-red-500' : 'text-indigo-600' }} flex-shrink-0">
                                                {{ strtoupper(substr($app->user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $app->user->name }}</div>
                                                <div class="text-xs text-gray-400">{{ $app->user->email }}</div>
                                                @if ($app->user->isSuspended())
                                                    <span class="inline-flex items-center gap-1 text-xs text-red-600 font-medium mt-0.5">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                                                        </svg>
                                                        SUSPENDED
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    {{-- Perusahaan --}}
                                    <td class="px-5 py-4 text-gray-700">{{ $app->company_name }}</td>
                                    {{-- Alasan --}}
                                    <td class="px-5 py-4 text-gray-500 max-w-xs">
                                        <p class="truncate" title="{{ $app->reason }}">{{ $app->reason }}</p>
                                        @if ($app->review_note)
                                            <p class="text-xs text-red-500 mt-1 italic truncate" title="{{ $app->review_note }}">
                                                Ditolak: {{ $app->review_note }}
                                            </p>
                                        @endif
                                        @if ($app->user->isSuspended())
                                            <p class="text-xs text-orange-600 mt-1 italic truncate" title="{{ $app->user->suspension_reason }}">
                                                Suspend: {{ $app->user->suspension_reason }}
                                            </p>
                                        @endif
                                    </td>
                                    {{-- Status --}}
                                    <td class="px-5 py-4">
                                        @if ($app->status === 'pending')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span>
                                                Pending
                                            </span>
                                        @elseif ($app->status === 'approved')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                                Disetujui
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                                Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    {{-- Tanggal --}}
                                    <td class="px-5 py-4 text-gray-500 whitespace-nowrap text-xs">
                                        {{ $app->created_at->format('d M Y') }}
                                    </td>
                                    {{-- Aksi --}}
                                    <td class="px-5 py-4">
                                        @if ($app->status === 'pending')
                                            {{-- PENDING: tombol approve + reject --}}
                                            <div class="flex flex-col gap-2 min-w-[140px]">
                                                <form method="POST"
                                                      action="{{ route('admin.organizer.approve', $app) }}"
                                                      onsubmit="return confirm('Setujui pengajuan dari {{ $app->user->name }}?')">
                                                    @csrf
                                                    <button type="submit"
                                                            class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg transition-colors">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                        Setujui
                                                    </button>
                                                </form>

                                                <button type="button"
                                                        onclick="document.getElementById('rf-{{ $app->id }}').classList.toggle('hidden')"
                                                        class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-white border border-red-300 hover:bg-red-50 text-red-600 text-xs font-medium rounded-lg transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                    Tolak
                                                </button>

                                                <div id="rf-{{ $app->id }}" class="hidden">
                                                    <form method="POST" action="{{ route('admin.organizer.reject', $app) }}">
                                                        @csrf
                                                        <textarea name="review_note" rows="2" required
                                                                  placeholder="Alasan penolakan..."
                                                                  class="w-full text-xs border border-gray-200 rounded-lg p-2 mb-1.5 focus:outline-none focus:ring-2 focus:ring-red-300 resize-none"></textarea>
                                                        <button type="submit"
                                                                class="w-full px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-lg transition-colors">
                                                            Konfirmasi Tolak
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>

                                        @else
                                            {{-- APPROVED / REJECTED: tombol suspend atau unsuspend --}}
                                            <div class="flex flex-col gap-2 min-w-[140px]">
                                                <span class="text-xs text-gray-400 italic">Sudah diproses</span>

                                                @if ($app->user->isSuspended())
                                                    {{-- Tombol Aktifkan Kembali --}}
                                                    <form method="POST"
                                                          action="{{ route('admin.organizer.unsuspend', $app) }}"
                                                          onsubmit="return confirm('Aktifkan kembali akun {{ $app->user->name }}?')">
                                                        @csrf
                                                        <button type="submit"
                                                                class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg transition-colors">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                            Aktifkan Kembali
                                                        </button>
                                                    </form>
                                                @else
                                                    {{-- Tombol Suspend --}}
                                                    <button type="button"
                                                            onclick="document.getElementById('sf-{{ $app->id }}').classList.toggle('hidden')"
                                                            class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-white border border-orange-300 hover:bg-orange-50 text-orange-600 text-xs font-medium rounded-lg transition-colors">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                        </svg>
                                                        Suspend
                                                    </button>

                                                    {{-- Form suspend inline --}}
                                                    <div id="sf-{{ $app->id }}" class="hidden">
                                                        <form method="POST" action="{{ route('admin.organizer.suspend', $app) }}">
                                                            @csrf
                                                            <textarea name="suspension_reason" rows="2" required
                                                                      placeholder="Alasan suspend (akan dilihat admin)..."
                                                                      class="w-full text-xs border border-orange-200 rounded-lg p-2 mb-1.5 focus:outline-none focus:ring-2 focus:ring-orange-300 resize-none"></textarea>
                                                            <button type="submit"
                                                                    class="w-full px-3 py-1.5 bg-orange-600 hover:bg-orange-700 text-white text-xs font-medium rounded-lg transition-colors">
                                                                Konfirmasi Suspend
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards --}}
                <div class="md:hidden divide-y divide-gray-100">
                    @foreach ($applications as $app)
                        <div class="p-4 space-y-3 {{ $app->user->isSuspended() ? 'bg-red-50/40' : '' }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full {{ $app->user->isSuspended() ? 'bg-red-100' : 'bg-indigo-100' }} flex items-center justify-center text-sm font-bold {{ $app->user->isSuspended() ? 'text-red-500' : 'text-indigo-600' }}">
                                        {{ strtoupper(substr($app->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900 text-sm">{{ $app->user->name }}</div>
                                        <div class="text-xs text-gray-400">{{ $app->company_name }}</div>
                                        @if ($app->user->isSuspended())
                                            <span class="text-xs text-red-600 font-semibold">SUSPENDED</span>
                                        @endif
                                    </div>
                                </div>
                                @if ($app->status === 'pending')
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700 font-medium">Pending</span>
                                @elseif ($app->status === 'approved')
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700 font-medium">Disetujui</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700 font-medium">Ditolak</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 leading-relaxed">{{ $app->reason }}</p>

                            @if ($app->status === 'pending')
                                <div class="flex gap-2">
                                    <form method="POST" action="{{ route('admin.organizer.approve', $app) }}"
                                          onsubmit="return confirm('Setujui?')" class="flex-1">
                                        @csrf
                                        <button class="w-full py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg">Setujui</button>
                                    </form>
                                    <button onclick="document.getElementById('mrf-{{ $app->id }}').classList.toggle('hidden')"
                                            class="flex-1 py-1.5 border border-red-300 text-red-600 hover:bg-red-50 text-xs font-medium rounded-lg">
                                        Tolak
                                    </button>
                                </div>
                                <div id="mrf-{{ $app->id }}" class="hidden">
                                    <form method="POST" action="{{ route('admin.organizer.reject', $app) }}">
                                        @csrf
                                        <textarea name="review_note" rows="2" required placeholder="Alasan penolakan..."
                                                  class="w-full text-xs border rounded-lg p-2 mb-1.5 focus:outline-none focus:ring-2 focus:ring-red-300 resize-none"></textarea>
                                        <button class="w-full py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-lg">Konfirmasi Tolak</button>
                                    </form>
                                </div>
                            @else
                                @if ($app->user->isSuspended())
                                    <form method="POST" action="{{ route('admin.organizer.unsuspend', $app) }}"
                                          onsubmit="return confirm('Aktifkan kembali?')">
                                        @csrf
                                        <button class="w-full py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg">Aktifkan Kembali</button>
                                    </form>
                                @else
                                    <button onclick="document.getElementById('msf-{{ $app->id }}').classList.toggle('hidden')"
                                            class="w-full py-1.5 border border-orange-300 text-orange-600 hover:bg-orange-50 text-xs font-medium rounded-lg">
                                        Suspend
                                    </button>
                                    <div id="msf-{{ $app->id }}" class="hidden">
                                        <form method="POST" action="{{ route('admin.organizer.suspend', $app) }}">
                                            @csrf
                                            <textarea name="suspension_reason" rows="2" required placeholder="Alasan suspend..."
                                                      class="w-full text-xs border border-orange-200 rounded-lg p-2 mb-1.5 focus:outline-none focus:ring-2 focus:ring-orange-300 resize-none"></textarea>
                                            <button class="w-full py-1.5 bg-orange-600 hover:bg-orange-700 text-white text-xs font-medium rounded-lg">Konfirmasi Suspend</button>
                                        </form>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>

                @if($applications->hasPages())
                    <div class="px-5 py-4 border-t border-gray-100">
                        {{ $applications->appends(['status' => $status])->links() }}
                    </div>
                @endif

            @endif
        </div>

    </div>
</x-admin-layout>
