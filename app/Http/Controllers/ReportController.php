<?php

namespace App\Http\Controllers;

use App\Models\Report; // Impor model Report
use Illuminate\Http\Request;
use Carbon\Carbon; // Opsional, jika Anda ingin memformat tanggal di controller

class ReportController extends Controller
{
    public function index()
    {
        // Ambil semua laporan, urutkan berdasarkan waktu terbaru
        $reports = Report::orderBy('created_at', 'desc')->get();

        // Passed ke view
        return view('reports.index', compact('reports'));
    }

    public function download()
    {
        $reports = Report::orderBy('created_at', 'desc')->get();

        $fileName = 'laporan_aktivitas_' . Carbon::now()->format('Ymd_His') . '.csv'; // Gunakan Carbon untuk nama file yang konsisten

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $callback = function() use ($reports) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Aksi', 'Deskripsi', 'Waktu']); // Header CSV

            foreach ($reports as $report) {
                // Pastikan $report->created_at adalah objek Carbon karena $casts di model
                fputcsv($file, [
                    $report->id,
                    $report->action,
                    $report->description,
                    $report->created_at->format('d/m/Y H:i:s') // Format waktu dengan jam, menit, detik
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}