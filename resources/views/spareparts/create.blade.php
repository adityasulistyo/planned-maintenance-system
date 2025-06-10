@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto sm:px-6 lg:px-8 py-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">
        Tambah Sparepart - 
        @switch($type)
            @case('ae') Auxiliary Engine @break
            @case('me') Main Engine @break
            @case('pe') Pump Equipment @break
            @default Tidak diketahui
        @endswitch
    </h2>

    @if($errors->any())
        <div class="mb-4 text-red-600">
            <ul class="list-disc pl-6">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('spareparts.store', $type) }}" method="POST" class="space-y-4">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="nama_barang" class="block text-sm font-medium text-gray-700">Nama Barang</label>
                <input type="text" name="nama_barang" id="nama_barang" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <div>
                <label for="no_seri" class="block text-sm font-medium text-gray-700">No Seri</label>
                <input type="text" name="no_seri" id="no_seri" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <div>
                <label for="max_running_hour" class="block text-sm font-medium text-gray-700">Max Running Hour</label>
                <input type="number" name="max_running_hour" id="max_running_hour" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <div>
                <label for="jumlah" class="block text-sm font-medium text-gray-700">Jumlah</label>
                <input type="number" name="jumlah" id="jumlah" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <div>
                <label for="harga_satuan" class="block text-sm font-medium text-gray-700">Harga Satuan (IDR)</label>
                <input type="number" name="harga_satuan" id="harga_satuan" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <div>
                <label for="tanggal_masuk" class="block text-sm font-medium text-gray-700">Tanggal Masuk</label>
                <input type="date" name="tanggal_masuk" id="tanggal_masuk" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
        </div>

        <div class="mt-6">
            <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Simpan
            </button>
            <a href="{{ route('spareparts.index', $type) }}"
               class="ml-4 text-gray-600 hover:text-gray-800">Kembali</a>
        </div>
    </form>
</div>
@endsection
