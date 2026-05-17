<x-admin-layout>
    <x-slot name="breadcrumb">Beranda</x-slot>

    <div class="p-6 space-y-6">

        {{-- Welcome Banner --}}
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-800 rounded-2xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Selamat datang, {{ Auth::user()->name }}! 👋</h1>
                    <p class="text-indigo-200 mt-1 text-sm">Kelola pengajuan organizer dan pantau aktivitas platform.</p>
                </div>
                <div class="hidden sm:block">
                    <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">

            {{-- Pending --}}
            <a href="{{ route('admin.organizer.index', ['status' => 'pending']) }}"
               class="bg-white rounded-xl p-5 border border-gray-200 hover:shadow-md hover:border-yellow-300 transition-all group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center group-hover:bg-yellow-200 transition-colors">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    @if($stats['pending'] > 0)
                        <span class="w-2.5 h-2.5 rounded-full bg-yellow-400 animate-pulse"></span>
                    @endif
                </div>
                <div class="text-3xl font-bold text-gray-900">{{ $stats['pending'] }}</div>
                <div class="text-sm text-gray-500 mt-1">Menunggu Review</div>
            </a>

            {{-- Approved --}}
            <a href="{{ route('admin.organizer.index', ['status' => 'approved']) }}"
               class="bg-white rounded-xl p-5 border border-gray-200 hover:shadow-md hover:border-green-300 transition-all group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center group-hover:bg-green-200 transition-colors">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900">{{ $stats['approved'] }}</div>
                <div class="text-sm text-gray-500 mt-1">Disetujui</div>
            </a>

            {{-- Rejected --}}
            <a href="{{ route('admin.organizer.index', ['status' => 'rejected']) }}"
               class="bg-white rounded-xl p-5 border border-gray-200 hover:shadow-md hover:border-red-300 transition-all group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center group-hover:bg-red-200 transition-colors">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900">{{ $stats['rejected'] }}</div>
                <div class="text-sm text-gray-500 mt-1">Ditolak</div>
            </a>

            {{-- Total Pengajuan --}}
            <div class="bg-white rounded-xl p-5 border border-gray-200">
                <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</div>
                <div class="text-sm text-gray-500 mt-1">Total Pengajuan</div>
            </div>

            {{-- Total Users --}}
            <div class="bg-white rounded-xl p-5 border border-gray-200">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="text-3xl font-bold text-gray-900">{{ $stats['users'] }}</div>
                <div class="text-sm text-gray-500 mt-1">User Terdaftar</div>
            </div>

            {{-- Total Organizers --}}
            <div class="bg-white rounded-xl p-5 border border-gray-200">
                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div class="text-3xl font-bold text-gray-900">{{ $stats['organizers'] }}</div>
                <div class="text-sm text-gray-500 mt-1">Organizer Aktif</div>
            </div>

        </div>

        {{-- Recent Applications --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-800">Pengajuan Terbaru</h2>
                <a href="{{ route('admin.organizer.index') }}"
                   class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                    Lihat semua →
                </a>
            </div>

            @if($recentApplications->isEmpty())
                <div class="px-6 py-10 text-center text-gray-400 text-sm">
                    Belum ada pengajuan organizer.
                </div>
            @else
                <div class="divide-y divide-gray-50">
                    @foreach($recentApplications as $app)
                        <div class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50 transition-colors">
                            {{-- Avatar --}}
                            <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center text-sm font-bold text-indigo-600 flex-shrink-0">
                                {{ strtoupper(substr($app->user->name, 0, 1)) }}
                            </div>
                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-gray-900 truncate">{{ $app->user->name }}</div>
                                <div class="text-xs text-gray-400 truncate">{{ $app->company_name }}</div>
                            </div>
                            {{-- Status --}}
                            <div class="flex-shrink-0">
                                @if($app->status === 'pending')
                                    <span class="px-2.5 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700 font-medium">Pending</span>
                                @elseif($app->status === 'approved')
                                    <span class="px-2.5 py-1 text-xs rounded-full bg-green-100 text-green-700 font-medium">Disetujui</span>
                                @else
                                    <span class="px-2.5 py-1 text-xs rounded-full bg-red-100 text-red-700 font-medium">Ditolak</span>
                                @endif
                            </div>
                            {{-- Date --}}
                            <div class="text-xs text-gray-400 flex-shrink-0 hidden sm:block">
                                {{ $app->created_at->format('d M Y') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</x-admin-layout>
