<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RunningHour;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Sparepart; // Pastikan ini diimpor untuk mengambil data sparepart
use App\Models\Report; // Pastikan ini diimpor untuk logging
use Illuminate\Support\Facades\Auth;

class RunningHoursController extends Controller
{
    public function index()
    {
        $data = RunningHour::all()->map(function ($item) {
            // jam_terpakai: Selisih jam antara tanggal_mulai dan waktu saat ini (now())
            $jam_terpakai_raw = Carbon::parse($item->tanggal_mulai)->diffInHours(now());

            // Pastikan max_running_hour adalah integer untuk perhitungan
            $max_running_hour_int = (int) $item->max_running_hour;

            // jam_sisa: max_running_hour dikurangi jam_terpakai
            $jam_sisa = $max_running_hour_int - $jam_terpakai_raw;

            // Handle jika jam_sisa menjadi negatif (sudah melebihi max_running_hour)
            // Anda bisa memutuskan untuk menampilkan sebagai 'Overdue' atau tetap 'Danger'
            if ($jam_sisa < 0) {
                // Contoh: Jika sudah lewat batas, bisa jadi 'Overdue' atau tetap 'Danger'
                // Untuk konsistensi dengan 'Danger' jika <= 72, kita bisa biarkan masuk ke Danger
                // atau buat status baru seperti 'Overdue'. Untuk saat ini, biarkan masuk ke Danger jika negatif.
                // Jika ingin status khusus saat overdue: $item->status = 'Overdue';
            }

            $item->jam_terpakai = number_format($jam_terpakai_raw); // Format angka untuk tampilan
            $item->jam_sisa = $jam_sisa; // Simpan jam_sisa untuk digunakan di tampilan

            // Logika Status yang Diperbarui berdasarkan jam_sisa:
            if ($jam_sisa <= 72) { // Danger: sisa jam kurang dari atau sama dengan 72
                $item->status = 'Danger';
            } elseif ($jam_sisa <= 168) { // Warning: sisa jam kurang dari atau sama dengan 168 (tetapi lebih dari 72)
                $item->status = 'Warning';
            } else { // Safe: sisa jam lebih dari 168
                $item->status = 'Safe';
            }

            // Logika Penentuan Jenis Maintenance
            // Mingguan Running Hours 2500 - 4000
            // Bulanan Running Hours  4001 - 10000
            // Tahunan Running Hours 10001 - 25000
            if ($max_running_hour_int >= 0 && $max_running_hour_int <= 1000) {
                $item->maintenance_type = 'harian';
            } elseif ($max_running_hour_int >= 1001 && $max_running_hour_int <= 4000) {
                $item->maintenance_type = 'Bulanan';
            } elseif ($max_running_hour_int >= 4001 && $max_running_hour_int <= 8640) {
                $item->maintenance_type = 'Bulanan';
            } elseif ($max_running_hour_int >= 8641 && $max_running_hour_int <= 500000) {
                $item->maintenance_type = 'Tahunan';
            } else {
                $item->maintenance_type = 'lain-lain'; // Atau Anda bisa set default lain
            }

            return $item;
        });

        // Buat instance model Sparepart di sini untuk digunakan oleh sparepartsData
        $sparepartModel = new Sparepart();

        $sparepartsData = [
            'ae' => $sparepartModel->setTable('spareparts_ae')
                                  ->where('jumlah', '>', 0)
                                  ->select('id', 'nama_barang', 'no_seri', 'mrh')
                                  ->get()
                                  ->toArray(),
            'me' => $sparepartModel->setTable('spareparts_me')
                                  ->where('jumlah', '>', 0)
                                  ->select('id', 'nama_barang', 'no_seri', 'mrh')
                                  ->get()
                                  ->toArray(),
            'pe' => $sparepartModel->setTable('spareparts_pe')
                                  ->where('jumlah', '>', 0)
                                  ->select('id', 'nama_barang', 'no_seri', 'mrh')
                                  ->get()
                                  ->toArray(),
        ];

        \Log::info('Spareparts Data (from Controller - setTable):', $sparepartsData);

        return view('running_hours.index', compact('data', 'sparepartsData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string',
            'sparepart_id' => 'required|integer',
            'tanggal_mulai' => 'required|date',
        ]);

        $kategori = $request->kategori;
        $modelInstance = new Sparepart();

        $tableName = '';
        switch ($kategori) {
            case 'ae': $tableName = 'spareparts_ae'; break;
            case 'me': $tableName = 'spareparts_me'; break;
            case 'pe': $tableName = 'spareparts_pe'; break;
            default:
                return back()->with('error', 'Kategori sparepart tidak valid.');
        }

        $sparepart = $modelInstance->setTable($tableName)
                                     ->where('id', $request->sparepart_id)
                                     ->where('jumlah', '>', 0)
                                     ->first();

        if (!$sparepart) {
            return back()->with('error', 'Sparepart tidak ditemukan atau stok habis untuk kategori yang dipilih.');
        }

        if (empty($sparepart->mrh) || !is_numeric($sparepart->mrh)) {
            return back()->with('error', "Sparepart '{$sparepart->nama_barang}' tidak memiliki nilai MRH yang valid.");
        }

        // Status awal untuk database
        $initial_status_for_db = 'Safe';

        // Dapatkan waktu saat ini dalam WIB
        // Karena kita sudah set 'timezone' => 'Asia/Jakarta' di config/app.php
        // Carbon::now() akan otomatis berada di zona waktu tersebut
        $currentTimestampWIB = Carbon::now();

        RunningHour::create([

            'kategori' => $kategori,
            'sparepart_id' => $sparepart->id,
            'nama_barang' => $sparepart->nama_barang,
            'no_seri' => $sparepart->no_seri,
            'max_running_hour' => $sparepart->mrh,
            'tanggal_mulai' => $request->tanggal_mulai, // Ini adalah tanggal yang dipilih di form
            // Jika Anda ingin juga menyimpan waktu saat ini saat entri dibuat:
            'created_at' => $currentTimestampWIB, // Otomatis oleh timestamps(), tapi bisa di override
            'updated_at' => $currentTimestampWIB, // Otomatis oleh timestamps(), tapi bisa di override
            // Jika Anda memiliki kolom `jam_mulai` di tabel `running_hours`:
            // 'jam_mulai' => $currentTimestampWIB->format('H:i:s'),
            'status' => $initial_status_for_db,
        ]);

        Report::create([
            'action' => 'Tambah Running Hour',
            'description' => 'Menambahkan data running hour untuk sparepart ' . $sparepart->nama_barang . ' (No Seri: ' . $sparepart->no_seri . ') pada kategori ' . strtoupper($kategori) . ' dengan Max RH: ' . $sparepart->mrh . ' dan Tanggal Mulai: ' . $request->tanggal_mulai . '.',
            // 'created_at' dan 'updated_at' TIDAK perlu diset manual di sini karena Model Report memiliki timestamps()
        ]);

        // ... (Pencatatan log Report, sudah menggunakan Carbon::now() yang akan sesuai timezone aplikasi) ...

        return redirect()->route('running_hours.index')->with('success', 'Data running hours berhasil ditambahkan.');
    }

