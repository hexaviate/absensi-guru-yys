<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Tahunan Absensi</title>
    <style>
        @page {
            margin: 15mm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        .header h2 {
            margin: 3px 0;
            font-size: 14px;
            font-weight: bold;
        }
        .header h3 {
            margin: 3px 0;
            font-size: 12px;
            font-weight: normal;
        }
        .header hr {
            border: none;
            border-top: 2px solid #000;
            margin: 8px 0;
        }
        .info-box {
            background-color: #f5f5f5;
            padding: 8px 10px;
            margin-bottom: 12px;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
        .info-box table {
            width: 100%;
        }
        .info-box td {
            padding: 2px 0;
            font-size: 9px;
        }
        .info-box td:first-child {
            width: 25%;
            font-weight: bold;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        table.data-table th {
            background-color: #2d3748;
            color: white;
            border: 1px solid #1a202c;
            padding: 8px 4px;
            text-align: center;
            font-weight: bold;
            font-size: 9px;
        }
        table.data-table td {
            border: 1px solid #cbd5e0;
            padding: 6px 4px;
            font-size: 9px;
        }
        table.data-table tbody tr:nth-child(even) {
            background-color: #f7fafc;
        }
        /* Kolom No */
        table.data-table td:nth-child(1) {
            text-align: center;
            width: 5%;
        }
        /* Kolom Nama */
        table.data-table td:nth-child(2) {
            width: 40%;
        }
        /* Kolom Instansi */
        table.data-table td:nth-child(3) {
            width: 25%;
            text-align: center;
        }
        /* Kolom Hadir, Izin, Alpa */
        table.data-table td:nth-child(4),
        table.data-table td:nth-child(5),
        table.data-table td:nth-child(6) {
            text-align: center;
            width: 10%;
        }
        /* Highlight untuk Alpa > 0 */
        .alpa-warning {
            background-color: #fee;
            color: #c00;
            font-weight: bold;
        }
        .footer {
            margin-top: 15px;
            font-size: 8px;
            color: #666;
        }
        .empty-state {
            text-align: center;
            padding: 15px;
            color: #999;
        }
        .formula-note {
            margin-top: 10px;
            padding: 8px;
            background-color: #e6f7ff;
            border-left: 4px solid #1890ff;
            font-size: 8px;
            line-height: 1.4;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>REKAP TAHUNAN ABSENSI GURU & KARYAWAN</h2>
        <h3>Yayasan Pendidikan Salafiyah</h3>
        <hr>
    </div>

    <div class="info-box">
        <table>
            <tr>
                <td>Status</td>
                <td>: {{ $filter['status'] }}</td>
            </tr>
            <tr>
                <td>Instansi</td>
                <td>: {{ $filter['instansi'] }}</td>
            </tr>
            <tr>
                <td>Tahun Ajaran</td>
                <td>: {{ $filter['tahun_ajaran'] }}</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Guru/Karyawan</th>
                <th>Instansi</th>
                <th>Hadir</th>
                <th>Izin</th>
                <th>Alpa</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rekap as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item['nama'] }}</td>
                <td>{{ $item['instansi'] }}</td>
                <td>{{ $item['hadir'] }}</td>
                <td>{{ $item['izin'] }}</td>
                <td class="{{ $item['alpa'] > 0 ? 'alpa-warning' : '' }}">
                    {{ $item['alpa'] }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="empty-state">Tidak ada data untuk ditampilkan</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="formula-note">
        <strong>📌 Catatan Perhitungan:</strong><br>
        • <strong>Hadir</strong> = Total kehadiran yang tercatat dalam sistem<br>
        • <strong>Izin</strong> = Total izin yang diajukan dan disetujui<br>
        • <strong>Alpa</strong> = Tidak presensi sampai jam 14:00 + tidak ada izin (otomatis tercatat di sistem)<br>
        • Sabtu & Minggu tetap dihitung sebagai hari kerja (kecuali hari libur resmi)<br>
        • Khusus SMK, Jumat adalah hari libur
    </div>

    <div class="footer">
        <strong>Total Data:</strong> {{ count($rekap) }} record<br>
        <strong>Dicetak pada:</strong> {{ \Carbon\Carbon::now()->format('d F Y, H:i:s') }}
    </div>
</body>
</html>
