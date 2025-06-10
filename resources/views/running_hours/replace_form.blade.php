@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto sm:px-6 lg:px-8 py-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">
        Ganti Sparepart Running Hour
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

    <div class="bg-white p-6 rounded shadow-md mb-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Sparepart Lama</h3>
        <p class="text-gray-700"><strong>Nama Barang:</strong> {{ $itemToReplace->nama_barang }}</p>
        <p class="text-gray-700"><strong>No Seri:</strong> {{ $itemToReplace->no_seri }}</p>
        <p class="text-gray-700"><strong>Kategori:</strong> {{ strtoupper($itemToReplace->kategori) }}</p>
        <p class="text-gray-700"><strong>Max Running Hour:</strong> {{ number_format($itemToReplace->max_running_hour) }} jam</p>
        <p class="text-gray-700"><strong>Jam Terpakai:</strong> {{ number_format($itemToReplace->jam_terpakai) }} jam</p>
        <p class="text-gray-700"><strong>Sisa Jam:</strong> {{ number_format($itemToReplace->jam_sisa) }} jam</p>
        <p class="text-gray-700"><strong>Status:</strong> <span class="px-2 py-1 text-xs font-semibold rounded-full 
            @php
                $statusClass = '';
                switch($itemToReplace->status) {
                    case 'Safe': $statusClass = 'bg-green-100 text-green-800'; break;
                    case 'Warning': $statusClass = 'bg-yellow-100 text-yellow-800'; break;
                    case 'Danger': $statusClass = 'bg-orange-100 text-orange-800'; break;
                    case 'Replaced': $statusClass = 'bg-blue-100 text-blue-800'; break; // Status baru
                    default: $statusClass = 'bg-gray-100 text-gray-800'; break;
                }
            @endphp
            {{ $statusClass }}">{{ $itemToReplace->status }}</span>
        </p>
    </div>

    <form action="{{ route('running_hours.replace_sparepart', $itemToReplace->id) }}" method="POST" class="space-y-4 bg-white p-6 rounded shadow-md">
        @csrf
        @method('PUT') {{-- Menggunakan PUT untuk update --}}

        <h3 class="text-xl font-semibold text-gray-800 mb-4">Pilih Sparepart Pengganti</h3>

        <div>
            <label for="new_sparepart_id" class="block text-sm font-medium text-gray-700">Nama Sparepart Baru (Kategori: {{ strtoupper($itemToReplace->kategori) }})</label>
            <select name="new_sparepart_id" id="new_sparepart_id" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">-- Pilih Sparepart Pengganti --</option>
                @forelse($availableSpareparts as $sparepart)
                    <option value="{{ $sparepart->id }}"
                            data-no-seri="{{ $sparepart->no_seri }}"
                            data-mrh="{{ $sparepart->mrh }}">
                        {{ $sparepart->nama_barang }} (No Seri: {{ $sparepart->no_seri }}) - Stok: {{ $sparepart->jumlah }}
                    </option>
                @empty
                    <option value="" disabled>Tidak ada sparepart pengganti tersedia untuk kategori ini.</option>
                @endforelse
            </select>
            @error('new_sparepart_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="new_no_seri" class="block text-sm font-medium text-gray-700">No Seri Sparepart Baru (Otomatis)</label>
            <input type="text" id="new_no_seri" readonly
                   class="mt-1 block w-full border-gray-300 bg-gray-100 rounded-md shadow-sm">
        </div>

        <div>
            <label for="new_max_running_hour" class="block text-sm font-medium text-gray-700">Max Running Hour Baru (Otomatis)</label>
            <input type="text" id="new_max_running_hour" readonly
                   class="mt-1 block w-full border-gray-300 bg-gray-100 rounded-md shadow-sm">
        </div>

        <div>
            <label for="tanggal_mulai_baru" class="block text-sm font-medium text-gray-700">Tanggal Mulai Baru</label>
            <input type="date" name="tanggal_mulai_baru" id="tanggal_mulai_baru" required
                   value="{{ old('tanggal_mulai_baru', date('Y-m-d')) }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @error('tanggal_mulai_baru')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="keterangan_penggantian" class="block text-sm font-medium text-gray-700">Keterangan Penggantian (Opsional)</label>
            <textarea name="keterangan_penggantian" id="keterangan_penggantian" rows="3"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('keterangan_penggantian') }}</textarea>
            @error('keterangan_penggantian')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-6 text-right">
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Lakukan Penggantian
            </button>
            <a href="{{ route('running_hours.index') }}"
               class="ml-4 text-gray-600 hover:text-gray-800">Batal</a>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const newSparepartSelect = document.getElementById('new_sparepart_id');
        const newNoSeriInput = document.getElementById('new_no_seri');
        const newMaxRunningHourInput = document.getElementById('new_max_running_hour');

        if (newSparepartSelect) {
            newSparepartSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption && selectedOption.value) {
                    newNoSeriInput.value = selectedOption.dataset.noSeri || '';
                    newMaxRunningHourInput.value = selectedOption.dataset.mrh || '';
                } else {
                    newNoSeriInput.value = '';
                    newMaxRunningHourInput.value = '';
                }
            });
        }
    });
</script>
@endsection