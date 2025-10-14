<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Tahunan Absensi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 16px;
        }
        .header h3 {
            margin: 5px 0;
            font-size: 14px;
            font-weight: normal;
        }
        .info-box {
            background-color: #f5f5f5;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
        }
        .info-box table {
            width: 100%;
        }
        .info-box td {
            padding: 3px 0;
        }
        .info-box strong {
            font-weight: bold;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.data-table th {
            background-color: #e8e8e8;
            border: 1px solid #666;
            padding: 8px;
            text-align: center;
            font-weight: bold;
        }
        table.data-table td {
            border: 1px solid #999;
            padding: 6px;
        }
        table.data-table td:nth-child(1) {
            text-align: center;
            width: 5%;
        }
        table.data-table td:nth-child(2) {
            width: 35%;
        }
        table.data-table td:nth-child(3) {
            width: 25%;
        }
        table.data-table td:nth-child(4),
        table.data-table td:nth-child(5),
        table.data-table td:nth-child(6),
        table.data-table td:nth-child(7) {
            text-align: center;
            width: 8.75%;
        }
        .footer {
            margin-top: 20px;
            font-size: 10px;
        }
        hr {
            border: none;
            border-top: 2px solid #000;
            margin: 10px 0;
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
                <td width="20%"><strong>Status</strong></td>
                <td>: {{ $filter['status'] }}</td>
            </tr>
            <tr>
                <td><strong>Instansi</strong></td>
                <td>: {{ $filter['instansi'] }}</td>
            </tr>
            <tr>
                <td><strong>Tahun Ajaran</strong></td>
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
                <th>Tanpa Ket</th>
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
                <td>{{ $item['alpa'] }}</td>
                <td>{{ $item['tanpa_ket'] }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <strong>Total Data:</strong> {{ count($rekap) }} record<br>
        <strong>Dicetak pada:</strong> {{ \Carbon\Carbon::now()->format('d F Y, H:i:s') }}
    </div>
</body>
</html>
