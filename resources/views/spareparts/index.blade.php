@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6">
    @if(session('success'))
    <div class="mb-4">
        <div class="flex items-center justify-between p-4 rounded-md bg-green-100 border border-green-300 text-green-800 shadow-sm">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M5 13l4 4L19 7"/>
                </svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="ml-4 text-green-700 hover:text-green-900">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                          d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                          clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
@endif
    <div class="bg-white p-6 shadow-md rounded">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">
            Data Sparepart - 
            @switch($type)
                @case('ae')
                    Auxiliary Engine
                    @break
                @case('me')
                    Main Engine
                    @break
                @case('pe')
                    Pump Equipment
                    @break
                @default
                    Tidak diketahui
            @endswitch
        </h2>

        @auth {{-- Pastikan user login dulu --}}
            @if(Auth::user()->isAdmin()) {{-- Hanya tampilkan jika user adalah admin --}}
                <a href="{{ route('spareparts.create', $type) }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-green-700"> + Tambah Sparepart </a>
            @endif
        @endauth
        <a href="{{ route('request.sparepart.create', $type) }}"
       class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
        Request Sparepart
    </a>
    </div>
    


    <div class="bg-white shadow-md rounded-lg overflow-x-auto">
        <table class="min-w-full text-sm text-left text-gray-600">
            <thead class="bg-gray-100 text-gray-700 uppercase">
                <tr>
                    <th class="px-4 py-2">No</th>
                    <th class="px-4 py-2">Nama Barang</th>
                    <th class="px-4 py-2">No Seri</th>
                    <th class="px-4 py-2">Max Running Hour</th>
                    <th class="px-4 py-2">Jumlah</th>
                    <th class="px-4 py-2">Harga Satuan (IDR)</th>
                    <th class="px-4 py-2">Tanggal Masuk</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($spareparts as $index => $item)
                <tr>
                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                    <td class="px-4 py-2">{{ $item->nama_barang }}</td>
                    <td class="px-4 py-2">{{ $item->no_seri }}</td>
                    <td class="px-4 py-2">{{ $item->mrh ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $item->jumlah }}</td>
                    <td class="px-4 py-2">{{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td class="px-4 py-2">{{ $item->tgl_masuk }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-2 text-center text-gray-500">Tidak ada data.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
