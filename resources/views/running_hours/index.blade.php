@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">Running Hours Management</h1>
            <p class="mt-2 text-gray-600">Monitor dan kelola running hours sparepart untuk maintenance tracking</p>
        </div>
    </header>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Form Tambah Data --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Tambah Data Running Hours</h2>

                    <form method="POST" action="{{ route('running_hours.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                            <div>
                                <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">Pilih Kategori</label>
                                <select name="kategori" id="kategori"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="ae" {{ old('kategori') == 'ae' ? 'selected' : '' }}>AE - Auxiliary Engine</option>
                                    <option value="me" {{ old('kategori') == 'me' ? 'selected' : '' }}>ME - Main Engine</option>
                                    <option value="pe" {{ old('kategori') == 'pe' ? 'selected' : '' }}>PE - Pump Engine</option>
                                </select>

                                @error('kategori')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="sparepart_id" class="block text-sm font-medium text-gray-700 mb-2">Nama Sparepart</label>
                                <select name="sparepart_id" id="sparepart_id"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        required {{ old('kategori') ? '' : 'disabled' }}>
                                    <option value="">Pilih sparepart</option>
                                </select>

                                @error('sparepart_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="no_seri" class="block text-sm font-medium text-gray-700 mb-2">No Seri</label>
                                <input name="no_seri" id="no_seri" type="text"
                                       value="{{ old('no_seri') }}"
                                       placeholder="Masukkan no seri"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                       readonly />
                                @error('no_seri')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="max_running_hour" class="block text-sm font-medium text-gray-700 mb-2">Max Running Hour</label>
                                <input name="max_running_hour" id="max_running_hour" type="number" min="1"
                                       value="{{ old('max_running_hour') }}"
                                       placeholder="Contoh: 10000"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                       readonly />
                                @error('max_running_hour')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                                <input name="tanggal_mulai" id="tanggal_mulai" type="date"
                                       value="{{ old('tanggal_mulai', date('Y-m-d')) }}"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                       required />
                                @error('tanggal_mulai')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Tambah Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Notifikasi --}}
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 rounded-md p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Tabel Data --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800">Data Running Hours</h2>
                            <p class="text-sm text-gray-600 mt-1">Total data: {{ isset($data) ? $data->count() : 0 }} item</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Sparepart</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No Seri</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Mulai</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Running Hours</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Maintenance</th> {{-- Pindahkan Kolom Maintenance ke Sini --}}
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @if(isset($data) && $data->count() > 0)
                                    @foreach ($data as $index => $item)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $kategoriConfig = [
                                                        'ae' => ['name' => 'Auxiliary Engine', 'class' => 'bg-blue-100 text-blue-800'],
                                                        'me' => ['name' => 'Main Engine', 'class' => 'bg-green-100 text-green-800'],
                                                        'pe' => ['name' => 'Pump Engine', 'class' => 'bg-purple-100 text-purple-800']
                                                    ];
                                                    $config = $kategoriConfig[$item->kategori] ?? ['name' => strtoupper($item->kategori), 'class' => 'bg-gray-100 text-gray-800'];
                                                @endphp
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $config['class'] }}">
                                                    {{ $config['name'] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->nama_barang ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->no_seri ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') : '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <div class="flex flex-col">
                                                    <span class="font-medium">{{ $item->jam_terpakai ?? '0' }} jam</span>
                                                    <span class="text-xs text-gray-500">
                                                        Max: {{ number_format($item->max_running_hour ?? 0) }} jam
                                                    </span>
                                                    @if(isset($item->jam_sisa))
                                                        <span class="text-xs text-gray-500">Sisa: {{ number_format($item->jam_sisa) }} jam</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $status = $item->status ?? 'Unknown';
                                                    $statusConfig = [
                                                        'Safe' => 'bg-green-100 text-green-800',
                                                        'Warning' => 'bg-yellow-100 text-yellow-800',
                                                        'Danger' => 'bg-orange-100 text-orange-800',
                                                        'Replace' => 'bg-red-100 text-red-800',
                                                        'Unknown' => 'bg-gray-100 text-gray-800'
                                                    ];
                                                    $statusClass = $statusConfig[$status] ?? $statusConfig['Unknown'];
                                                @endphp
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                                    {{ $status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->maintenance_type ?? '-' }}</td> {{-- Pindahkan Sel Maintenance ke Sini --}}
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" class="px-6 py-12 text-center"> {{-- colspan disesuaikan --}}
                                            <div class="flex flex-col items-center">
                                                <svg class="h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                </svg>
                                                <p class="text-gray-500 text-lg font-medium">Belum ada data running hours</p>
                                                <p class="text-gray-400 text-sm mt-1">Tambahkan data pertama dengan mengisi form di atas</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const sparepartsData = @json($sparepartsData ?? []);

    console.log('Spareparts Data (JS):', sparepartsData); // Debug log: Pastikan data spareparts sampai ke JS

    function loadSpareparts() {
        const kategori = document.getElementById('kategori').value;
        const sparepartSelect = document.getElementById('sparepart_id');
        const noSeriInput = document.getElementById('no_seri');
        const maxRunningHourInput = document.getElementById('max_running_hour');

        console.log('Selected kategori (loadSpareparts):', kategori); // Debug log

        // Reset semua field
        sparepartSelect.innerHTML = '<option value="">Pilih Sparepart</option>';
        noSeriInput.value = '';
        maxRunningHourInput.value = '';

        if (kategori && sparepartsData[kategori] && sparepartsData[kategori].length > 0) {
            sparepartSelect.disabled = false;

            console.log('Loading spareparts for kategori:', kategori, sparepartsData[kategori]); // Debug log

            sparepartsData[kategori].forEach(sparepart => {
                const option = document.createElement('option');
                option.value = sparepart.id;
                option.textContent = sparepart.nama_barang;
                // Pastikan dataset diisi dengan benar
                option.dataset.noSeri = sparepart.no_seri || '';
                option.dataset.mrh = sparepart.mrh || '';
                sparepartSelect.appendChild(option);
            });
        } else {
            sparepartSelect.disabled = true;
            console.log('No spareparts found for kategori:', kategori); // Debug log
        }
    }

    function fillSparepartDetails() {
        const sparepartSelect = document.getElementById('sparepart_id');
        const selected = sparepartSelect.options[sparepartSelect.selectedIndex];
        const noSeriInput = document.getElementById('no_seri');
        const maxRunningHourInput = document.getElementById('max_running_hour');

        if (selected && selected.value) {
            console.log('Selected sparepart (fillSparepartDetails):', selected.textContent, selected.dataset); // Debug log
            noSeriInput.value = selected.dataset.noSeri || '';
            maxRunningHourInput.value = selected.dataset.mrh || '';
        } else {
            noSeriInput.value = '';
            maxRunningHourInput.value = '';
        }
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        const kategoriSelect = document.getElementById('kategori');
        const sparepartSelect = document.getElementById('sparepart_id');

        // Add event listeners
        if (kategoriSelect) {
            kategoriSelect.addEventListener('change', loadSpareparts);
        }

        if (sparepartSelect) {
            sparepartSelect.addEventListener('change', fillSparepartDetails);
        }

        // Restore previous selections if any (untuk validation errors)
        const currentKategori = kategoriSelect?.value;
        const oldSparepartId = '{{ old("sparepart_id") }}'; // Ambil old sparepart_id

        if (currentKategori) {
            loadSpareparts(); // Load spareparts for the old category

            // Restore sparepart selection after a short delay
            setTimeout(() => {
                if (oldSparepartId && sparepartSelect) {
                    sparepartSelect.value = oldSparepartId;
                    fillSparepartDetails(); // Fill details for the restored sparepart
                }
            }, 100);
        }
    });
</script>

@endsection