     /**
     * Menampilkan form untuk mengganti sparepart Running Hour.
     * Hanya admin yang bisa mengakses ini.
     *
     * @param int $id ID RunningHour yang akan diganti
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showReplaceForm($id)
    {
        // Middleware 'admin' sudah melindungi ini, tapi validasi tetap bagus
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect('/dashboard')->with('error', 'Akses Ditolak: Anda tidak memiliki izin Admin.');
        }

        $itemToReplace = RunningHour::find($id);

        if (!$itemToReplace) {
            return back()->with('error', 'Item Running Hour tidak ditemukan.');
        }

        // Ambil data sparepart yang tersedia untuk kategori yang sama
        $sparepartModel = new Sparepart();
        $tableName = '';
        switch ($itemToReplace->kategori) {
            case 'ae': $tableName = 'spareparts_ae'; break;
            case 'me': $tableName = 'spareparts_me'; break;
            case 'pe': $tableName = 'spareparts_pe'; break;
        }

        $availableSpareparts = $sparepartModel->setTable($tableName)
                                             ->where('jumlah', '>', 0)
                                             ->select('id', 'nama_barang', 'no_seri', 'mrh')
                                             ->get();

        return view('running_hours.replace_form', compact('itemToReplace', 'availableSpareparts'));
    }

    /**
     * Memproses penggantian sparepart Running Hour.
     * Hanya admin yang bisa mengakses ini.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id ID RunningHour yang diganti
     * @return \Illuminate\Http\RedirectResponse
     */
    public function replaceSparepart(Request $request, $id)
{
    // Middleware 'admin' sudah melindungi ini
    if (!Auth::check() || !Auth::user()->isAdmin()) {
        return redirect('/dashboard')->with('error', 'Akses Ditolak: Anda tidak memiliki izin Admin.');
    }

    $itemToReplace = RunningHour::find($id);

    if (!$itemToReplace) {
        return back()->with('error', 'Item Running Hour yang akan diganti tidak ditemukan.');
    }

    $request->validate([
        'new_sparepart_id' => 'required|integer',
        'tanggal_mulai_baru' => 'required|date',
        'keterangan_penggantian' => 'nullable|string|max:255',
    ]);

    // 1. Ambil detail sparepart pengganti dari inventaris
    $sparepartModel = new Sparepart();
    $tableName = '';
    switch ($itemToReplace->kategori) { // Gunakan kategori dari item lama
        case 'ae': $tableName = 'spareparts_ae'; break;
        case 'me': $tableName = 'spareparts_me'; break;
        case 'pe': $tableName = 'spareparts_pe'; break;
    }

    $newSparepart = $sparepartModel->setTable($tableName)
                                   ->where('id', $request->new_sparepart_id)
                                   ->where('jumlah', '>', 0)
                                   ->first();

    if (!$newSparepart) {
        return back()->with('error', 'Sparepart pengganti tidak ditemukan atau stok habis. Silakan pilih sparepart lain.');
    }

    // 2. Buat entri Running Hour baru untuk sparepart pengganti
    RunningHour::create([
        'kategori' => $itemToReplace->kategori,
        'sparepart_id' => $newSparepart->id, // ID sparepart pengganti
        'nama_barang' => $newSparepart->nama_barang,
        'no_seri' => $newSparepart->no_seri,
        'max_running_hour' => $newSparepart->mrh,
        'tanggal_mulai' => $request->tanggal_mulai_baru, // Tanggal mulai untuk yang baru
        'status' => 'Safe', // Status awal yang baru adalah 'Safe'
    ]);

    // 3. Update status item Running Hour lama menjadi 'Replaced'
    $itemToReplace->status = 'Replaced'; // Misal tambahkan status 'Replaced' di DB Anda
    // HAPUS BARIS-BARIS BERIKUT (karena kolom ini tidak ada di DB):
    // $itemToReplace->jam_terpakai = Carbon::parse($itemToReplace->tanggal_mulai)->diffInHours(Carbon::now());
    // $itemToReplace->jam_sisa = 0;
    $itemToReplace->save(); // Cukup simpan perubahan status

    // Opsional: Kurangi jumlah sparepart pengganti dari inventaris
    // Ini diasumsikan sparepart yang digunakan adalah 1 unit
    // Pastikan $sparepartModel diinisialisasi jika ini adalah satu-satunya tempat digunakan di method ini
    // Atau diinisialisasi di __construct() jika sering digunakan
    // Menggunakan $newSparepart->kategori untuk memastikan $tableName yang benar
    $sparepartModel->setTable($tableName)
                   ->where('id', $newSparepart->id)
                   ->decrement('jumlah');


    // 4. Catat aktivitas penggantian di laporan
    Report::create([
        'action' => 'Penggantian Sparepart Running Hour',
        'description' => 'Sparepart "' . $itemToReplace->nama_barang . '" (ID: ' . $itemToReplace->id . ', No Seri: ' . $itemToReplace->no_seri . ') diganti dengan sparepart baru "' . $newSparepart->nama_barang . '" (ID: ' . $newSparepart->id . ', No Seri: ' . $newSparepart->no_seri . ') oleh Admin ' . Auth::user()->name . '. Keterangan: ' . $request->keterangan_penggantian,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ]);

    return redirect()->route('running_hours.index')->with('success', 'Sparepart Running Hour berhasil diganti!');
}
}