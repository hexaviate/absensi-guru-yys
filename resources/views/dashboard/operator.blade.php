@extends('layout.main')
@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Dashboard Operator Instansi</h1>
        </div>

        <!-- Summary Cards -->
        <div class="row">
            <div class="col-lg-4 col-md-6 col-12 mb-3">
                <div class="stats-card">
                    <div class="card-body">
                        <h2 class="stats-number">{{ $totalGuruInstansi }}</h2>
                        <p class="stats-label">Total Guru</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12 mb-3">
                <div class="stats-card">
                    <div class="card-body">
                        <h2 class="stats-number">{{ $totalGuruHadir }}</h2>
                        <p class="stats-label">Kehadiran Hari Ini</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12 mb-3">
                <div class="stats-card">
                    <div class="card-body">
                        <h2 class="stats-number">{{ $persentaseHadir }} %</h2>
                        <p class="stats-label">Tingkat Kehadiran</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Chart Statistik Kehadiran -->
            <div class="col-lg-8 col-md-12 col-12 col-sm-12">
                {{-- <div class="card">
                    <div class="card-header">
                        <h4>Statistik Kehadiran Guru</h4>
                    </div>
                    <div class="card-body">
                        <div id="chartKehadiran"></div>
                    </div>
                </div> --}}

                <!-- Tabel Riwayat Presensi -->
                <div class="card custom-card">
                    <div class="card-header">
                        <h4>Riwayat Presensi Hari Ini</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Foto</th>
                                        <th>Nama Guru</th>
                                        <th>Waktu Hadir</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($guruHadirHariIni as $item)
                                        <tr>
                                            <td>
                                                <img src="{{ asset('foto_presensi/' . $item->user->foto) }}"
                                                    alt="foto {{ $item->user->name }}" class="rounded-circle" width="45"
                                                    height="45">
                                            </td>
                                            <td>{{ $item->user->name ?? '-' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->jam_datang)->format('H:i') }}</td>
                                            <td>
                                                <span class="badge badge-success">Hadir</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Belum Ada Yang Absensi</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Umum Instansi -->
            <div class="col-lg-4 col-md-12 col-12 col-sm-12">
                <!-- Ringkasan Kehadiran Hari Ini -->
                <div class="card custom-card">
                    <div class="card-header">
                        <h4>Ringkasan Kehadiran</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="font-weight-bold">
                                    Hadir
                                </span>
                                <span class="badge badge-success badge-pill">{{ $totalGuruHadir }}</span>
                            </div>
                           <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-success" role="progressbar"
                                    style="width: {{ $persentaseHadir }}%;"
                                    aria-valuenow="{{ $persentaseHadir }}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="font-weight-bold">
                                    Izin
                                </span>
                                <span class="badge badge-warning badge-pill">{{ $totalGuruIzin }}</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-warning" role="progressbar"
                                    style="width: {{ $persentaseIzin }}%;"
                                    aria-valuenow="{{ $persentaseIzin }}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="font-weight-bold">
                                    Alpha
                                </span>
                                <span class="badge badge-danger badge-pill">{{ $totalGuruAlpha }}</span>
                            </div>
                           <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-danger" role="progressbar"
                                    style="width: {{ $persentaseAlpha }}%;"
                                    aria-valuenow="{{ $persentaseAlpha }}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('style')
    <style>
        /* Stats Cards dengan gradient */
        .stats-card {
            background: linear-gradient(135deg, #6777ef 0%, #89b6ff 100%);
            border: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            height: 100%;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .stats-card .card-body {
            text-align: center;
            padding: 25px;
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 8px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .stats-label {
            font-size: 0.95rem;
            font-weight: 600;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0;
            opacity: 0.95;
        }

        /* Card dasar dengan hover effect */
        .custom-card {
            background-color: #ffffff;
            border: 1px solid #e4e6fc;
            border-radius: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .custom-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 767px) {
            .stats-number {
                font-size: 2rem;
            }

            .stats-label {
                font-size: 0.85rem;
            }
        }
    </style>
@endpush

@push('script')
@endpush



{{-- INI Operator --}}
