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
        <div class="alert alert-warning alert-has-icon alert-dismissible show fade">
            <div class="alert-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
            <div class="alert-body">
                <div class="alert-title">Perhatian!</div>
                ⚠️ Kamu belum melakukan absen pulang di SD Harapan hari ini
            </div>
            <button class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>

        <!-- Card Status Kehadiran Hari Ini -->
        <div class="row">
            <div class="col-12">
                <h6 class="mb-3 font-weight-bold text-dark">Status Kehadiran Hari Ini</h6>
            </div>

            <!-- Container untuk instansi cards dengan layout responsif -->
            <div class="col-12">
                <div class="instansi-container">
                    <!-- Instansi 1: SD Harapan -->
                    <div class="instansi-card-wrapper">
                        <div class="card card-custom shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="font-weight-bold text-primary mb-1">SD Harapan</h6>
                                        <small class="text-muted">Selasa, 7 Oktober 2025</small>
                                    </div>
                                    <span class="badge badge-warning badge-lg">⏳ Belum Pulang</span>
                                </div>

                                <div class="attendance-time-row">
                                    <div class="time-item">
                                        <i class="fa-solid fa-right-to-bracket text-success"></i>
                                        <div class="time-detail">
                                            <small class="text-muted d-block">Jam Datang</small>
                                            <strong class="text-dark">07:22 WIB</strong>
                                        </div>
                                    </div>
                                    <div class="time-divider"></div>
                                    <div class="time-item">
                                        <i class="fa-solid fa-right-from-bracket text-danger"></i>
                                        <div class="time-detail">
                                            <small class="text-muted d-block">Jam Pulang</small>
                                            <strong class="text-muted">-- : --</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Instansi 2: SMP Cendekia -->
                    <div class="instansi-card-wrapper">
                        <div class="card card-custom shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="font-weight-bold text-primary mb-1">SMP Cendekia</h6>
                                        <small class="text-muted">Selasa, 7 Oktober 2025</small>
                                    </div>
                                    <span class="badge badge-success badge-lg">✅ Hadir</span>
                                </div>

                                <div class="attendance-time-row">
                                    <div class="time-item">
                                        <i class="fa-solid fa-right-to-bracket text-success"></i>
                                        <div class="time-detail">
                                            <small class="text-muted d-block">Jam Datang</small>
                                            <strong class="text-dark">07:15 WIB</strong>
                                        </div>
                                    </div>
                                    <div class="time-divider"></div>
                                    <div class="time-item">
                                        <i class="fa-solid fa-right-from-bracket text-danger"></i>
                                        <div class="time-detail">
                                            <small class="text-muted d-block">Jam Pulang</small>
                                            <strong class="text-dark">15:30 WIB</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Instansi 3: SMA Bintang (Contoh untuk 3 instansi) -->
                    <!-- Uncomment jika ada 3 instansi -->
                    <!--
                        <div class="instansi-card-wrapper">
                            <div class="card card-custom shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h6 class="font-weight-bold text-primary mb-1">SMA Bintang</h6>
                                            <small class="text-muted">Selasa, 7 Oktober 2025</small>
                                        </div>
                                        <span class="badge badge-success badge-lg">✅ Hadir</span>
                                    </div>

                                    <div class="attendance-time-row">
                                        <div class="time-item">
                                            <i class="fa-solid fa-right-to-bracket text-success"></i>
                                            <div class="time-detail">
                                                <small class="text-muted d-block">Jam Datang</small>
                                                <strong class="text-dark">07:00 WIB</strong>
                                            </div>
                                        </div>
                                        <div class="time-divider"></div>
                                        <div class="time-item">
                                            <i class="fa-solid fa-right-from-bracket text-danger"></i>
                                            <div class="time-detail">
                                                <small class="text-muted d-block">Jam Pulang</small>
                                                <strong class="text-dark">14:00 WIB</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        -->
                </div>
            </div>
        </div>

        <!-- Riwayat Absensi & Rekap Bulanan -->
        <div class="row">
            <!-- Riwayat Absensi Terakhir -->
            <div class="col-12 col-lg-7 mb-4">
                <div class="card card-custom shadow-sm">
                    <div class="card-header">
                        <h6 class="font-weight-bold mb-0">
                            <i class="fa-solid fa-clock-rotate-left text-primary"></i> Riwayat Absensi (5 Hari Terakhir)
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled history-list">
                            <li class="history-item">
                                <div class="history-date">
                                    <strong>7 Okt</strong>
                                    <small>Selasa</small>
                                </div>
                                <div class="history-detail">
                                    <div class="history-location">SD Harapan</div>
                                    <div class="history-status status-present">
                                        <i class="fa-solid fa-circle-check"></i> Hadir
                                    </div>
                                </div>
                            </li>
                            <li class="history-item">
                                <div class="history-date">
                                    <strong>6 Okt</strong>
                                    <small>Senin</small>
                                </div>
                                <div class="history-detail">
                                    <div class="history-location">SMP Cendekia</div>
                                    <div class="history-status status-present">
                                        <i class="fa-solid fa-circle-check"></i> Hadir
                                    </div>
                                </div>
                            </li>
                            <li class="history-item">
                                <div class="history-date">
                                    <strong>5 Okt</strong>
                                    <small>Minggu</small>
                                </div>
                                <div class="history-detail">
                                    <div class="history-location">SD Harapan</div>
                                    <div class="history-status status-leave">
                                        <i class="fa-solid fa-file-lines"></i> Izin
                                    </div>
                                </div>
                            </li>
                            <li class="history-item">
                                <div class="history-date">
                                    <strong>4 Okt</strong>
                                    <small>Sabtu</small>
                                </div>
                                <div class="history-detail">
                                    <div class="history-location">SMP Cendekia</div>
                                    <div class="history-status status-present">
                                        <i class="fa-solid fa-circle-check"></i> Hadir
                                    </div>
                                </div>
                            </li>
                            <li class="history-item">
                                <div class="history-date">
                                    <strong>3 Okt</strong>
                                    <small>Jumat</small>
                                </div>
                                <div class="history-detail">
                                    <div class="history-location">SD Harapan</div>
                                    <div class="history-status status-absent">
                                        <i class="fa-solid fa-circle-xmark"></i> Alpha
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Rekap Bulanan -->
            <div class="col-12 col-lg-5 mb-4">
                <div class="card card-custom shadow-sm card-recap-monthly">
                    <div class="card-header border-0">
                        <h6 class="font-weight-bold mb-0">
                            <i class="fa-solid fa-calendar-days text-primary"></i> Rekap Bulan Ini
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
        /* ========================================
               INSTANSI CARDS - MOBILE FIRST LAYOUT
               ======================================== */

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

{{-- INI USER --}}
