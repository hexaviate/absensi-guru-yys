@extends('layout.main')
@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Dashboard Guru</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">Dashboard</div>
            </div>
        </div>


        @php
            $user = auth()->user();
        @endphp

        <!-- Header Greeting -->
        {{-- <div class="header-greeting mb-4">
            <h2 class="greeting-title">Halo, {{ $user->name }}</h2>
            <p class="greeting-subtitle">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>
        </div> --}}

        <!-- Status Kehadiran Hari Ini -->
        <div class="row">
            {{-- cek kalau user tenaga pendidik dan tidak punya jadwal hari ini --}}
            @if (auth()->user()->hasAnyRole('tenaga_pendidik') && $jadwalHariIni->isEmpty())
                <div class="col-12 mb-3">
                    <div class="alert alert-warning" role="alert">
                        <i class="fa-solid fa-exclamation-triangle"></i> Anda tidak memiliki jadwal pada hari ini.
                    </div>
                </div>
            @else
                {{-- tampilkan card presensi --}}
                @forelse($presensiHariIni as $presensi)
                    <div class="col-lg-6 col-12 mb-4">
                        <div class="card shadow-sm border-0 rounded-lg hover-shadow"
                            style="transition: all 0.3s; border-left: 6px solid #4e73df;">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <h6 class="text-primary mb-0">
                                        <i
                                            class="fa-solid fa-building mr-2"></i>{{ $presensi->instansi->nama_instansi ?? 'N/A' }}
                                    </h6>
                                    <span class="badge badge-light text-primary">{{ now()->format('d M Y') }}</span>
                                </div>
                                <hr>
                                <div class="small text-muted mb-1">Jadwal</div>
                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <strong>{{ $presensi->jadwal->datang ?? '07:00' }}</strong><br><small>Datang</small>
                                    </div>
                                    <div>
                                        <strong>{{ $presensi->jadwal->pulang ?? '14:00' }}</strong><br><small>Pulang</small>
                                    </div>
                                </div>
                                <div class="small text-muted mb-1">Absensi</div>
                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <strong>{{ $presensi->datang ? \Carbon\Carbon::parse($presensi->datang)->format('H:i') : '—' }}</strong><br>
                                        <small>Datang</small>
                                    </div>
                                    <div>
                                        <strong>{{ $presensi->pulang ? \Carbon\Carbon::parse($presensi->pulang)->format('H:i') : '—' }}</strong><br>
                                        <small>Pulang</small>
                                    </div>
                                </div>
                                @if ($presensi->datang && $presensi->pulang)
                                    <span class="badge badge-success px-3 py-1 rounded-pill">Hadir Tepat Waktu</span>
                                @elseif($presensi->datang && !$presensi->pulang)
                                    <span class="badge badge-warning px-3 py-1 rounded-pill">Belum Pulang</span>
                                @else
                                    <span class="badge badge-warning px-3 py-1 rounded-pill">Belum Absen</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 mb-3">
                        <div class="alert alert-warning" role="alert">
                            <i class="fa-solid fa-exclamation-triangle"></i> Belum ada data presensi untuk hari ini.
                        </div>
                    </div>
                @endforelse
            @endif
        </div>

        <!-- Rekap Bulan Ini     -->
        <div class="row">
            <div class="col-lg-8 col-md-12 col-12 col-sm-12 mb-4">
                <div class="card card-custom shadow-sm">
                    <div class="card-header">
                        <h6 class="font-weight-bold text-dark mb-0">Riwayat Absensi Minggu Ini</h6>
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
                                            {{ $presensiPerHari->pluck('instansi.nama_instansi')->filter()->implode(' - ') }}
                                        </div>
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
                                            <div class="history-status status-present"><i
                                                    class="fa-solid fa-circle-check"></i> Hadir</div>
                                        @elseif($adaIzin)
                                            <div class="history-status status-leave"><i class="fa-solid fa-file-lines"></i>
                                                Izin</div>
                                        @elseif($adaBelumPulang)
                                            <div class="history-status status-leave"><i class="fa-solid fa-clock"></i> Belum
                                                Pulang</div>
                                        @elseif($semuaAlpha)
                                            <div class="history-status status-absent"><i
                                                    class="fa-solid fa-circle-xmark"></i> Alpha</div>
                                        @else
                                            <div class="history-status status-leave"><i class="fa-solid fa-clock"></i>
                                                Sebagian Hadir</div>
                                        @endif
                                    </div>
                                </li>
                            @empty
                                <li class="text-center text-muted py-4"><i class="fa-solid fa-inbox"></i><br>Belum ada
                                    riwayat presensi minggu ini</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-12 col-12 col-sm-12">
                <!-- Ringkasan Kehadiran Hari Ini -->
                <div class="card card-custom">
                    <div class="card-header">
                        <h4>Ringkasan Kehadiran</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="font-weight-bold">
                                    Hadir
                                </span>
                                <span class="badge badge-success badge-pill">{{ $presensiBulanIni }}</span>
                            </div>
                          <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-success " role="progressbar"
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
                                <span class="badge badge-warning badge-pill">{{ $izinBulanIni }}</span>
                            </div>
                           <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-waring" role="progressbar"
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
                                <span class="badge badge-danger badge-pill">{{ $tidakHadirBulanIni }}</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-danger" role="progressbar"
                                    style="width: {{ $persentaseTidakHadir }}%;"
                                    aria-valuenow="{{ $persentaseTidakHadir }}" aria-valuemin="0" aria-valuemax="100">
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
        /* Header Greeting Section */
        .header-greeting {
            background: linear-gradient(135deg, #6777ef 0%, #5a67d8 100%);
            padding: 24px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(103, 119, 239, 0.2);
        }

        .greeting-title {
            font-size: 28px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 4px;
        }

        .greeting-subtitle {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 0;
        }

        /* Card Attendance */
        .card-attendance {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e4e6fc;
            transition: all 0.3s ease;
            height: 100%;
        }

        .card-attendance:hover {
            box-shadow: 0 4px 16px rgba(103, 119, 239, 0.15) !important;
            transform: translateY(-2px);
        }

        .card-attendance .card-body {
            padding: 20px;
        }

        .card-title-custom {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .school-icon-custom {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #6777ef 0%, #5a67d8 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 16px;
            flex-shrink: 0;
        }

        .school-name {
            font-size: 16px;
            font-weight: 700;
            color: #191d21;
            margin-bottom: 0;
        }

        .info-row-custom {
            display: flex;
            align-items: flex-start;
            margin-bottom: 12px;
            font-size: 14px;
            line-height: 1.6;
        }

        .info-row-custom:last-child {
            margin-bottom: 0;
        }

        .emoji-icon {
            min-width: 24px;
            margin-right: 8px;
            margin-top: 2px;
            font-size: 16px;
        }

        .info-content {
            flex: 1;
        }

        .info-label-custom {
            font-weight: 600;
            color: #34395e;
        }

        .info-text-custom {
            color: #6c757d;
        }

        .time-highlight {
            font-weight: 700;
            color: #6777ef;
        }

        .time-late {
            font-weight: 700;
            color: #fc544b;
        }

        .time-empty {
            color: #95aac9;
            font-weight: 600;
        }

        .divider-custom {
            height: 1px;
            background: #e4e6fc;
            margin: 14px 0;
        }

        .status-badge-custom {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-top: 4px;
        }

        .status-success-custom {
            background: #d4edda;
            color: #155724;
        }

        .status-warning-custom {
            background: #fff3cd;
            color: #856404;
        }

        .status-danger-custom {
            background: #f8d7da;
            color: #721c24;
        }

        /* Card Rekap */
        .card-rekap {
            background: linear-gradient(135deg, #fff 0%, #f8f9fc 100%);
            border: 1px solid #e4e6fc;
            border-radius: 12px;
        }

        .rekap-grid-custom {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 14px;
            margin-top: 16px;
        }

        .rekap-item-custom {
            background: #fff;
            padding: 14px;
            border-radius: 10px;
            border: 1px solid #e4e6fc;
            text-align: center;
        }

        .rekap-label-custom {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 6px;
            display: block;
        }

        .rekap-value-custom {
            font-size: 16px;
            font-weight: 700;
            color: #34395e;
        }

        /* History List - Keep existing styles */
        .card-custom {
            border: 1px solid #e4e6fc;
            border-radius: 12px;
            transition: all 0.3s ease;
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

        /* Responsive */
        @media (max-width: 768px) {
            .greeting-title {
                font-size: 22px;
            }

            .header-greeting {
                padding: 20px;
            }

            .card-attendance .card-body {
                padding: 16px;
            }

            .rekap-grid-custom {
                grid-template-columns: 1fr;
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
