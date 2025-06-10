<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sparepart;
use App\Models\RunningHour; // Pastikan ini diimpor
use App\Models\Report;
use App\Models\SparepartRequest;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $sparepartModel = new Sparepart();

        // Data Inventaris (Total per kategori)
        $totalAE = $sparepartModel->setTable('spareparts_ae')->sum('jumlah');
        $totalME = $sparepartModel->setTable('spareparts_me')->sum('jumlah');
        $totalPE = $sparepartModel->setTable('spareparts_pe')->sum('jumlah');
        $totalInventaris = $totalAE + $totalME + $totalPE;

        // Data Running Hours
        $allRunningHoursItems = RunningHour::all()->map(function ($item) {
            $jam_terpakai_raw = Carbon::parse($item->tanggal_mulai)->diffInHours(now());
            $max_running_hour_int = (int) $item->max_running_hour;
            $jam_sisa = $max_running_hour_int - $jam_terpakai_raw;

            // Logika Status yang sama dengan RunningHoursController@index
            if ($jam_sisa <= 72) {
                $item->calculated_status = 'Danger';
            } elseif ($jam_sisa <= 168) {
                $item->calculated_status = 'Warning';
            } else {
                $item->calculated_status = 'Safe';
            }
            $item->jam_sisa = $jam_sisa; // Juga penting untuk pop-up

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

        $totalRunningHours = $allRunningHoursItems->count();
        $runningHoursWarning = $allRunningHoursItems->where('calculated_status', 'Warning')->count();
        $runningHoursDanger = $allRunningHoursItems->where('calculated_status', 'Danger')->count();

        // Variabel untuk pop-up (criticalRunningHours) juga sudah dihitung di atas
        $criticalRunningHours = $allRunningHoursItems->whereIn('calculated_status', ['Warning', 'Danger'])->values();

        // Penghitungan untuk Card Maintenance Baru
        $maintenanceCounts = [
            'Harian' => $allRunningHoursItems->where('maintenance_type', 'Harian')->count(),
            'Mingguan' => $allRunningHoursItems->where('maintenance_type', 'Mingguan')->count(),
            'Bulanan' => $allRunningHoursItems->where('maintenance_type', 'Bulanan')->count(),
            'Tahunan' => $allRunningHoursItems->where('maintenance_type', 'Tahunan')->count(),
            'Lain-lain' => $allRunningHoursItems->where('maintenance_type', 'Lain-lain')->count(),
        ];

        // Data Request (tetap sama)
        $totalRequests = SparepartRequest::count();
        $pendingRequests = SparepartRequest::where('status', 'pending')->count();
        $approvedRequests = SparepartRequest::where('status', 'approved')->count();
        $rejectedRequests = SparepartRequest::where('status', 'rejected')->count();

        // Data Report (tetap sama)
        $totalReports = Report::count();
        $latestReports = Report::orderBy('created_at', 'desc')->take(5)->get();

        return view('dashboard', compact(
            'totalAE',
            'totalME',
            'totalPE',
            'totalInventaris',
            'totalRunningHours',
            'runningHoursWarning',
            'runningHoursDanger', // Ini sekarang akan menampilkan angka yang benar
            'totalRequests',
            'pendingRequests',
            'approvedRequests',
            'rejectedRequests',
            'totalReports',
            'latestReports',
            'criticalRunningHours', // Variabel ini sudah dihitung dan benar
            'maintenanceCounts'
        ));
    }
}