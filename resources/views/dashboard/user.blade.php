@extends('layout.main')
@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Dashboard Guru</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">Dashboard</div>
            </div>
        </div>

        <!-- Notifikasi Penting -->
        {{-- <div class="alert alert-warning alert-has-icon alert-dismissible show fade">
            <div class="alert-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
            <div class="alert-body">
                <div class="alert-title">Perhatian!</div>
                ⚠️ Kamu belum melakukan absen pulang di SD Harapan hari ini
            </div>
            <button class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div> --}}

        <!-- Card Status Kehadiran Hari Ini -->
        <div class="row">
            <div class="col-12">
                <h6 class="mb-3 font-weight-bold text-dark">Status Kehadiran Hari Ini</h6>
            </div>

            <!-- Container untuk instansi cards dengan layout responsif -->
            <div class="col-12">
                <div class="instansi-container">
                    <!-- Instansi 1: SD Harapan -->
                    <div class="col-12">
                        <div class="instansi-container">
                            @forelse($presensiHariIni as $presensi)
                                <div class="instansi-card-wrapper">
                                    <div class="card card-custom shadow-sm">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div>
                                                    <h6 class="font-weight-bold text-primary mb-1">
                                                        {{ $presensi->instansi->nama_instansi ?? 'N/A' }}
                                                    </h6>
                                                    <small class="text-muted">
                                                        {{ \Carbon\Carbon::parse($presensi->tanggal)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                                    </small>
                                                </div>

                                                @if ($presensi->jam_pulang)
                                                    <span class="badge badge-success badge-lg">
                                                        <i class="fa-solid fa-check"></i> Hadir
                                                    </span>
                                                @else
                                                    <span class="badge badge-warning badge-lg">
                                                        <i class="fa-solid fa-hourglass-half"></i> Belum Pulang
                                                    </span>
                                                @endif

                                            </div>

                                            <div class="attendance-time-row">
                                                <div class="time-item">
                                                    <i class="fa-solid fa-right-to-bracket text-success"></i>
                                                    <div class="time-detail">
                                                        <small class="text-muted d-block">Jam Datang</small>
                                                        <strong class="text-dark">
                                                            {{ $presensi->datang ? \Carbon\Carbon::parse($presensi->datang)->format(format: 'H:i') . ' WIB' : '-- : --' }}
                                                        </strong>
                                                    </div>
                                                </div>
                                                <div class="time-divider"></div>
                                                <div class="time-item">
                                                    <i class="fa-solid fa-right-from-bracket text-danger"></i>
                                                    <div class="time-detail">
                                                        <small class="text-muted d-block">Jam Pulang</small>
                                                        <strong
                                                            class="{{ $presensi->pulang ? 'text-dark' : 'text-muted' }}">
                                                            {{ $presensi->pulang ? \Carbon\Carbon::parse($presensi->pulang)->format('H:i') . ' WIB' : '-- : --' }}
                                                        </strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="fa-solid fa-info-circle"></i> Belum ada presensi hari ini
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="row">
            <div class="col-12">
                <h6 class="mb-3 font-weight-bold text-dark">Jadwal Hari Ini</h6>
            </div>
            @forelse($jadwalHariIni as $jadwal)
                <div class="col-12 mb-3">
                    <div class="card card-jadwal shadow-sm border-0">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <div class="col-5">
                                    <h6 class="font-weight-bold text-dark mb-0">
                                        {{ $jadwal->instansi->nama_instansi ?? 'N/A' }}
                                    </h6>
                                </div>
                                <div class="col-7">
                                    <div class="row">
                                        <div class="col-6 text-center">
                                            <div class="time-box bg-success-light">
                                                <small class="text-success d-block mb-1">Masuk</small>
                                                <strong class="text-dark">
                                                    {{ $jadwal->datang ? \Carbon\Carbon::parse($jadwal->datang)->format('H:i') : '--:--' }}
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="col-6 text-center">
                                            <div class="time-box bg-danger-light">
                                                <small class="text-danger d-block mb-1">Pulang</small>
                                                <strong class="text-dark">
                                                    {{ $jadwal->pulang ? \Carbon\Carbon::parse($jadwal->pulang)->format('H:i') : '--:--' }}
                                                </strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 mb-3">
                    <div class="alert alert-info">
                        <i class="fa-solid fa-calendar-xmark"></i> Tidak ada jadwal untuk hari ini
                    </div>
                </div>
            @endforelse


        </div> --}}


        <!-- Riwayat Absensi & Rekap Bulanan -->
        <div class="row">
            <!-- Riwayat Absensi Terakhir -->
            <div class="col-12 col-lg-7 mb-4">
                <div class="card card-custom shadow-sm">
                    <div class="card-header">
                        <h6 class="font-weight-bold text-dark mb-0">
                            Riwayat Absensi
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled history-list">
                            @forelse($presensiMingguIni->groupBy('tanggal') as $tanggal => $presensiPerHari)
                                <li class="history-item">
                                    <div class="history-date">
                                        <strong>{{ \Carbon\Carbon::parse($tanggal)->format('d M') }}</strong>
                                        <small>{{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('dddd') }}</small>
                                    </div>
                                    <div class="history-detail">
                                        <div class="history-location">
                                            {{-- Gabungkan nama instansi dengan separator " - " --}}
                                            {{ $presensiPerHari->pluck('instansi.nama_instansi')->filter()->implode(' - ') }}
                                        </div>

                                        {{-- Tentukan status berdasarkan semua presensi di hari itu --}}
                                        @php
                                            $semuaHadir = $presensiPerHari->every(fn($p) => $p->datang && $p->pulang);
                                            $adaIzin = $presensiPerHari->contains(
                                                fn($p) => $p->keterangan == 'izin' || $p->status == 'izin',
                                            );
                                            $adaBelumPulang = $presensiPerHari->contains(
                                                fn($p) => $p->datang && !$p->pulang,
                                            );
                                            $semuaAlpha = $presensiPerHari->every(fn($p) => !$p->datang);
                                        @endphp

                                        @if ($semuaHadir)
                                            <div class="history-status status-present">
                                                <i class="fa-solid fa-circle-check"></i> Hadir
                                            </div>
                                        @elseif($adaIzin)
                                            <div class="history-status status-leave">
                                                <i class="fa-solid fa-file-lines"></i> Izin
                                            </div>
                                        @elseif($adaBelumPulang)
                                            <div class="history-status status-leave">
                                                <i class="fa-solid fa-clock"></i> Belum Pulang
                                            </div>
                                        @elseif($semuaAlpha)
                                            <div class="history-status status-absent">
                                                <i class="fa-solid fa-circle-xmark"></i> Alpha
                                            </div>
                                        @else
                                            <div class="history-status status-leave">
                                                <i class="fa-solid fa-clock"></i> Sebagian Hadir
                                            </div>
                                        @endif
                                    </div>
                                </li>
                            @empty
                                <li class="text-center text-muted py-4">
                                    <i class="fa-solid fa-inbox"></i><br>
                                    Belum ada riwayat presensi minggu ini
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Rekap Bulanan -->
            <div class="col-12 col-lg-5 mb-4">
                <div class="card card-custom shadow-sm card-recap-monthly">
                    <div class="card-header border-0">
                        <h6 class="font-weight-bold text-dark mb-0">
                            Rekap Bulan Ini
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="monthly-recap">
                            <div class="recap-item">
                                <div class="recap-icon text-success">
                                    <i class="fa-solid fa-circle-check"></i>
                                </div>
                                <div class="recap-info">
                                    <h3 class="mb-0 text-dark">18x</h3>
                                    <small class="text-muted">Hadir</small>
                                </div>
                            </div>
                            <div class="recap-divider"></div>
                            <div class="recap-item">
                                <div class="recap-icon text-warning">
                                    <i class="fa-solid fa-file-lines"></i>
                                </div>
                                <div class="recap-info">
                                    <h3 class="mb-0 text-dark">3x</h3>
                                    <small class="text-muted">Izin</small>
                                </div>
                            </div>
                            <div class="recap-divider"></div>
                            <div class="recap-item">
                                <div class="recap-icon text-danger">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                </div>
                                <div class="recap-info">
                                    <h3 class="mb-0 text-dark">1x</h3>
                                    <small class="text-muted">Alpha</small>
                                </div>
                            </div>
                        </div>
                        <div class="progress mt-4" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 81.8%"
                                aria-valuenow="81.8" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="d-block mt-2 text-muted">Tingkat kehadiran: <strong
                                class="text-dark">81.8%</strong></small>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('style')
    <style>
        .card-jadwal {
            border-radius: 12px;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            transition: all 0.3s ease;
            border-left: 4px solid #007bff;
        }

        .card-jadwal:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12) !important;
        }

        .time-box {
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .time-box:hover {
            transform: scale(1.05);
        }

        .bg-success-light {
            background-color: #d4edda;
        }

        .bg-danger-light {
            background-color: #f8d7da;
        }

        .instansi-container {
            display: grid;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        /* Mobile: 1 column, full width */
        @media (max-width: 575.98px) {
            .instansi-container {
                grid-template-columns: 1fr;
            }
        }

        /* Tablet: 2 columns untuk 2-3 cards, 1 column untuk 1 card */
        @media (min-width: 576px) and (max-width: 991.98px) {
            .instansi-container {
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            }
        }

        /* Desktop: Maksimal 3 columns */
        @media (min-width: 992px) {
            .instansi-container {
                grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
                gap: 1.25rem;
            }

            /* Jika hanya 1 card, buat tidak terlalu lebar */
            .instansi-container:has(.instansi-card-wrapper:only-child) {
                grid-template-columns: minmax(320px, 600px);
            }

            /* Jika 2 cards, buat seimbang */
            .instansi-container:has(.instansi-card-wrapper:nth-child(2):last-child) {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .instansi-card-wrapper {
            min-height: 160px;
        }

        /* Card Custom Styling */
        .card-custom {
            border: 1px solid #e4e6fc;
            border-radius: 12px;
            transition: all 0.3s ease;
            height: 100%;
            background-color: #ffffff;
        }

        .card-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(103, 119, 239, 0.12) !important;
            border-color: #d0d5f6;
        }

        .card-custom .card-header {
            background-color: #fff;
            border-bottom: 1px solid #f0f1f5;
            padding: 1rem 1.25rem;
            border-radius: 12px 12px 0 0;
        }

        .card-custom .card-body {
            padding: 1.25rem;
        }

        /* Badge Styling */
        .badge-lg {
            padding: 0.45rem 0.75rem;
            font-size: 0.8rem;
            font-weight: 600;
            border-radius: 6px;
        }

        /* Attendance Time Row */
        .attendance-time-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #f8f9fc;
            padding: 1rem;
            border-radius: 10px;
        }

        .time-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex: 1;
        }

        .time-item i {
            font-size: 1.5rem;
        }

        .time-detail small {
            font-size: 0.75rem;
        }

        .time-detail strong {
            font-size: 1rem;
        }

        .time-divider {
            width: 2px;
            height: 40px;
            background-color: #dee2e6;
            margin: 0 1rem;
        }

        /* Comparison Box */
        .comparison-box {
            display: flex;
            gap: 1rem;
            flex-direction: column;
        }

        .comparison-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            background-color: #f8f9fc;
        }

        .comparison-item.today {
            border-left: 4px solid #6777ef;
        }

        .comparison-item.yesterday {
            border-left: 4px solid #95a5a6;
        }

        .comparison-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
        }

        .comparison-time {
            font-size: 1.1rem;
            font-weight: 700;
            color: #333;
        }

        /* History List */
        .history-list {
            margin: 0;
        }

        .history-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #f0f1f5;
            transition: background-color 0.2s;
        }

        .history-item:last-child {
            border-bottom: none;
        }

        .history-item:hover {
            background-color: #f8f9fc;
        }

        .history-date {
            min-width: 70px;
            text-align: center;
            padding-right: 1rem;
            border-right: 2px solid #e4e6fc;
        }

        .history-date strong {
            display: block;
            font-size: 1rem;
            color: #333;
        }

        .history-date small {
            color: #6c757d;
            font-size: 0.75rem;
        }

        .history-detail {
            flex: 1;
            padding-left: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .history-location {
            font-weight: 600;
            color: #333;
        }

        .history-status {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-present {
            background-color: #d4edda;
            color: #155724;
        }

        .status-leave {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-absent {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Monthly Recap - White Background */
        /* Fix untuk card recap monthly agar tidak terlalu tinggi */
        .card-recap-monthly {
            height: fit-content;
            align-self: start;
        }

        .card-recap-monthly .card-body {
            padding: 1.5rem 1.25rem;
        }

        .card-recap-monthly .card-header {
            background-color: #f8f9fc;
        }

        .monthly-recap {
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        .recap-item {
            text-align: center;
        }

        .recap-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .recap-info h3 {
            font-size: 2rem;
            font-weight: 700;
        }

        .recap-info small {
            font-size: 0.85rem;
        }

        .recap-divider {
            width: 1px;
            height: 60px;
            background-color: #e4e6fc;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .attendance-time-row {
                flex-direction: column;
                gap: 0.75rem;
                padding: 0.875rem;
            }

            .time-divider {
                width: 100%;
                height: 1px;
                margin: 0;
            }

            .time-item {
                width: 100%;
                padding: 0.5rem;
                background-color: #fff;
                border-radius: 6px;
            }

            .monthly-recap {
                flex-direction: column;
                gap: 1.5rem;
                padding: 0.5rem 0;
            }

            .recap-divider {
                width: 80%;
                height: 1px;
            }

            .badge-lg {
                font-size: 0.75rem;
                padding: 0.35rem 0.6rem;
            }
        }

        @media (max-width: 576px) {
            .card-custom .card-body {
                padding: 1rem;
            }

            .history-item {
                padding: 0.875rem;
                flex-wrap: wrap;
            }

            .history-date {
                min-width: 60px;
            }

            .history-detail {
                flex-wrap: wrap;
                gap: 0.5rem;
            }
        }
    </style>
@endpush

@push('script')
@endpush
