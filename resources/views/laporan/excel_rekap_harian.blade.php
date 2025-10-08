<table>
    <tr>
        <td colspan="7" style="text-align: center; font-weight: bold; font-size: 14px;">
            REKAP HARIAN ABSENSI GURU & KARYAWAN
        </td>
    </tr>
    <tr>
        <td colspan="7"></td>
    </tr>
    <tr>
        <td colspan="7">
            Filter: Status: {{ $filter['status'] }} | Instansi: {{ $filter['instansi'] }} | Tanggal: {{ \Carbon\Carbon::parse($filter['tanggal'])->format('d-m-Y') }}
        </td>
    </tr>
    <tr>
        <td colspan="7"></td>
    </tr>
    <tr style="font-weight: bold; background-color: #f2f2f2;">
        <td>No</td>
        <td>Tanggal</td>
        <td>Nama Guru/Karyawan</td>
        <td>Instansi</td>
        <td>Jam Datang</td>
        <td>Jam Pulang</td>
        <td>Status</td>
    </tr>
    @forelse ($presensi as $item)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
        <td>{{ $item->user->name ?? 'N/A' }}</td>
        <td>{{ $item->instansi->nama_instansi ?? 'N/A' }}</td>
        <td>{{ $item->datang ?? '-' }}</td>
        <td>{{ $item->pulang ?? '-' }}</td>
        <td>{{ ucfirst($item->status) }}</td>
    </tr>
    @empty
    <tr>
        <td colspan="7" style="text-align: center;">Tidak ada data presensi</td>
    </tr>
    @endforelse
    <tr>
        <td colspan="7"></td>
    </tr>
    <tr>
        <td colspan="7">Total Data: {{ $presensi->count() }} record</td>
    </tr>
    <tr>
        <td colspan="7">Dicetak pada: {{ now()->format('d-m-Y H:i:s') }}</td>
    </tr>
</table>
