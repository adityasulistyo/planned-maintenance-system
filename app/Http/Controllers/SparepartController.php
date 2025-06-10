<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // <-- Tambahkan ini untuk otorisasi
use Carbon\Carbon; // <-- Tambahkan ini untuk tanggal

class SparepartController extends Controller
{
    // Ini tidak lagi diperlukan jika Anda menggunakan DB::table() secara langsung
    // private $sparepartModel;
    // public function __construct()
    // {
    //     $this->sparepartModel = new Sparepart();
    // }

    public function index($type)
    {
        $tableName = $this->resolveTable($type);
        $spareparts = DB::table($tableName)->get();

        return view('spareparts.index', [
            'spareparts' => $spareparts,
            'type' => $type
        ]);
    }

    // Mengganti nama method 'request' menjadi 'createRequest' agar lebih jelas (optional, tapi disarankan)
    public function createRequest($type)
    {
        // Validasi tipe kategori yang diizinkan
        $allowedTypes = ['ae', 'me', 'pe'];
        if (!in_array($type, $allowedTypes)) {
            abort(404, 'Kategori tidak valid.');
        }
        return view('spareparts.request', compact('type'));
    }

    public function submitRequest(Request $request, $type)
    {
        // Validasi input
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        // Validasi tipe kategori yang diizinkan
        $allowedTypes = ['ae', 'me', 'pe'];
        if (!in_array($type, $allowedTypes)) {
            return back()->with('error', 'Kategori permintaan tidak valid.');
        }

        // Simpan ke database sparepart_requests
        DB::table('sparepart_requests')->insert([
            'nama_barang' => $request->nama_barang,
            'quantity' => $request->jumlah, // Pastikan kolom 'quantity' ada di DB
            'keterangan' => $request->keterangan,
            'type' => $type,
            'requested_at' => Carbon::now(), // Menggunakan Carbon untuk waktu saat ini
            'status' => 'pending', // Menambahkan kolom status default
        ]);

        // Tambahkan log ke tabel 'reports'
        DB::table('reports')->insert([
            'action' => 'Kirim Permintaan Sparepart',
            'description' => 'Pengguna ' . (Auth::check() ? Auth::user()->name : 'Guest') . ' mengirim permintaan untuk ' . $request->nama_barang . ' (' . $request->jumlah . ' unit) kategori ' . strtoupper($type) . '.',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return redirect()->route('request.sparepart.create', $type)->with('success', 'Permintaan sparepart berhasil dikirim.');
    }

    public function requestList()
    {
        $requests = DB::table('sparepart_requests')->orderBy('requested_at', 'desc')->get();
        return view('spareparts.request_list', compact('requests'));
    }

    // --- METODE BARU UNTUK UPDATE STATUS PERMINTAAN ---
    public function updateStatus(Request $request, $id, $status)
    {
        // Otorisasi admin akan ditangani oleh middleware 'admin' di route,
        // tapi double check di controller juga bagus.
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect('/dashboard')->with('error', 'Akses Ditolak: Anda tidak memiliki izin Admin untuk melakukan aksi ini.');
        }

        $sparepartRequest = DB::table('sparepart_requests')->find($id);

        if (!$sparepartRequest) {
            return back()->with('error', 'Permintaan sparepart tidak ditemukan.');
        }

        $allowedStatuses = ['approved', 'rejected'];
        if (!in_array($status, $allowedStatuses)) {
            return back()->with('error', 'Status tidak valid.');
        }

        // Hanya izinkan perubahan jika status saat ini 'pending'
        if ($sparepartRequest->status != 'pending') {
            return back()->with('error', 'Permintaan ini sudah ' . $sparepartRequest->status . ' dan tidak dapat diubah.');
        }

        $oldStatus = $sparepartRequest->status;

        // Update status di database
        DB::table('sparepart_requests')
            ->where('id', $id)
            ->update([
                'status' => $status,
                'updated_at' => Carbon::now() // Pastikan ada kolom updated_at di tabel
            ]);

        // Tambahkan log ke tabel 'reports'
        DB::table('reports')->insert([
            'action' => 'Update Status Permintaan',
            'description' => 'Status permintaan sparepart ' . $sparepartRequest->nama_barang . ' (ID: ' . $sparepartRequest->id . ') diubah dari "' . $oldStatus . '" menjadi "' . $status . '" oleh Admin ' . Auth::user()->name . '.',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return back()->with('success', 'Status permintaan sparepart berhasil diubah menjadi ' . $status . '.');
    }
    // --- AKHIR METODE BARU ---


    public function create($type)
    {
        return view('spareparts.create', compact('type'));
    }

    public function store(Request $request, $type)
    {
        $tableName = $this->resolveTable($type);

        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'no_seri' => 'required|string|max:100',
            'max_running_hour' => 'required|integer|min:0',
            'jumlah' => 'required|integer|min:0',
            'harga_satuan' => 'required|numeric',
            'tanggal_masuk' => 'required|date',
        ]);

        $data = [
            'nama_barang' => $validated['nama_barang'],
            'no_seri' => $validated['no_seri'],
            'mrh' => $validated['max_running_hour'],
            'jumlah' => $validated['jumlah'],
            'harga' => $validated['harga_satuan'], // Pastikan nama kolom 'harga' sesuai
            'tgl_masuk' => $validated['tanggal_masuk'], // Pastikan nama kolom 'tgl_masuk' sesuai
            'created_at' => Carbon::now(), // Tambahkan ini jika tabel Anda memiliki kolom timestamps
            'updated_at' => Carbon::now(), // Tambahkan ini jika tabel Anda memiliki kolom timestamps
        ];

        DB::table($tableName)->insert($data);

        // Tambahkan log untuk penambahan sparepart inventaris
        DB::table('reports')->insert([
            'action' => 'Tambah Inventaris',
            'description' => 'Admin ' . (Auth::check() ? Auth::user()->name : 'Unknown') . ' menambahkan sparepart ' . $validated['nama_barang'] . ' (No Seri: ' . $validated['no_seri'] . ') ke inventaris ' . strtoupper($type) . '.',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return redirect()->route('spareparts.index', $type)->with('success', 'Sparepart berhasil ditambahkan.');
    }

    private function resolveTable($type)
    {
        $mapping = [
            'ae' => 'spareparts_ae',
            'me' => 'spareparts_me',
            'pe' => 'spareparts_pe',
        ];

        if (!array_key_exists($type, $mapping)) {
            abort(404, 'Tipe sparepart tidak dikenali');
        }

        return $mapping[$type];
    }
}