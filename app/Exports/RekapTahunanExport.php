<?php

namespace App\Exports;

use App\Models\Presensi;
use App\Models\Instansi;
use App\Models\Tapel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\Auth;

class RekapTahunanExport implements FromArray, WithStyles, WithTitle
{
    protected $request;
    protected $rekapData;
    protected $filter;

    public function __construct($request)
    {
        $this->request = $request;
        $this->loadData();
    }

    private function loadData()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                $this->rekapData = collect([]);
                $this->filter = [
                    'status' => 'Semua',
                    'instansi' => 'Semua',
                    'tahun_ajaran' => 'Semua Tahun Ajaran'
                ];
                return;
            }

            $isOperatorInstansi = $user->hasRole('operator_instansi');

            // Query untuk mendapatkan presensi
            $query = Presensi::with(['user', 'instansi']);

            // Filter untuk operator instansi
            if ($isOperatorInstansi) {
                $instansiOperator = $user->instansi()->first();
                if ($instansiOperator) {
                    $query->where('instansi_id', $instansiOperator->id);
                }
            } elseif ($this->request->filled('instansi')) {
                $query->where('instansi_id', $this->request->instansi);
            }

            // Filter status
            if ($this->request->filled('status')) {
                $query->where('status', $this->request->status);
            }

            // Filter tahun ajaran
            if ($this->request->filled('tahun_ajaran')) {
                $tapel = Tapel::find($this->request->tahun_ajaran);
                if ($tapel) {
                    $dateRange = $tapel->getDateRange();
                    if ($dateRange && isset($dateRange['start']) && isset($dateRange['end'])) {
                        $query->whereBetween('tanggal', [$dateRange['start'], $dateRange['end']]);
                    }
                }
            }

            $presensiData = $query->get();

            // Hitung rekap per user
            $grouped = $presensiData->groupBy('user_id');

            $this->rekapData = $grouped->map(function ($items, $userId) {
                $firstItem = $items->first();

                // Safety check untuk relasi
                if (!$firstItem || !$firstItem->user || !$firstItem->instansi) {
                    return null;
                }

                return [
                    'user_id' => $userId,
                    'nama' => $firstItem->user->name ?? 'N/A',
                    'instansi' => $firstItem->instansi->nama_instansi ?? 'N/A',
                    'instansi_id' => $firstItem->instansi->id ?? 0,
                    'hadir' => $items->where('status', 'hadir')->count(),
                    'izin' => $items->where('status', 'izin')->count(),
                    'alpa' => $items->where('status', 'alpa')->count(),
                    'tanpa_ket' => $items->where('status', 'tanpa_keterangan')->count(),
                ];
            })->filter() // Hapus null values
            ->values();

            // Urutkan berdasarkan instansi (PAUD → MI → MTS → MA → SMK → PATTA)
            $urutanInstansi = ['PAUD', 'MI', 'MTS', 'MA', 'SMK', 'PATTA'];

            $this->rekapData = $this->rekapData->sort(function ($a, $b) use ($urutanInstansi) {
                $instansiA = strtoupper($a['instansi'] ?? '');
                $instansiB = strtoupper($b['instansi'] ?? '');

                $indexA = array_search($instansiA, $urutanInstansi);
                $indexB = array_search($instansiB, $urutanInstansi);

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

            // Ambil tahun ajaran
            $tahunAjaran = 'Semua Tahun Ajaran';
            if ($this->request->filled('tahun_ajaran')) {
                $tapel = Tapel::find($this->request->tahun_ajaran);
                $tahunAjaran = $tapel ? $tapel->kode : 'Semua Tahun Ajaran';
            }

            $this->filter = [
                'status' => $this->request->status ?: 'Semua',
                'instansi' => $namaInstansi,
                'tahun_ajaran' => $tahunAjaran
            ];
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('RekapTahunanExport LoadData Error: ' . $e->getMessage());
            $this->rekapData = collect([]);
            $this->filter = [
                'status' => 'Semua',
                'instansi' => 'Semua',
                'tahun_ajaran' => 'Semua Tahun Ajaran'
            ];
        }
    }

    public function array(): array
    {
        $data = [];

        // Header utama
        $data[] = ['REKAP TAHUNAN ABSENSI GURU & KARYAWAN', '', '', '', '', '', ''];
        $data[] = ['Yayasan Pendidikan Salafiyah', '', '', '', '', '', ''];
        $data[] = ['', '', '', '', '', '', ''];

        // Informasi Laporan
        $data[] = ['Informasi Laporan:', '', '', '', '', '', ''];
        $data[] = ['', 'Status', ': ' . $this->filter['status'], '', '', '', ''];
        $data[] = ['', 'Instansi', ': ' . $this->filter['instansi'], '', '', '', ''];
        $data[] = ['', 'Tahun Ajaran', ': ' . $this->filter['tahun_ajaran'], '', '', '', ''];
        $data[] = ['', '', '', '', '', '', ''];

        // Header tabel
        $data[] = ['No', 'Nama Guru/Karyawan', 'Instansi', 'Hadir', 'Izin', 'Alpa', 'Tanpa Ket'];

        // Data rows
        if ($this->rekapData && count($this->rekapData) > 0) {
            foreach ($this->rekapData as $index => $item) {
                $data[] = [
                    $index + 1,
                    $item['nama'] ?? 'N/A',
                    $item['instansi'] ?? 'N/A',
                    $item['hadir'] ?? 0,
                    $item['izin'] ?? 0,
                    $item['alpa'] ?? 0,
                    $item['tanpa_ket'] ?? 0
                ];
            }
        } else {
            $data[] = ['', 'Tidak ada data', '', '', '', '', ''];
        }

        // Footer
        $data[] = ['', '', '', '', '', '', ''];
        $data[] = ['Total Data: ' . ($this->rekapData ? count($this->rekapData) : 0) . ' record', '', '', '', '', '', ''];
        $data[] = ['Dicetak pada: ' . now()->format('d F Y, H:i:s'), '', '', '', '', '', ''];

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:G1');
        $sheet->mergeCells('A2:G2');
        $sheet->mergeCells('A4:G4');

        $sheet->getStyle('A1:A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '000000']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
        ]);

        $sheet->getStyle('A3:G3')->applyFromArray([
            'borders' => ['bottom' => ['borderStyle' => Border::BORDER_THICK, 'color' => ['rgb' => '000000']]]
        ]);

        $sheet->getStyle('A4:G7')->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F5F5F5']],
            'borders' => ['outline' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '999999']]]
        ]);

        $sheet->getStyle('A4')->applyFromArray(['font' => ['bold' => true, 'size' => 11]]);
        $sheet->getStyle('A5:A7')->applyFromArray(['font' => ['size' => 10]]);

        $headerRow = 9;
        $sheet->getStyle("A{$headerRow}:G{$headerRow}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => '000000']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E8E8E8']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '666666']]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
        ]);

        $dataRowCount = $this->rekapData ? count($this->rekapData) : 0;
        $lastDataRow = 9 + $dataRowCount;

        if ($dataRowCount > 0) {
            $sheet->getStyle("A10:G{$lastDataRow}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '999999']]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                'font' => ['size' => 10]
            ]);

            $sheet->getStyle("A10:A{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D10:G{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(18);
        $sheet->getColumnDimension('D')->setWidth(10);
        $sheet->getColumnDimension('E')->setWidth(10);
        $sheet->getColumnDimension('F')->setWidth(10);
        $sheet->getColumnDimension('G')->setWidth(12);

        $sheet->getRowDimension(1)->setRowHeight(20);
        $sheet->getRowDimension(2)->setRowHeight(16);
        $sheet->getRowDimension(9)->setRowHeight(18);

        for ($row = 10; $row <= $lastDataRow; $row++) {
            $sheet->getRowDimension($row)->setRowHeight(16);
        }

        return [];
    }

    public function title(): string
    {
        return 'Rekap Tahunan';
    }
}
