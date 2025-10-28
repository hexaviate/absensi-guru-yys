@extends('layout.main')

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Profile Kehadiran</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item">Profile Kehadiran</div>
            </div>
        </div>

        <div class="section-body">
            <!-- Back Button -->
            <div class="row mb-3">
                <div class="col-12">
                    <a href="{{ route('user.index') }}" class="btn btn-icon icon-left btn-primary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar User
                    </a>
                </div>
            </div>

            <div class="row mt-sm-4">
                <!-- Left Side - Profile Card -->
                <div class="col-12 col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <!-- Profile Picture -->
                            <div class="mb-3">
                                @if($user->foto && file_exists(public_path('foto/' . $user->foto)))
                                    <img alt="Foto Profil" src="{{ asset('foto/' . $user->foto) }}"
                                        class="rounded-circle" width="120" height="120" style="object-fit: cover;">
                                @elseif($user->foto_presensi && file_exists(public_path('foto_presensi/' . $user->foto_presensi)))
                                    <img alt="Foto Profil" src="{{ asset('foto_presensi/' . $user->foto_presensi) }}"
                                        class="rounded-circle" width="120" height="120" style="object-fit: cover;">
                                @else
                                    <img alt="Foto Profil" src="{{ asset('asset/dist/assets/img/avatar/avatar-1.png') }}"
                                        class="rounded-circle" width="120">
                                @endif
                            </div>

                            <!-- User Name -->
                            <h5 class="mb-1">{{ $user->name }}</h5>

                            <!-- Position -->
                            <p class="text-muted mb-2">
                                <small>{{ $user->role ?? 'Tenaga Pendidik' }}</small>
                            </p>

                            <!-- Institution/Company -->
                            <div class="mb-3 pb-3 border-bottom">
                                <small class="text-muted d-block">
                                    @if ($user->roles->contains('name', 'admin_yayasan'))
                                        Terdaftar di semua instansi
                                    @else
                                        @forelse ($user->instansi as $ins)
                                            {{ $ins->nama_instansi }}{{ !$loop->last ? ', ' : '' }}
                                        @empty
                                            Belum Ada Instansi
                                        @endforelse
                                    @endif
                                </small>
                            </div>

                            <!-- Phone Number -->
                            @if($user->telp)
                            <div class="mb-3">
                                <small class="text-muted d-block">
                                    <i class="fas fa-phone"></i> {{ $user->telp }}
                                </small>
                            </div>
                            @endif

                            <!-- Distance -->
                            @if(isset($user->distance))
                            <div class="mb-3">
                                <small class="text-muted d-block">
                                    <i class="fas fa-map-marker-alt"></i> Jarak Tempuh: {{ $user->distance }} km
                                </small>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Side - Attendance Statistics -->
                <div class="col-12 col-md-8">
                    <!-- Summary Cards -->
                    <div class="row mb-3">
                        <div class="col-6 col-sm-3">
                            <div class="attendance-stat-card attendance-stat-primary">
                                <div class="stat-content">
                                    <div class="stat-label">Wajib Hadir</div>
                                    <div class="stat-value">{{ $wajibHadir }}</div>
                                    <div class="stat-unit">hari</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="attendance-stat-card attendance-stat-success">
                                <div class="stat-content">
                                    <div class="stat-label">Hadir</div>
                                    <div class="stat-value">{{ $hadir }}</div>
                                    <div class="stat-unit">hari</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="attendance-stat-card attendance-stat-warning">
                                <div class="stat-content">
                                    <div class="stat-label">Izin</div>
                                    <div class="stat-value">{{ $izin }}</div>
                                    <div class="stat-unit">hari</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="attendance-stat-card attendance-stat-danger">
                                <div class="stat-content">
                                    <div class="stat-label">Alpha</div>
                                    <div class="stat-value">{{ $tidakHadir }}</div>
                                    <div class="stat-unit">hari</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Percentage Card -->
                    @php
                        $totalTidakHadir = $izin + $tidakHadir;
                        $persentaseKehadiran = $wajibHadir > 0 ? ($hadir / $wajibHadir) * 100 : 0;
                        $persentaseKehadiran = number_format($persentaseKehadiran, 2);

                        // Determine progress bar color based on percentage
                        if ($persentaseKehadiran >= 90) {
                            $progressColor = 'linear-gradient(135deg, #28a745 0%, #1e7e34 100%)';
                            $textColor = '#28a745';
                        } elseif ($persentaseKehadiran >= 75) {
                            $progressColor = 'linear-gradient(135deg, #ffc107 0%, #e0a800 100%)';
                            $textColor = '#ffc107';
                        } else {
                            $progressColor = 'linear-gradient(135deg, #dc3545 0%, #bd2130 100%)';
                            $textColor = '#dc3545';
                        }
                    @endphp

                    <div class="card">
                        <div class="card-header">
                            <h4>Tingkat Kehadiran</h4>
                        </div>
                        <div class="card-body">
                            <div class="attendance-meter">
                                <div class="meter-header">
                                    <span>Persentase Kehadiran</span>
                                    <strong class="meter-percentage" style="color: {{ $textColor }}">{{ $persentaseKehadiran }}%</strong>
                                </div>
                                <div class="progress meter-bar" style="height: 30px; border-radius: 8px; overflow: hidden;">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: {{ $persentaseKehadiran }}%; font-size: 14px; display: flex; align-items: center; justify-content: center; font-weight: 600; background: {{ $progressColor }}"
                                        aria-valuenow="{{ $persentaseKehadiran }}" aria-valuemin="0" aria-valuemax="100">
                                        {{ $persentaseKehadiran }}%
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-6">
                                    <div class="meter-stat">
                                        <div class="meter-stat-value">{{ $hadir }}</div>
                                        <div class="meter-stat-label">Total Hadir</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="meter-stat meter-stat-absent">
                                        <div class="meter-stat-value">{{ $totalTidakHadir }}</div>
                                        <div class="meter-stat-label">Tidak Hadir</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Attendance Stat Cards */
        .attendance-stat-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 25px;
            border-radius: 10px;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            height: 100%;
            color: white;
            text-align: center;
        }

        .attendance-stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .attendance-stat-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }

        .attendance-stat-success {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
        }

        .attendance-stat-warning {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        }

        .attendance-stat-danger {
            background: linear-gradient(135deg, #dc3545 0%, #bd2130 100%);
        }

        .stat-icon {
            font-size: 32px;
            margin-bottom: 12px;
            display: inline-block;
        }

        .stat-content {
            width: 100%;
        }

        .stat-label {
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            opacity: 0.95;
            display: block;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 4px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .stat-unit {
            font-size: 0.9rem;
            font-weight: 500;
            opacity: 0.9;
        }

        /* Attendance Meter */
        .attendance-meter {
            margin-bottom: 20px;
        }

        .meter-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            font-size: 14px;
        }

        .meter-percentage {
            font-size: 18px;
            font-weight: 600;
        }

        .meter-bar {
            background: #e9ecef;
        }

        .meter-stat {
            text-align: center;
            padding: 25px;
            border-radius: 10px;
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .meter-stat:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
        }

        .meter-stat-absent {
            background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
        }

        .meter-stat-absent .meter-stat-value {
            color: #dc3545;
        }

        .meter-stat-absent .meter-stat-label {
            color: #c62828;
        }

        .meter-stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: #28a745;
            margin-bottom: 6px;
        }

        .meter-stat-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #2e7d32;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Detail Items */
        .detail-item {
            padding: 16px;
            margin-bottom: 12px;
            border-radius: 8px;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }

        .detail-item:hover {
            background: #e9ecef;
            transform: translateX(4px);
        }

        .detail-item:last-child {
            margin-bottom: 0;
        }

        .detail-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
            font-size: 14px;
        }

        .detail-percent {
            font-size: 12px;
            color: #666;
            font-weight: 600;
        }

        @media (max-width: 767px) {
            .stat-value {
                font-size: 2rem;
            }

            .stat-label {
                font-size: 0.85rem;
            }

            .stat-unit {
                font-size: 0.85rem;
            }

            .meter-stat-value {
                font-size: 2rem;
            }

            .meter-stat-label {
                font-size: 0.8rem;
            }
        }
    </style>
@endsection
