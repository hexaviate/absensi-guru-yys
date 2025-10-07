<?php

namespace App\Http\Controllers;

use App\Exports\RekapHarianExport;
use App\Exports\RekapBulananExport;
use App\Exports\RekapTahunanExport;  // Tambahkan import baru
use App\Models\Instansi;
use App\Models\Presensi;
use App\Models\Tapel;  // Tambahkan import Tapel
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $query = Presensi::with(['user', 'instansi']);

        // ngeFILTER STATUS
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // ngeFILTER INSTANSI
        if ($request->filled('instansi')) {
            $query->where('instansi_id', $request->instansi);
        }

        // ngeFILTER HARIAN
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        // ngeFILTER BULANAN (dari - sampai)
        if ($request->filled('dari_tanggal') && $request->filled('sampai_tanggal')) {
            $query->whereBetween('tanggal', [$request->dari_tanggal, $request->sampai_tanggal]);
        }

        // ngeFILTER TAHUN AJARAN (menggunakan tapel) - dengan debug
        if ($request->filled('tahun_ajaran')) {
            $tapel = Tapel::find($request->tahun_ajaran);
            if ($tapel) {
                $dateRange = $tapel->getDateRange();

                // Debug log untuk memastikan range tanggal benar
                \Log::info('Filter Tahun Ajaran Debug:', [
                    'tapel_id' => $request->tahun_ajaran,
                    'tapel_kode' => $tapel->kode,
                    'date_range' => $dateRange
                ]);

                if ($dateRange) {
                    $query->whereBetween('tanggal', [$dateRange['start'], $dateRange['end']]);
                }
            }
        }

        $presensi = $query->orderBy('tanggal', 'desc')->get();

        // Debug log untuk hasil query
        \Log::info('Query Result Count: ' . $presensi->count());

        $instansi = Instansi::all();
        $tapels = Tapel::active()->get(); // Ambil semua tahun ajaran

        return view('laporan.rekap_absensi', compact('presensi', 'instansi', 'tapels'));
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new RekapHarianExport($request), 'rekap_harian_' . now()->format('Y-m-d') . '.xlsx');
    }

    public function exportPDF(Request $request)
    {
        $query = Presensi::with(['user', 'instansi']);

        // Terapkan filter yang sama
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('instansi')) {
            $query->where('instansi_id', $request->instansi);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        $presensi = $query->orderBy('tanggal', 'desc')->get();

        // Ambil nama instansi untuk header
        $namaInstansi = 'Semua';
        if ($request->filled('instansi')) {
            $instansi = Instansi::find($request->instansi);
            $namaInstansi = $instansi ? $instansi->nama_instansi : 'Semua';
        }

        // Data untuk view PDF
        $data = [
            'presensi' => $presensi,
            'filter' => [
                'status' => $request->status ?: 'Semua',
                'instansi' => $namaInstansi,
                'tanggal' => $request->tanggal ?: now()->format('Y-m-d')
            ]
        ];

        $pdf = Pdf::loadView('laporan.pdf_rekap_harian', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->download('rekap_harian_' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportExcelBulanan(Request $request)
    {
        try {
            \Log::info('Export Excel Bulanan called with params:', $request->all());

            $filename = 'rekap_bulanan_' . now()->format('Y-m-d') . '.xlsx';

            return Excel::download(new RekapBulananExport($request), $filename);
        } catch (\Exception $e) {
            \Log::error('Excel Bulanan Export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal export Excel: ' . $e->getMessage());
        }
    }

    public function exportPDFBulanan(Request $request)
    {
        try {
            \Log::info('Export PDF Bulanan called with params:', $request->all());

            $query = Presensi::with(['user', 'instansi']);

            // Filter bulanan
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('instansi')) {
                $query->where('instansi_id', $request->instansi);
            }

            if ($request->filled('dari_tanggal') && $request->filled('sampai_tanggal')) {
                $query->whereBetween('tanggal', [$request->dari_tanggal, $request->sampai_tanggal]);
            }

            $presensi = $query->orderBy('tanggal', 'desc')->get();

            // Ambil nama instansi untuk header
            $namaInstansi = 'Semua';
            if ($request->filled('instansi')) {
                $instansi = Instansi::find($request->instansi);
                $namaInstansi = $instansi ? $instansi->nama_instansi : 'Semua';
            }

            // Format periode
            $periode = '';
            if ($request->filled('dari_tanggal') && $request->filled('sampai_tanggal')) {
                $dari = \Carbon\Carbon::parse($request->dari_tanggal)->format('d F Y');
                $sampai = \Carbon\Carbon::parse($request->sampai_tanggal)->format('d F Y');
                $periode = $dari . ' s/d ' . $sampai;
            } else {
                $periode = 'Semua Periode';
            }

            $data = [
                'presensi' => $presensi,
                'filter' => [
                    'status' => $request->status ?: 'Semua',
                    'instansi' => $namaInstansi,
                    'periode' => $periode
                ]
            ];

            $pdf = Pdf::loadView('laporan.pdf_rekap_bulanan', $data)
                ->setPaper('a4', 'portrait');

            return $pdf->download('rekap_bulanan_' . now()->format('Y-m-d') . '.pdf');
        } catch (\Exception $e) {
            \Log::error('PDF Bulanan Export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal export PDF: ' . $e->getMessage());
        }
    }

    // Method baru untuk export tahunan
    public function exportExcelTahunan(Request $request)
    {
        try {
            \Log::info('Export Excel Tahunan called with params:', $request->all());

            $filename = 'rekap_tahunan_' . now()->format('Y-m-d') . '.xlsx';

            return Excel::download(new RekapTahunanExport($request), $filename);
        } catch (\Exception $e) {
            \Log::error('Excel Tahunan Export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal export Excel: ' . $e->getMessage());
        }
    }

    public function exportPDFTahunan(Request $request)
    {
        try {
            \Log::info('Export PDF Tahunan called with params:', $request->all());

            $query = Presensi::with(['user', 'instansi']);

            // Filter tahunan
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('instansi')) {
                $query->where('instansi_id', $request->instansi);
            }

            if ($request->filled('tahun_ajaran')) {
                $tapel = Tapel::find($request->tahun_ajaran);
                if ($tapel) {
                    $dateRange = $tapel->getDateRange();
                    if ($dateRange) {
                        $query->whereBetween('tanggal', [$dateRange['start'], $dateRange['end']]);
                    }
                }
            }

            $presensi = $query->orderBy('tanggal', 'desc')->get();

            // Ambil nama instansi untuk header
            $namaInstansi = 'Semua';
            if ($request->filled('instansi')) {
                $instansi = Instansi::find($request->instansi);
                $namaInstansi = $instansi ? $instansi->nama_instansi : 'Semua';
            }

            // Ambil tahun ajaran
            $tahunAjaran = 'Semua Tahun Ajaran';
            if ($request->filled('tahun_ajaran')) {
                $tapel = Tapel::find($request->tahun_ajaran);
                $tahunAjaran = $tapel ? $tapel->kode : 'Semua Tahun Ajaran';
            }

            $data = [
                'presensi' => $presensi,
                'filter' => [
                    'status' => $request->status ?: 'Semua',
                    'instansi' => $namaInstansi,
                    'tahun_ajaran' => $tahunAjaran
                ]
            ];

            $pdf = Pdf::loadView('laporan.pdf_rekap_tahunan', $data)
                ->setPaper('a4', 'portrait');

            return $pdf->download('rekap_tahunan_' . now()->format('Y-m-d') . '.pdf');
        } catch (\Exception $e) {
            \Log::error('PDF Tahunan Export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal export PDF: ' . $e->getMessage());
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
