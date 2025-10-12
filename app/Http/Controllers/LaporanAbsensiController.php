<?php

namespace App\Http\Controllers;

use App\Exports\RekapHarianExport;
use App\Exports\RekapBulananExport;
use App\Exports\RekapTahunanExport;
use App\Models\Instansi;
use App\Models\Presensi;
use App\Models\Tapel;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaporanAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();


        try {
            // $instansiOperator = $user->instansi()->get();

            // Base query dengan eager loading dan validasi relasi
            $query = Presensi::with(['user', 'instansi'])
                ->whereHas('user') // Pastikan user exists
                ->whereHas('instansi'); // Pastikan instansi exists

            // Filter berdasarkan role - CRITICAL: ini harus di awal


            if ($user->hasRole('operator_instansi')) {
                // Ambil instansi yang dimiliki user melalui relasi
                $instansiUser = $user->instansi()->first();

                if (!$instansiUser) {
                    Log::error('Operator instansi tidak memiliki relasi instansi', [
                        'user_id' => $user->id,
                        'user_name' => $user->name
                    ]);

                    return view('laporan.rekap_absensi', [
                        'presensi' => collect([]),
                        'instansi' => collect([]),
                        'tapels' => collect([]),
                        'user' => $user
                    ])->with('error', 'Akun Anda belum terhubung dengan instansi. Silakan hubungi administrator.');
                }

                // Filter hanya untuk instansi miliknya
                $query->where('instansi_id', $instansiUser->id);

                Log::info('Operator Instansi Access:', [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'instansi_id' => $instansiUser->id,
                    'instansi_name' => $instansiUser->nama_instansi
                ]);
            } elseif ($user->hasRole('admin_yayasan')) {
                // Admin yayasan bisa pilih instansi spesifik
                if ($request->filled('instansi')) {
                    $query->where('instansi_id', $request->instansi);

                    Log::info('Admin Yayasan Filter:', [
                        'user_id' => $user->id,
                        'selected_instansi' => $request->instansi
                    ]);
                } else {
                    Log::info('Admin Yayasan - Viewing All Instansi');
                }
            }

            // Filter STATUS
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Filter HARIAN (tanggal spesifik)
            if ($request->filled('tanggal')) {
                $query->whereDate('tanggal', $request->tanggal);
            }

            // Filter BULANAN (range tanggal)
            if ($request->filled('dari_tanggal') && $request->filled('sampai_tanggal')) {
                $query->whereBetween('tanggal', [$request->dari_tanggal, $request->sampai_tanggal]);
            }

            // Filter TAHUNAN (berdasarkan tahun ajaran)
            if ($request->filled('tahun_ajaran')) {
                $tapel = Tapel::find($request->tahun_ajaran);

                if ($tapel) {
                    $dateRange = $tapel->getDateRange();

                    if ($dateRange && isset($dateRange['start']) && isset($dateRange['end'])) {
                        $query->whereBetween('tanggal', [$dateRange['start'], $dateRange['end']]);

                        Log::info('Tahun Ajaran Filter Applied:', [
                            'tapel_kode' => $tapel->kode,
                            'date_range' => $dateRange
                        ]);
                    }
                }
            }

            // Get hasil query dengan ordering
            $presensi = $query->orderBy('tanggal', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();

            // Debug: Log query SQL
            Log::info('Query Executed:', [
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings()
            ]);

            // Validasi data yang didapat
            $invalidRecords = $presensi->filter(function ($item) {
                return !$item->user || !$item->instansi;
            });

            if ($invalidRecords->isNotEmpty()) {
                Log::warning('Found records with missing relations:', [
                    'count' => $invalidRecords->count(),
                    'ids' => $invalidRecords->pluck('id')->toArray()
                ]);
            }

            // Debug log
            Log::info('Query Result Summary:', [
                'user_role' => $user->getRoleNames()->first(),
                'user_id' => $user->id,
                'user_instansi_id' => $user->instansi_id ?? 'null',
                'filter_instansi' => $request->instansi ?? 'all',
                'filter_status' => $request->status ?? 'all',
                'filter_tanggal' => $request->tanggal ?? 'none',
                'total_records' => $presensi->count(),
                'has_data' => $presensi->isNotEmpty(),
                'sample_record' => $presensi->first() ? [
                    'id' => $presensi->first()->id,
                    'user_id' => $presensi->first()->user_id,
                    'user_name' => $presensi->first()->user->name ?? 'NULL',
                    'instansi_id' => $presensi->first()->instansi_id,
                    'instansi_name' => $presensi->first()->instansi->nama_instansi ?? 'NULL'
                ] : null
            ]);

            // Get list instansi berdasarkan role
            if ($user->hasRole('operator_instansi')) {
                $instansiUser = $user->instansi()->first();
                $instansi = $instansiUser ? collect([$instansiUser]) : collect([]);
            } else {
                $instansi = Instansi::all();
            }


            // Get list tahun ajaran aktif
            $tapels = Tapel::active()->orderBy('kode', 'desc')->get();

            return view('laporan.rekap_absensi', compact('presensi', 'instansi', 'tapels', 'user'));
        } catch (\Exception $e) {
            Log::error('ERROR in LaporanAbsensiController@index:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $user->id ?? null
            ]);



            // Fallback dengan data kosong
            return view('laporan.rekap_absensi', [
                'presensi' => collect([]),
                'instansi' => collect([]),
                'tapels' => collect([]),
                'user' => $user,
            ])->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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
