@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">Daftar Permintaan Sparepart</h1>
            <p class="mt-2 text-gray-600">Daftar semua permintaan sparepart yang masuk.</p>
        </div>
    </header>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Pesan Sukses atau Error --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Request</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    @auth
                                        {{-- Kolom Aksi hanya terlihat jika user adalah admin --}}
                                        @if(Auth::user()->isAdmin())
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                        @endif
                                    @endauth
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($requests as $index => $request)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $request->nama_barang }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $request->quantity }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $request->keterangan }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ strtoupper($request->type) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($request->requested_at)->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @php
                                                $statusClass = '';
                                                switch($request->status) {
                                                    case 'pending': $statusClass = 'bg-yellow-100 text-yellow-800'; break;
                                                    case 'approved': $statusClass = 'bg-green-100 text-green-800'; break;
                                                    case 'rejected': $statusClass = 'bg-red-100 text-red-800'; break;
                                                    default: $statusClass = 'bg-gray-100 text-gray-800'; break;
                                                }
                                            @endphp
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </td>
                                        @auth
                                            @if(Auth::user()->isAdmin())
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    @if($request->status == 'pending')
                                                        {{-- Form untuk Approve --}}
                                                        <form action="{{ route('request.sparepart.updateStatus', ['id' => $request->id, 'status' => 'approved']) }}" method="POST" class="inline-block">
                                                            @csrf
                                                            @method('PUT') {{-- Gunakan method PUT untuk update --}}
                                                            <button type="submit" class="text-green-600 hover:text-green-900 mr-2" title="Approve">
                                                                Approve
                                                            </button>
                                                        </form>
                                                        {{-- Form untuk Reject --}}
                                                        <form action="{{ route('request.sparepart.updateStatus', ['id' => $request->id, 'status' => 'rejected']) }}" method="POST" class="inline-block">
                                                            @csrf
                                                            @method('PUT') {{-- Gunakan method PUT untuk update --}}
                                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Reject">
                                                                Reject
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="text-gray-500 text-xs">Selesai</span>
                                                    @endif
                                                </td>
                                            @endif
                                        @endauth
                                    </tr>
                                @empty
                                    <tr>
                                        {{-- colspan disesuaikan dengan jumlah kolom (8 kolom) --}}
                                        <td colspan="8" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center">
                                                <svg class="h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                </svg>
                                                <p class="text-gray-500 text-lg font-medium">Belum ada permintaan sparepart</p>
                                                <p class="text-gray-400 text-sm mt-1">Buat permintaan baru melalui form.</p>
                                            </div>
                                        </td>
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