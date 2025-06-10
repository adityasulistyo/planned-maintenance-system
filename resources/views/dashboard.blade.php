@extends('layouts.app')

@php use Illuminate\Support\Str; @endphp

@section('content')
<div class="min-h-screen bg-gray-100">
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
            <p class="mt-2 text-gray-600">Ringkasan Sistem Pemeliharaan Terencana Anda.</p>
        </div>
    </header>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-stretch">

                {{-- Card Inventaris --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col">
                    <div class="p-6 border-b border-gray-200 flex-grow flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 text-blue-600 rounded-md p-3">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14v6m-3-3h6M6 10h2m-2 4h2m-2 4h2m10-10h2m-2 4h2m-2 4h2M5 18H3a2 2 0 01-2-2V8a2 2 0 012-2h3.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293H21a2 2 0 012 2v8a2 2 0 01-2 2h-2"></path></svg>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium text-gray-500">Total Inventaris</dt>
                            <dd class="text-lg font-bold text-gray-900">{{ number_format($totalInventaris) }}</dd>
                            <p class="text-xs text-gray-500">AE: {{ number_format($totalAE) }}, ME: {{ number_format($totalME) }}, PE: {{ number_format($totalPE) }}</p>
                        </div>
                    </div>
                </div>

                {{-- Card Running Hours Status --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col">
                    <div class="p-6 border-b border-gray-200 flex-grow flex items-center">
                        <div class="flex-shrink-0 bg-yellow-100 text-yellow-600 rounded-md p-3">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium text-gray-500">Running Hours Status</dt> {{-- Ubah Judul --}}
                            <dd class="text-lg font-bold text-gray-900">{{ number_format($totalRunningHours) }}</dd>
                            <p class="text-xs text-gray-500">
                                Warning: {{ number_format($runningHoursWarning) }},
                                Danger: {{ number_format($runningHoursDanger) }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Card Maintenance Overview (BARU) --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col">
                    <div class="p-6 border-b border-gray-200 flex-grow flex items-center">
                        <div class="flex-shrink-0 bg-indigo-100 text-indigo-600 rounded-md p-3">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.587.36 1.36.577 2.18.577z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium text-gray-500">Maintenance Overview</dt>
                            <dd class="text-lg font-bold text-gray-900">{{ number_format($totalRunningHours) }} item</dd> {{-- Total item yang masuk kategori maintenance --}}
                            <p class="text-xs text-gray-500">
                                Harian: {{ number_format($maintenanceCounts['Harian'] ?? 0) }},
                                Mingguan: {{ number_format($maintenanceCounts['Mingguan'] ?? 0) }}
                            </p>
                            <p class="text-xs text-gray-500">
                                Bulanan: {{ number_format($maintenanceCounts['Bulanan'] ?? 0) }},
                                Tahunan: {{ number_format($maintenanceCounts['Tahunan'] ?? 0) }}
                            </p>
                            @if(isset($maintenanceCounts['Lain-lain']) && $maintenanceCounts['Lain-lain'] > 0)
                            <p class="text-xs text-gray-500">
                                Lain-lain: {{ number_format($maintenanceCounts['Lain-lain'] ?? 0) }}
                            </p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Card Requests --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col">
                    <div class="p-6 border-b border-gray-200 flex-grow flex items-center">
                        <div class="flex-shrink-0 bg-purple-100 text-purple-600 rounded-md p-3">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium text-gray-500">Total Permintaan Sparepart</dt>
                            <dd class="text-lg font-bold text-gray-900">{{ number_format($totalRequests) }}</dd>
                            <p class="text-xs text-gray-500">Pending: {{ number_format($pendingRequests) }}, Approved: {{ number_format($approvedRequests) }}</p>
                        </div>
                    </div>
                </div>

                {{-- Card Reports (Jika Anda ingin 5 card dalam satu baris, pindahkan Card Reports ke bawah jika Anda memiliki lebih dari 4 card) --}}
                {{-- Atau, jika Anda hanya ingin 4 card utama, Anda bisa menyingkirkan atau menyembunyikan card reports jika dashboard terlalu padat --}}
                {{-- Namun, saya akan letakkan di sini untuk saat ini agar tetap 4 card di baris pertama --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col">
                    <div class="p-6 border-b border-gray-200 flex-grow flex items-center">
                        <div class="flex-shrink-0 bg-green-100 text-green-600 rounded-md p-3">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium text-gray-500">Total Laporan Aktivitas</dt>
                            <dd class="text-lg font-bold text-gray-900">{{ number_format($totalReports) }}</dd>
                            <p class="text-xs text-gray-500">Lihat detail di halaman <a href="{{ route('reports.index') }}" class="text-blue-500 hover:underline">Laporan</a></p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Pop-up Warning/Danger (Di luar grid card, agar tidak mengganggu layout) --}}
            @auth
                @if($criticalRunningHours->isNotEmpty())
                    <div id="warningModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
                        <div class="relative p-8 border w-3/4 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white">
                            <div class="text-center">
                                <h3 class="text-2xl font-semibold text-red-600 mb-4">Perhatian! Sparepart Membutuhkan Perhatian!</h3>
                                <p class="text-gray-700 mb-6">Beberapa sparepart mendekati atau telah melampaui batas running hour maksimalnya.</p>
                                <ul class="list-disc text-left mx-auto max-h-48 overflow-y-auto mb-6 p-2 border rounded">
                                    @foreach($criticalRunningHours as $item)
                                        <li class="mb-1 text-sm {{ $item->calculated_status == 'Danger' ? 'text-orange-700 font-bold' : 'text-yellow-700' }}">
                                            <strong>{{ $item->nama_barang }}</strong> (No Seri: {{ $item->no_seri }}) - Status: <span class="font-bold">{{ $item->calculated_status }}</span> (Sisa: {{ number_format($item->jam_sisa) }} jam)
                                            @if(Auth::user()->isAdmin())
                                                <form action="{{ route('running_hours.show_replace_form', $item->id) }}" method="GET" class="inline-block ml-3">
                                                    <button type="submit" class="text-blue-600 hover:text-blue-800 text-xs font-semibold">Ganti Sekarang</button>
                                                </form>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="flex justify-center">
                                    <button id="closeModal" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const modal = document.getElementById('warningModal');
                            const closeModalBtn = document.getElementById('closeModal');

                            if (modal) {
                                closeModalBtn.addEventListener('click', function() {
                                    modal.style.display = 'none';
                                });
                            }
                        });
                    </script>
                @endif
            @endauth

            {{-- Bagian Laporan Terbaru (Opsional, di bawah cards) --}}
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">5 Laporan Terbaru</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($latestReports as $report)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $report->action }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($report->description, 70) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $report->created_at->format('d/m/Y H:i') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">Tidak ada laporan terbaru.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection