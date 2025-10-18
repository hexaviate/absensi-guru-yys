<?php

namespace App\Exports;

use App\Models\Presensi;
use App\Models\TidakHadir;
use App\Models\Instansi;
use App\Models\Tapel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RekapBulananExport implements FromArray, WithStyles, WithTitle
{
    protected $request;
    protected $rekapData;
    protected $filter;

    public function __construct($request)
    {
        $this->request = $request;
        $this->loadData();
    }

    private function hitungAlpa($userId, $instansiId, $dari, $sampai)
    {
        $alpa = TidakHadir::where('user_id', $userId)
            ->where('instansi_id', $instansiId)
            ->whereBetween('tanggal', [$dari, $sampai])
            ->count();

        return $alpa;
    }

    private function loadData()
    {
        $user = Auth::user();
        $isOperatorInstansi = $user->hasRole('operator_instansi');

        // Query untuk mendapatkan presensi
        $query = Presensi::with(['user', 'instansi'])
            ->whereHas('user')
            ->whereHas('instansi');

        $instansiFilterId = null;

        // Filter untuk operator instansi
        if ($isOperatorInstansi) {
            $instansiOperator = $user->instansi()->first();
            if ($instansiOperator) {
                $query->where('instansi_id', $instansiOperator->id);
                $instansiFilterId = $instansiOperator->id;
            }
        } elseif ($this->request->filled('instansi')) {
            $query->where('instansi_id', $this->request->instansi);
            $instansiFilterId = $this->request->instansi;
        }

        // Filter status
        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }

        // Filter range tanggal
        $dari = $this->request->filled('dari_tanggal') ? $this->request->dari_tanggal : now()->startOfMonth()->toDateString();
        $sampai = $this->request->filled('sampai_tanggal') ? $this->request->sampai_tanggal : now()->endOfMonth()->toDateString();

        $query->whereBetween('tanggal', [$dari, $sampai]);

        $presensiData = $query->get();

        // Group by user_id dan instansi_id dari presensi
        $grouped = $presensiData->groupBy(function ($item) {
            return $item->user_id . '_' . $item->instansi_id;
        });

        // Ambil data dari tabel tidak_hadirs
        $tidakHadirQuery = TidakHadir::with(['user', 'instansi'])
            ->whereBetween('tanggal', [$dari, $sampai])
            ->whereHas('user')
            ->whereHas('instansi');

        if ($instansiFilterId) {
            $tidakHadirQuery->where('instansi_id', $instansiFilterId);
        }

        $tidakHadirData = $tidakHadirQuery->get()
            ->groupBy(function ($item) {
                return $item->user_id . '_' . $item->instansi_id;
            });

        // Gabungkan user dari presensi
        $this->rekapData = $grouped->map(function ($items, $key) use ($dari, $sampai) {
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
                'instansi' => $instansi->nama_instansi ?? 'N/A',
                'instansi_id' => $instansi->id ?? 0,
                'hadir' => $hadir,
                'izin' => $izin,
                'alpa' => $alpa,
            ];
        });

        // Tambahkan user yang hanya ada di tidak_hadirs
        foreach ($tidakHadirData as $key => $items) {
            if (!$this->rekapData->has($key)) {
                $firstItem = $items->first();
                $user = $firstItem->user;
                $instansi = $firstItem->instansi;

                $alpa = $this->hitungAlpa($user->id, $instansi->id, $dari, $sampai);

                $this->rekapData->put($key, [
                    'user_id' => $user->id,
                    'nama' => $user->name ?? 'N/A',
                    'instansi' => $instansi->nama_instansi ?? 'N/A',
                    'instansi_id' => $instansi->id ?? 0,
                    'hadir' => 0,
                    'izin' => 0,
                    'alpa' => $alpa,
                ]);
            }
        }

        $this->rekapData = $this->rekapData->values();

        // Urutkan berdasarkan instansi (PAUD → MI → MTS → MA → SMK → PATTA)
        $urutanInstansi = ['PAUD', 'MI', 'MTS', 'MA', 'SMK', 'PATTA'];

        $this->rekapData = $this->rekapData->sort(function ($a, $b) use ($urutanInstansi) {
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
        } elseif ($this->request->filled('instansi')) {
            $instansi = Instansi::find($this->request->instansi);
            $namaInstansi = $instansi ? $instansi->nama_instansi : 'Semua';
        }

        // Format periode
        $periode = '';
        if ($this->request->filled('dari_tanggal') && $this->request->filled('sampai_tanggal')) {
            $dariFormat = Carbon::parse($dari)->format('d F Y');
            $sampaiFormat = Carbon::parse($sampai)->format('d F Y');
            $periode = $dariFormat . ' s/d ' . $sampaiFormat;
        } else {
            $periode = 'Semua Periode';
        }

        // Ambil tahun ajaran
        $tapelKode = 'Semua Tahun Ajaran';
        if ($this->request->filled('tahun_ajaran')) {
            $tapel = Tapel::find($this->request->tahun_ajaran);
            if ($tapel) {
                $tapelKode = $tapel->kode;
            }
        } else {
            $tapel = Tapel::where('status', 'aktif')->first();
            if ($tapel) {
                $tapelKode = $tapel->kode;
            }
        }

        $this->filter = [
            'status' => $this->request->status ?: 'Semua',
            'instansi' => $namaInstansi,
            'periode' => $periode,
            'tahun_ajaran' => $tapelKode
        ];
    }

    public function array(): array
    {
        $data = [];

        // Header utama
        $data[] = ['REKAP BULANAN ABSENSI GURU & KARYAWAN', '', '', '', '', ''];
        $data[] = ['Yayasan Pendidikan Salafiyah', '', '', '', '', ''];
        $data[] = ['', '', '', '', '', ''];

        // Informasi Laporan
        $data[] = ['Informasi Laporan:', '', '', '', '', ''];
        $data[] = ['', 'Status', ': ' . $this->filter['status'], '', '', ''];
        $data[] = ['', 'Instansi', ': ' . $this->filter['instansi'], '', '', ''];
        $data[] = ['', 'Periode', ': ' . $this->filter['periode'], '', '', ''];
        $data[] = ['', 'Tahun Ajaran', ': ' . $this->filter['tahun_ajaran'], '', '', ''];
        $data[] = ['', '', '', '', '', ''];

        // Header tabel
        $data[] = ['No', 'Nama Guru/Karyawan', 'Instansi', 'Hadir', 'Izin', 'Alpa'];

        // Data rows
        foreach ($this->rekapData as $index => $item) {
            $data[] = [
                $index + 1,
                $item['nama'],
                $item['instansi'],
                $item['hadir'],
                $item['izin'],
                $item['alpa']
            ];
        }

        // Footer
        // $data[] = ['', '', '', '', '', ''];
        // $data[] = ['📌 Catatan:', '', '', '', '', ''];
        // $data[] = ['', 'Alpa = Tidak presensi sampai jam 14:00 + tidak ada izin (otomatis tercatat)', '', '', '', ''];
        $data[] = ['', '', '', '', '', ''];
        $data[] = ['', '', '', '', '', ''];
        $data[] = ['Total Data: ' . $this->rekapData->count() . ' record', '', '', '', '', ''];
        $data[] = ['Dicetak pada: ' . now()->format('d F Y, H:i:s'), '', '', '', '', ''];

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        // Merge cells untuk header
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:F2');
        $sheet->mergeCells('A4:F4');

        // Style header utama
        $sheet->getStyle('A1:A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '000000']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
        ]);

        // Border bawah header
        $sheet->getStyle('A3:F3')->applyFromArray([
            'borders' => ['bottom' => ['borderStyle' => Border::BORDER_THICK, 'color' => ['rgb' => '000000']]]
        ]);

        // Style info box
        $sheet->getStyle('A4:F8')->applyFromArray([
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F5F5F5']],
            'borders' => ['outline' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '999999']]]
        ]);

        $sheet->getStyle('A4')->applyFromArray(['font' => ['bold' => true, 'size' => 11]]);
        $sheet->getStyle('A5:A8')->applyFromArray(['font' => ['size' => 10]]);

        // Header tabel
        $headerRow = 10;
        $sheet->getStyle("A{$headerRow}:F{$headerRow}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '2D3748']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '1A202C']]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
        ]);

        // Data rows
        $lastDataRow = 10 + count($this->rekapData);
        if ($lastDataRow > 10) {
            $sheet->getStyle("A11:F{$lastDataRow}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E0']]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                'font' => ['size' => 10]
            ]);

            // Center alignment untuk kolom No dan angka
            $sheet->getStyle("A11:A{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("C11:C{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D11:F{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Striped rows
            for ($row = 11; $row <= $lastDataRow; $row++) {
                if ($row % 2 == 0) {
                    $sheet->getStyle("A{$row}:F{$row}")->applyFromArray([
                        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F7FAFC']]
                    ]);
                }

                // Highlight alpa > 0 dengan warna merah muda
                $alpaValue = $sheet->getCell("F{$row}")->getValue();
                if (is_numeric($alpaValue) && $alpaValue > 0) {
                    $sheet->getStyle("F{$row}")->applyFromArray([
                        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FEE']],
                        'font' => ['color' => ['rgb' => 'CC0000'], 'bold' => true]
                    ]);
                }
            }
        }

        // Footer catatan
        // $footerStartRow = $lastDataRow + 2;
        // $sheet->getStyle("A{$footerStartRow}")->applyFromArray([
        //     'font' => ['bold' => true, 'size' => 10],
        //     'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E6F7FF']],
        // ]);

        // $sheet->mergeCells("A{$footerStartRow}:F{$footerStartRow}");
        // $sheet->mergeCells("A" . ($footerStartRow + 1) . ":F" . ($footerStartRow + 1));

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(35);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(10);
        $sheet->getColumnDimension('E')->setWidth(10);
        $sheet->getColumnDimension('F')->setWidth(10);

        // Set row heights
        $sheet->getRowDimension(1)->setRowHeight(22);
        $sheet->getRowDimension(2)->setRowHeight(18);
        $sheet->getRowDimension(10)->setRowHeight(20);

        for ($row = 11; $row <= $lastDataRow; $row++) {
            $sheet->getRowDimension($row)->setRowHeight(18);
        }

        return [];
    }

    public function title(): string
    {
        return 'Rekap Bulanan';
    }
}
