<?php

namespace App\Http\Controllers;

use App\Exports\RekapHarianExport;
use App\Exports\RekapBulananExport;
use App\Exports\RekapTahunanExport;
use App\Models\HariLibur;
use App\Models\Instansi;
use App\Models\Presensi;
use App\Models\TidakHadir;
use App\Models\Tapel;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LaporanAbsensiController extends Controller
{
    /**
     * Proses pengecekan alpa otomatis untuk semua user
     * Dipanggil setiap hari jam 14:00 via scheduler
     */
    public function prosesAlpaOtomatis()
    {
        try {
            $today = Carbon::today();
            $jam14 = Carbon::today()->setTime(14, 0, 0);

            // Cek apakah sudah lewat jam 14:00
            if (Carbon::now()->lt($jam14)) {
                Log::info('Belum waktunya proses alpa (sebelum jam 14:00)');
                return;
            }

            // Ambil semua user yang memiliki instansi
            $users = User::whereHas('instansi')->with('instansi')->get();

            foreach ($users as $user) {
                foreach ($user->instansi as $instansi) {
                    // Cek apakah instansi adalah SMK
                    $isSMK = strtoupper($instansi->nama_instansi) === 'SMK';

                    // Skip jika hari ini adalah hari libur untuk instansi ini
                    $isLibur = false;

                    // Jika SMK dan hari Jumat, skip
                    if ($isSMK && $today->dayOfWeek == 5) {
                        continue;
                    }

                    // Cek hari libur resmi
                    $hariLibur = HariLibur::whereDate('tanggal', $today)
                        ->where('instansi_id', $instansi->id)
                        ->exists();

                    if ($hariLibur) {
                        continue;
                    }

                    // Cek apakah user sudah presensi hadir hari ini
                    $sudahHadir = Presensi::where('user_id', $user->id)
                        ->where('instansi_id', $instansi->id)
                        ->whereDate('tanggal', $today)
                        ->where('status', 'hadir')
                        ->exists();

                    // Cek apakah user sudah izin hari ini
                    $sudahIzin = Presensi::where('user_id', $user->id)
                        ->where('instansi_id', $instansi->id)
                        ->whereDate('tanggal', $today)
                        ->where('status', 'izin')
                        ->exists();

                    // Jika tidak hadir dan tidak izin, tambahkan ke tabel tidak_hadirs
                    if (!$sudahHadir && !$sudahIzin) {
                        // Cek apakah sudah ada record di tidak_hadirs
                        $tidakHadirExists = TidakHadir::where('user_id', $user->id)
                            ->where('instansi_id', $instansi->id)
                            ->whereDate('tanggal', $today)
                            ->exists();

                        if (!$tidakHadirExists) {
                            TidakHadir::create([
                                'user_id' => $user->id,
                                'instansi_id' => $instansi->id,
                                'tanggal' => $today,
                                'keterangan' => 'Alpa otomatis - tidak presensi sampai jam 14:00'
                            ]);

                            Log::info('Alpa otomatis ditambahkan:', [
                                'user_id' => $user->id,
                                'user_name' => $user->name,
                                'instansi_id' => $instansi->id,
                                'tanggal' => $today->toDateString()
                            ]);
                        }
                    }
                }
            }

            Log::info('Proses alpa otomatis selesai untuk tanggal: ' . $today->toDateString());
        } catch (\Exception $e) {
            Log::error('Error proses alpa otomatis: ' . $e->getMessage());
        }
    }

    /**
     * Hitung alpa dari tabel tidak_hadirs
     */
    private function hitungAlpa($userId, $instansiId, $dari, $sampai)
    {
        $dari = Carbon::parse($dari);
        $sampai = Carbon::parse($sampai);

        $alpa = TidakHadir::where('user_id', $userId)
            ->where('instansi_id', $instansiId)
            ->whereBetween('tanggal', [$dari, $sampai])
            ->count();

        return $alpa;
    }

    /**
     * Ambil data rekap tanpa kolom hari kerja
     */
    private function getRekapData($query, $dari, $sampai)
    {
        $dari = Carbon::parse($dari);
        $sampai = Carbon::parse($sampai);

        $presensiData = $query->get();

        // Group by user_id dan instansi_id
        $grouped = $presensiData->groupBy(function ($item) {
            return $item->user_id . '_' . $item->instansi_id;
        });

        $rekapData = $grouped->map(function ($items, $key) use ($dari, $sampai) {
            $firstItem = $items->first();
            $user = $firstItem->user;
            $instansi = $firstItem->instansi;

            // Hitung presensi
            $hadir = $items->where('status', 'hadir')->count();
            $izin = $items->where('status', 'izin')->count();

            // Hitung alpa dari tabel tidak_hadirs
            $alpa = $this->hitungAlpa($user->id, $instansi->id, $dari, $sampai);

            return [
                'user_id' => $user->id,
                'nama' => $user->name ?? 'N/A',
                'instansi_id' => $instansi->id,
                'instansi' => $instansi->nama_instansi ?? 'N/A',
                'hadir' => $hadir,
                'izin' => $izin,
                'alpa' => $alpa,
            ];
        })->values();

        return $rekapData;
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $jenis = $request->query('jenis', 'harian'); // Default: harian

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
                        'isOperatorInstansi' => true,
                        'jenis' => $jenis
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

            // Filter berdasarkan JENIS (Harian, Bulanan, Tahunan)
            if ($jenis === 'harian') {
                if ($request->filled('tanggal')) {
                    $query->whereDate('tanggal', $request->tanggal);
                } else {
                    $query->whereDate('tanggal', Carbon::today());
                }
            } elseif ($jenis === 'bulanan') {
                if ($request->filled('dari_tanggal') && $request->filled('sampai_tanggal')) {
                    $query->whereBetween('tanggal', [$request->dari_tanggal, $request->sampai_tanggal]);
                } else {
                    $query->whereBetween('tanggal', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                }
            } elseif ($jenis === 'tahunan') {
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
                } else {
                    $query->whereBetween('tanggal', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
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

            return view('laporan.rekap_absensi', compact('presensi', 'instansi', 'tapels', 'user', 'isOperatorInstansi', 'jenis'));
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
                'isOperatorInstansi' => $user->hasRole('operator_instansi'),
                'jenis' => $jenis
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
            $query = Presensi::with(['user', 'instansi'])
                ->whereHas('user')
                ->whereHas('instansi');

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
            $query = Presensi::with(['user', 'instansi'])
                ->whereHas('user')
                ->whereHas('instansi');

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

            $dari = $request->filled('dari_tanggal') ? $request->dari_tanggal : now()->startOfMonth()->toDateString();
            $sampai = $request->filled('sampai_tanggal') ? $request->sampai_tanggal : now()->endOfMonth()->toDateString();

            $query->whereBetween('tanggal', [$dari, $sampai]);

            // Ambil tapel untuk info saja
            $tapelKode = 'Semua Tahun Ajaran';

            if ($request->filled('tahun_ajaran')) {
                $tapel = Tapel::find($request->tahun_ajaran);
                if ($tapel) {
                    $tapelKode = $tapel->kode;
                }
            } else {
                $tapel = Tapel::where('status', 'aktif')->first();
                if ($tapel) {
                    $tapelKode = $tapel->kode;
                }
            }

            // Get rekap data
            $rekapData = $this->getRekapData($query, $dari, $sampai);

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
                $dariFormat = Carbon::parse($dari)->format('d F Y');
                $sampaiFormat = Carbon::parse($sampai)->format('d F Y');
                $periode = $dariFormat . ' s/d ' . $sampaiFormat;
            } else {
                $periode = 'Semua Periode';
            }

            $data = [
                'rekap' => $rekapData,
                'filter' => [
                    'status' => $request->status ?: 'Semua',
                    'instansi' => $namaInstansi,
                    'periode' => $periode,
                    'tahun_ajaran' => $tapelKode
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
            $query = Presensi::with(['user', 'instansi'])
                ->whereHas('user')
                ->whereHas('instansi');

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

            $dari = now()->startOfYear()->toDateString();
            $sampai = now()->endOfYear()->toDateString();
            $tahunAjaran = 'Semua Tahun Ajaran';

            if ($request->filled('tahun_ajaran')) {
                $tapel = Tapel::find($request->tahun_ajaran);
                if ($tapel) {
                    $dateRange = $tapel->getDateRange();
                    if ($dateRange && isset($dateRange['start']) && isset($dateRange['end'])) {
                        $dari = $dateRange['start'];
                        $sampai = $dateRange['end'];
                        $tahunAjaran = $tapel->kode;
                        $query->whereBetween('tanggal', [$dari, $sampai]);
                    }
                }
            } else {
                // Gunakan tapel aktif sebagai default
                $tapel = Tapel::where('status', 'aktif')->first();
                if ($tapel) {
                    $dateRange = $tapel->getDateRange();
                    if ($dateRange && isset($dateRange['start']) && isset($dateRange['end'])) {
                        $dari = $dateRange['start'];
                        $sampai = $dateRange['end'];
                        $tahunAjaran = $tapel->kode;
                        $query->whereBetween('tanggal', [$dari, $sampai]);
                    }
                }
            }

            // Get rekap data
            $rekapData = $this->getRekapData($query, $dari, $sampai);

            // Urutkan berdasarkan instansi
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
