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

class RekapTahunanExport implements FromArray, WithStyles, WithTitle
{
    protected $request;
    protected $presensi;
    protected $filter;

    public function __construct($request)
    {
        $this->request = $request;
        $this->loadData();
    }

    private function loadData()
    {
        $query = Presensi::with(['user', 'instansi']);

        // Filter tahunan
        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }

        if ($this->request->filled('instansi')) {
            $query->where('instansi_id', $this->request->instansi);
        }

        if ($this->request->filled('tahun_ajaran')) {
            $tapel = Tapel::find($this->request->tahun_ajaran);
            if ($tapel) {
                $dateRange = $tapel->getDateRange();
                if ($dateRange) {
                    $query->whereBetween('tanggal', [$dateRange['start'], $dateRange['end']]);
                }
            }
        }

        $this->presensi = $query->orderBy('tanggal', 'desc')->get();

        // Ambil nama instansi untuk header
        $namaInstansi = 'Semua';
        if ($this->request->filled('instansi')) {
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
    }

    public function array(): array
    {
        $data = [];

        // Header utama (baris 1-2)
        $data[] = ['REKAP TAHUNAN ABSENSI GURU & KARYAWAN', '', '', '', '', '', ''];
        $data[] = ['Yayasan Salafiyah Kajen', '', '', '', '', '', ''];
        $data[] = ['', '', '', '', '', '', ''];

        // Informasi Laporan (baris 4-7)
        $data[] = ['Informasi Laporan:', '', '', '', '', '', ''];
        $data[] = ['', 'Status', ': ' . $this->filter['status'], '', '', '', '', ''];
        $data[] = ['', 'Instansi', ': ' . $this->filter['instansi'], '', '', '', '', ''];
        $data[] = ['', 'Tahun Ajaran', ': ' . $this->filter['tahun_ajaran'], '', '', '', '', ''];
        $data[] = ['', '', '', '', '', '', ''];

        // Header tabel (baris 9)
        $data[] = ['No', 'Tanggal', 'Nama Guru/Karyawan', 'Instansi', 'Datang', 'Pulang', 'Status'];

        // Data rows
        foreach ($this->presensi as $index => $item) {
            $data[] = [
                $index + 1,
                \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y'),
                $item->user->name ?? 'N/A',
                $item->instansi->nama_instansi ?? 'N/A',
                $item->datang ?? '-',
                $item->pulang ?? '-',
                ucfirst($item->status)
            ];
        }

        // Footer
        $data[] = ['', '', '', '', '', '', ''];
        $data[] = ['Total Data: ' . $this->presensi->count() . ' record', '', '', '', '', '', ''];
        $data[] = ['Dicetak pada: ' . now()->format('d F Y, H:i:s'), '', '', '', '', '', ''];
        // $data[] = ['', '', '', '', '', '', ''];
        // $data[] = ['', '', '', '', '', 'Mengetahui,', ''];
        // $data[] = ['', '', '', '', '', '', ''];
        // $data[] = ['', '', '', '', '', '', ''];
        // $data[] = ['', '', '', '', '', '', ''];
        // $data[] = ['', '', '', '', '', '_________________________', ''];
        // $data[] = ['', '', '', '', '', 'Kepala Sekolah/Pimpinan', ''];

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        // Style yang sama dengan bulanan, hanya ganti beberapa label
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
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F5F5F5']],
            'borders' => ['outline' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '999999']]]
        ]);

        $sheet->getStyle('A4')->applyFromArray(['font' => ['bold' => true, 'size' => 11]]);
        $sheet->getStyle('A5:A7')->applyFromArray(['font' => ['size' => 10]]);

        $headerRow = 9;
        $sheet->getStyle("A{$headerRow}:G{$headerRow}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => '000000']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E8E8E8']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '666666']]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
        ]);

        $lastDataRow = 9 + count($this->presensi);
        if ($lastDataRow > 9) {
            $sheet->getStyle("A10:G{$lastDataRow}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '999999']]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                'font' => ['size' => 10]
            ]);

            $sheet->getStyle("A10:A{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B10:B{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("E10:E{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("F10:F{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("G10:G{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        $footerStartRow = $lastDataRow + 2;
        $footerEndRow = $footerStartRow + 1;
        $signatureRow = $footerEndRow + 2;

        $sheet->getStyle("A{$footerStartRow}:A{$footerEndRow}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 10]
        ]);

        $sheet->getStyle("F{$signatureRow}")->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'font' => ['size' => 10]
        ]);

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(12);
        $sheet->getColumnDimension('C')->setWidth(28);
        $sheet->getColumnDimension('D')->setWidth(18);
        $sheet->getColumnDimension('E')->setWidth(10);
        $sheet->getColumnDimension('F')->setWidth(10);
        $sheet->getColumnDimension('G')->setWidth(10);

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
