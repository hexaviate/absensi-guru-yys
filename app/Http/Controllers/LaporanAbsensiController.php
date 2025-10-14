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

class LaporanAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        try {
            // Base query dengan eager loading dan validasi relasi
            $query = Presensi::with(['user', 'instansi'])
                ->whereHas('user')
                ->whereHas('instansi');

            // Filter berdasarkan role
            if ($user->hasRole('operator_instansi')) {
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
                        'user' => $user,
                        'isOperatorInstansi' => true
                    ])->with('error', 'Akun Anda belum terhubung dengan instansi. Silakan hubungi administrator.');
                }

                $query->where('instansi_id', $instansiUser->id);

                Log::info('Operator Instansi Access:', [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'instansi_id' => $instansiUser->id,
                    'instansi_name' => $instansiUser->nama_instansi
                ]);
            } elseif ($user->hasRole('admin_yayasan')) {
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

            // Get list instansi berdasarkan role
            $isOperatorInstansi = $user->hasRole('operator_instansi');
            if ($isOperatorInstansi) {
                $instansiUser = $user->instansi()->first();
                $instansi = $instansiUser ? collect([$instansiUser]) : collect([]);
            } else {
                $instansi = Instansi::all();
            }

            // Get list tahun ajaran aktif
            $tapels = Tapel::active()->orderBy('kode', 'desc')->get();

            return view('laporan.rekap_absensi', compact('presensi', 'instansi', 'tapels', 'user', 'isOperatorInstansi'));
        } catch (\Exception $e) {
            Log::error('ERROR in LaporanAbsensiController@index:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $user->id ?? null
            ]);

            return view('laporan.rekap_absensi', [
                'presensi' => collect([]),
                'instansi' => collect([]),
                'tapels' => collect([]),
                'user' => $user,
                'isOperatorInstansi' => $user->hasRole('operator_instansi')
            ])->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            $filename = 'rekap_harian_' . now()->format('Y-m-d') . '.xlsx';
            return Excel::download(new RekapHarianExport($request), $filename);
        } catch (\Exception $e) {
            Log::error('Excel Harian Export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal export Excel: ' . $e->getMessage());
        }
    }

    public function exportPDF(Request $request)
    {
        try {
            $user = Auth::user();
            $query = Presensi::with(['user', 'instansi']);

            // Filter untuk operator instansi
            $isOperatorInstansi = $user->hasRole('operator_instansi');
            if ($isOperatorInstansi) {
                $instansiOperator = $user->instansi()->first();
                if ($instansiOperator) {
                    $query->where('instansi_id', $instansiOperator->id);
                }
            } elseif ($request->filled('instansi')) {
                $query->where('instansi_id', $request->instansi);
            }

            // Filter
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('tanggal')) {
                $query->whereDate('tanggal', $request->tanggal);
            }

            $presensi = $query->orderBy('tanggal', 'desc')->get();

            // Urutkan berdasarkan instansi (PAUD → MI → MTS → MA → SMK → PATTA)
            $urutanInstansi = ['PAUD', 'MI', 'MTS', 'MA', 'SMK', 'PATTA'];

            $presensi = $presensi->sort(function ($a, $b) use ($urutanInstansi) {
                $instansiA = strtoupper($a->instansi->nama_instansi ?? '');
                $instansiB = strtoupper($b->instansi->nama_instansi ?? '');

                $indexA = array_search($instansiA, $urutanInstansi);
                $indexB = array_search($instansiB, $urutanInstansi);

                $indexA = $indexA === false ? 999 : $indexA;
                $indexB = $indexB === false ? 999 : $indexB;

                if ($indexA === $indexB) {
                    return $b->tanggal <=> $a->tanggal;
                }

                return $indexA - $indexB;
            })->values();

            // Ambil nama instansi untuk header
            $namaInstansi = 'Semua';
            if ($isOperatorInstansi) {
                $instansiOperator = $user->instansi()->first();
                $namaInstansi = $instansiOperator ? $instansiOperator->nama_instansi : 'Semua';
            } elseif ($request->filled('instansi')) {
                $instansi = Instansi::find($request->instansi);
                $namaInstansi = $instansi ? $instansi->nama_instansi : 'Semua';
            }

            $data = [
                'presensi' => $presensi,
                'filter' => [
                    'status' => $request->status ?: 'Semua',
                    'instansi' => $namaInstansi,
                    'tanggal' => $request->tanggal ?: now()->format('Y-m-d')
                ]
            ];

            $pdf = Pdf::loadView('laporan.pdf_rekap_harian', $data)->setPaper('a4', 'portrait');
            return $pdf->download('rekap_harian_' . now()->format('Y-m-d') . '.pdf');
        } catch (\Exception $e) {
            Log::error('PDF Harian Export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal export PDF: ' . $e->getMessage());
        }
    }

    public function exportExcelBulanan(Request $request)
    {
        try {
            Log::info('Export Excel Bulanan called with params:', $request->all());
            $filename = 'rekap_bulanan_' . now()->format('Y-m-d') . '.xlsx';
            return Excel::download(new RekapBulananExport($request), $filename);
        } catch (\Exception $e) {
            Log::error('Excel Bulanan Export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal export Excel: ' . $e->getMessage());
        }
    }

    public function exportPDFBulanan(Request $request)
    {
        try {
            Log::info('Export PDF Bulanan called with params:', $request->all());

            $user = Auth::user();
            $query = Presensi::with(['user', 'instansi']);

            // Filter untuk operator instansi
            $isOperatorInstansi = $user->hasRole('operator_instansi');
            if ($isOperatorInstansi) {
                $instansiOperator = $user->instansi()->first();
                if ($instansiOperator) {
                    $query->where('instansi_id', $instansiOperator->id);
                }
            } elseif ($request->filled('instansi')) {
                $query->where('instansi_id', $request->instansi);
            }

            // Filter bulanan
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('dari_tanggal') && $request->filled('sampai_tanggal')) {
                $query->whereBetween('tanggal', [$request->dari_tanggal, $request->sampai_tanggal]);
            }

            $presensiData = $query->get();

            // Hitung rekap per user
            $grouped = $presensiData->groupBy('user_id');

            $rekapData = $grouped->map(function ($items, $userId) {
                $user = $items->first()->user;
                $instansi = $items->first()->instansi;

                return [
                    'user_id' => $userId,
                    'nama' => $user->name ?? 'N/A',
                    'instansi' => $instansi->nama_instansi ?? 'N/A',
                    'hadir' => $items->where('status', 'hadir')->count(),
                    'izin' => $items->where('status', 'izin')->count(),
                    'alpa' => $items->where('status', 'alpa')->count(),
                    'tanpa_ket' => $items->where('status', 'tanpa_keterangan')->count(),
                ];
            })->values();

            // Urutkan berdasarkan instansi (PAUD → MI → MTS → MA → SMK → PATTA)
            $urutanInstansi = ['PAUD', 'MI', 'MTS', 'MA', 'SMK', 'PATTA'];

            $rekapData = $rekapData->sort(function ($a, $b) use ($urutanInstansi) {
                $indexA = array_search(strtoupper($a['instansi']), $urutanInstansi);
                $indexB = array_search(strtoupper($b['instansi']), $urutanInstansi);

                $indexA = $indexA === false ? 999 : $indexA;
                $indexB = $indexB === false ? 999 : $indexB;

                if ($indexA === $indexB) {
                    return strcmp($a['nama'], $b['nama']);
                }

                return $indexA - $indexB;
            })->values();

            // Ambil nama instansi untuk header
            $namaInstansi = 'Semua';
            if ($isOperatorInstansi) {
                $instansiOperator = $user->instansi()->first();
                $namaInstansi = $instansiOperator ? $instansiOperator->nama_instansi : 'Semua';
            } elseif ($request->filled('instansi')) {
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
                'rekap' => $rekapData,
                'filter' => [
                    'status' => $request->status ?: 'Semua',
                    'instansi' => $namaInstansi,
                    'periode' => $periode
                ]
            ];

            $pdf = Pdf::loadView('laporan.pdf_rekap_bulanan', $data)->setPaper('a4', 'portrait');
            return $pdf->download('rekap_bulanan_' . now()->format('Y-m-d') . '.pdf');
        } catch (\Exception $e) {
            Log::error('PDF Bulanan Export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal export PDF: ' . $e->getMessage());
        }
    }

    public function exportExcelTahunan(Request $request)
    {
        try {
            Log::info('Export Excel Tahunan called with params:', $request->all());
            $filename = 'rekap_tahunan_' . now()->format('Y-m-d') . '.xlsx';
            return Excel::download(new RekapTahunanExport($request), $filename);
        } catch (\Exception $e) {
            Log::error('Excel Tahunan Export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal export Excel: ' . $e->getMessage());
        }
    }

    public function exportPDFTahunan(Request $request)
    {
        try {
            Log::info('Export PDF Tahunan called with params:', $request->all());

            $user = Auth::user();
            $query = Presensi::with(['user', 'instansi']);

            // Filter untuk operator instansi
            $isOperatorInstansi = $user->hasRole('operator_instansi');
            if ($isOperatorInstansi) {
                $instansiOperator = $user->instansi()->first();
                if ($instansiOperator) {
                    $query->where('instansi_id', $instansiOperator->id);
                }
            } elseif ($request->filled('instansi')) {
                $query->where('instansi_id', $request->instansi);
            }

            // Filter tahunan
            if ($request->filled('status')) {
                $query->where('status', $request->status);
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

            $presensiData = $query->get();

            // Hitung rekap per user
            $grouped = $presensiData->groupBy('user_id');

            $rekapData = $grouped->map(function ($items, $userId) {
                $user = $items->first()->user;
                $instansi = $items->first()->instansi;

                return [
                    'user_id' => $userId,
                    'nama' => $user->name ?? 'N/A',
                    'instansi' => $instansi->nama_instansi ?? 'N/A',
                    'hadir' => $items->where('status', 'hadir')->count(),
                    'izin' => $items->where('status', 'izin')->count(),
                    'alpa' => $items->where('status', 'alpa')->count(),
                    'tanpa_ket' => $items->where('status', 'tanpa_keterangan')->count(),
                ];
            })->values();

            // Urutkan berdasarkan instansi (PAUD → MI → MTS → MA → SMK → PATTA)
            $urutanInstansi = ['PAUD', 'MI', 'MTS', 'MA', 'SMK', 'PATTA'];

            $rekapData = $rekapData->sort(function ($a, $b) use ($urutanInstansi) {
                $indexA = array_search(strtoupper($a['instansi']), $urutanInstansi);
                $indexB = array_search(strtoupper($b['instansi']), $urutanInstansi);

                $indexA = $indexA === false ? 999 : $indexA;
                $indexB = $indexB === false ? 999 : $indexB;

                if ($indexA === $indexB) {
                    return strcmp($a['nama'], $b['nama']);
                }

                return $indexA - $indexB;
            })->values();

            // Ambil nama instansi untuk header
            $namaInstansi = 'Semua';
            if ($isOperatorInstansi) {
                $instansiOperator = $user->instansi()->first();
                $namaInstansi = $instansiOperator ? $instansiOperator->nama_instansi : 'Semua';
            } elseif ($request->filled('instansi')) {
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
                'rekap' => $rekapData,
                'filter' => [
                    'status' => $request->status ?: 'Semua',
                    'instansi' => $namaInstansi,
                    'tahun_ajaran' => $tahunAjaran
                ]
            ];

            $pdf = Pdf::loadView('laporan.pdf_rekap_tahunan', $data)->setPaper('a4', 'portrait');
            return $pdf->download('rekap_tahunan_' . now()->format('Y-m-d') . '.pdf');
        } catch (\Exception $e) {
            Log::error('PDF Tahunan Export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal export PDF: ' . $e->getMessage());
        }
    }
}
