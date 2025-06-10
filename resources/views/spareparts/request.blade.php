@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto sm:px-6 lg:px-8 py-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">
        Request Sparepart - 
        @switch($type)
            @case('ae') Auxiliary Engine @break
            @case('me') Main Engine @break
            @case('pe') Pump Equipment @break
            @default Tidak diketahui
        @endswitch
    </h2>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('request.sparepart.submit', $type) }}" method="POST" class="space-y-4 bg-white p-6 rounded shadow-md">
        @csrf

        <div>
            <label class="block text-gray-700 font-medium mb-1">Nama Sparepart</label>
            <input type="text" name="nama_barang" class="w-full border-gray-300 rounded-md shadow-sm" required>
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-1">Jumlah yang Dibutuhkan</label>
            <input type="number" name="jumlah" class="w-full border-gray-300 rounded-md shadow-sm" required min="1">
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-1">Keterangan</label>
            <textarea name="keterangan" rows="3" class="w-full border-gray-300 rounded-md shadow-sm"></textarea>
        </div>

        <div class="text-right">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                Kirim Request
            </button>
        </div>
    </form>
</div>
@endsection
