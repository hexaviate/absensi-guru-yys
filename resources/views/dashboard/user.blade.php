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
        @if(false) {{-- Aktifkan sesuai kondisi --}}
        <div class="alert alert-warning alert-has-icon alert-dismissible show fade">
            <div class="alert-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
            <div class="alert-body">
                <div class="alert-title">Perhatian!</div>
                Kamu belum melakukan absen pulang di SD Harapan hari ini
            </div>
            <button class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
        @endif

        <!-- Status Kehadiran Hari Ini -->
        <div class="row">
            <div class="col-12">
                <h6 class="mb-3 font-weight-bold text-dark">Status Kehadiran Hari Ini</h6>
            </div>

            @forelse($presensiHariIni as $presensi)
                <div class="col-lg-6 col-md-6 col-12 mb-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h6 class="font-weight-bold text-dark mb-1">
                                        <i class="fa-solid fa-building text-primary mr-1"></i>
                                        {{ $presensi->instansi->nama_instansi ?? 'N/A' }}
                                    </h6>
                                    <small class="text-muted">
                                        <i class="fa-solid fa-calendar-days mr-1"></i>
                                        {{ \Carbon\Carbon::parse($presensi->tanggal)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                    </small>
                                </div>

                                @if ($presensi->jam_pulang)
                                    <span class="badge badge-success">Hadir</span>
                                @else
                                    <span class="badge badge-warning">Belum Pulang</span>
                                @endif
                            </div>

                            <div class="bg-light p-3 rounded">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="text-center">
                                            <i class="fa-solid fa-arrow-right-to-bracket text-primary mb-2" style="font-size: 1.5rem;"></i>
                                            <div>
                                                <small class="text-muted d-block">Jam Datang</small>
                                                <strong class="text-dark">
                                                    {{ $presensi->datang ? \Carbon\Carbon::parse($presensi->datang)->format('H:i') : '--:--' }}
                                                </strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 border-left">
                                        <div class="text-center">
                                            <i class="fa-solid fa-arrow-right-from-bracket text-secondary mb-2" style="font-size: 1.5rem;"></i>
                                            <div>
                                                <small class="text-muted d-block">Jam Pulang</small>
                                                <strong class="{{ $presensi->pulang ? 'text-dark' : 'text-muted' }}">
                                                    {{ $presensi->pulang ? \Carbon\Carbon::parse($presensi->pulang)->format('H:i') : '--:--' }}
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
                    <div class="alert alert-light border">
                        <div class="text-center py-3">
                            <i class="fa-solid fa-circle-info text-muted mb-2" style="font-size: 2rem;"></i>
                            <p class="mb-0 text-muted">Belum ada presensi hari ini</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Jadwal Hari Ini -->
        <div class="row">
            <div class="col-12">
                <h6 class="mb-3 font-weight-bold text-dark">Jadwal Hari Ini</h6>
            </div>

            @forelse($jadwalHariIni as $jadwal)
                <div class="col-12 mb-3">
                    <div class="card shadow-sm border-left-primary border-0">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <div class="col-md-5 mb-2 mb-md-0">
                                    <h6 class="font-weight-bold text-dark mb-0">
                                        {{ $jadwal->instansi->nama_instansi ?? 'N/A' }}
                                    </h6>
                                </div>
                                <div class="col-md-7">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="bg-light p-2 rounded">
                                                <small class="text-muted d-block">Masuk</small>
                                                <strong class="text-dark">
                                                    {{ $jadwal->datang ? \Carbon\Carbon::parse($jadwal->datang)->format('H:i') : '--:--' }}
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="bg-light p-2 rounded">
                                                <small class="text-muted d-block">Pulang</small>
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
                    <div class="alert alert-light border">
                        <div class="text-center py-3">
                            <i class="fa-solid fa-calendar-xmark text-muted mb-2" style="font-size: 2rem;"></i>
                            <p class="mb-0 text-muted">Tidak ada jadwal untuk hari ini</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Riwayat Absensi & Rekap Bulanan -->
        <div class="row">
            <!-- Riwayat Absensi Terakhir -->
            <div class="col-lg-7 col-12 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="font-weight-bold text-dark mb-0">Riwayat Absensi</h6>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-unstyled list-unstyled-border mb-0">
                            @forelse($presensiMingguIni->groupBy('tanggal') as $tanggal => $presensiPerHari)
                                <li class="media p-3">
                                    <div class="text-center mr-3" style="min-width: 60px;">
                                        <strong class="text-primary d-block">{{ \Carbon\Carbon::parse($tanggal)->format('d') }}</strong>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($tanggal)->format('M') }}</small>
                                        <small class="text-muted d-block">{{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('ddd') }}</small>
                                    </div>
                                    <div class="media-body">
                                        <div class="d-flex justify-content-between align-items-start flex-wrap">
                                            <div class="mb-1">
                                                <h6 class="font-weight-bold text-dark mb-1">
                                                    {{ $presensiPerHari->pluck('instansi.nama_instansi')->filter()->implode(' • ') }}
                                                </h6>
                                            </div>

                                            @php
                                                $semuaHadir = $presensiPerHari->every(fn($p) => $p->datang && $p->pulang);
                                                $adaIzin = $presensiPerHari->contains(fn($p) => $p->keterangan == 'izin' || $p->status == 'izin');
                                                $adaBelumPulang = $presensiPerHari->contains(fn($p) => $p->datang && !$p->pulang);
                                                $semuaAlpha = $presensiPerHari->every(fn($p) => !$p->datang);
                                            @endphp

                                            @if ($semuaHadir)
                                                <span class="badge badge-success">Hadir</span>
                                            @elseif($adaIzin)
                                                <span class="badge badge-info">Izin</span>
                                            @elseif($adaBelumPulang)
                                                <span class="badge badge-warning">Belum Pulang</span>
                                            @elseif($semuaAlpha)
                                                <span class="badge badge-danger">Alpha</span>
                                            @else
                                                <span class="badge badge-secondary">Sebagian Hadir</span>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="text-center py-5">
                                    <i class="fa-solid fa-inbox text-muted mb-2" style="font-size: 2rem;"></i>
                                    <p class="text-muted mb-0">Belum ada riwayat presensi minggu ini</p>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Rekap Bulanan -->
            <div class="col-lg-5 col-12 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="font-weight-bold text-dark mb-0">Rekap Bulan Ini</h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center mb-4">
                            <div class="col-4">
                                <i class="fa-solid fa-circle-check text-success mb-2" style="font-size: 2rem;"></i>
                                <h4 class="mb-0 font-weight-bold text-dark">18</h4>
                                <small class="text-muted">Hadir</small>
                            </div>
                            <div class="col-4 border-left border-right">
                                <i class="fa-solid fa-file-lines text-warning mb-2" style="font-size: 2rem;"></i>
                                <h4 class="mb-0 font-weight-bold text-dark">3</h4>
                                <small class="text-muted">Izin</small>
                            </div>
                            <div class="col-4">
                                <i class="fa-solid fa-circle-xmark text-danger mb-2" style="font-size: 2rem;"></i>
                                <h4 class="mb-0 font-weight-bold text-dark">1</h4>
                                <small class="text-muted">Alpha</small>
                            </div>
                        </div>

                        <div class="mb-2">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted">Tingkat Kehadiran</small>
                                <small class="font-weight-bold text-dark">81.8%</small>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 81.8%"
                                    aria-valuenow="81.8" aria-valuemin="0" aria-valuemax="100"></div>
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
        /* Minimal Custom CSS - Hanya untuk enhance */
        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(103, 119, 239, 0.15) !important;
        }

        .border-left-primary {
            border-left: 4px solid #6777ef !important;
        }

        /* Smooth transitions */
        .badge, .btn {
            transition: all 0.2s ease;
        }
    </style>
@endpush

@push('script')
@endpush
