@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Running Hours Spareparts</h2>

    @foreach (['AE' => $ae, 'ME' => $me, 'PE' => $pe] as $label => $spareparts)
        <div class="mb-10">
            <h3 class="text-xl font-semibold mb-3 text-gray-700">Spareparts {{ $label }}</h3>
            <div class="overflow-x-auto bg-white rounded shadow">
                <table class="min-w-full table-auto text-sm text-left text-gray-700">
                    <thead class="bg-gray-100 uppercase text-xs text-gray-600">
                        <tr>
                            <th class="px-4 py-2">No</th>
                            <th class="px-4 py-2">Nama Barang</th>
                            <th class="px-4 py-2">No Seri</th>
                            <th class="px-4 py-2">Max Running Hour</th>
                            <th class="px-4 py-2">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($spareparts as $i => $item)
                            <tr>
                                <td class="px-4 py-2">{{ $i + 1 }}</td>
                                <td class="px-4 py-2">{{ $item->nama_barang }}</td>
                                <td class="px-4 py-2">{{ $item->no_seri }}</td>
                                <td class="px-4 py-2">{{ $item->mrh }}</td>
                                <td class="px-4 py-2">{{ $item->jumlah }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-2 text-center text-gray-500">Tidak ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div>
@endsection
