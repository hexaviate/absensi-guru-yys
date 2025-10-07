<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Tahunan Absensi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .header h2 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .header p {
            margin: 5px 0 0 0;
            font-size: 12px;
        }

        .filter-info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 10px;
        }

        th, td {
            border: 1px solid #333;
            padding: 6px 4px;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background-color: #e9ecef;
            font-weight: bold;
            text-align: center;
            font-size: 9px;
        }

        .center {
            text-align: center;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }

        .signature {
            float: right;
            text-align: center;
            width: 200px;
            margin-top: 20px;
        }

        .page-break {
            page-break-after: always;
        }

        @page {
            margin: 1cm;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Rekap Tahunan Absensi Guru & Karyawan</h2>
        <p>Yayasan Pendidikan Salafiyah</p>
    </div>

    <div class="filter-info">
        <strong>Informasi Laporan:</strong><br>
        <table style="border: none; font-size: 11px; margin: 5px 0;">
            <tr style="border: none;">
                <td style="border: none; width: 100px; padding: 2px 0;">Status</td>
                <td style="border: none; padding: 2px 0;">: {{ $filter['status'] }}</td>
            </tr>
            <tr style="border: none;">
                <td style="border: none; padding: 2px 0;">Instansi</td>
                <td style="border: none; padding: 2px 0;">: {{ $filter['instansi'] }}</td>
            </tr>
            <tr style="border: none;">
                <td style="border: none; padding: 2px 0;">Tahun Ajaran</td>
                <td style="border: none; padding: 2px 0;">: {{ $filter['tahun_ajaran'] }}</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="12%">Tanggal</th>
                <th width="28%">Nama Guru/Karyawan</th>
                <th width="22%">Instansi</th>
                <th width="12%">Datang</th>
                <th width="12%">Pulang</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($presensi as $item)
                <tr>
                    <td class="center">{{ $loop->iteration }}</td>
                    <td class="center">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                    <td>{{ $item->user->name ?? 'N/A' }}</td>
                    <td>{{ $item->instansi->nama_instansi ?? 'N/A' }}</td>
                    <td class="center">{{ $item->datang ?? '-' }}</td>
                    <td class="center">{{ $item->pulang ?? '-' }}</td>
                    <td class="center">{{ ucfirst($item->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="center" style="padding: 20px; font-style: italic;">
                        Tidak ada data presensi untuk tahun ajaran yang dipilih
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div style="float: left;">
            <strong>Total Data:</strong> {{ $presensi->count() }} record<br>
            <strong>Dicetak pada:</strong> {{ now()->format('d F Y, H:i:s') }}
        </div>

        {{-- <div class="signature">
            <p>Mengetahui,</p>
            <br><br><br>
            <p>_________________________</p>
            <p>Kepala Sekolah/Pimpinan</p>
        </div> --}}

    </div>
</body>
</html>
