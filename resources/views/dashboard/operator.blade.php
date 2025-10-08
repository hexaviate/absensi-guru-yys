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
                        <h2 class="stats-number">45</h2>
                        <p class="stats-label">Total Guru</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12 mb-3">
                <div class="stats-card">
                    <div class="card-body">
                        <h2 class="stats-number">39</h2>
                        <p class="stats-label">Kehadiran Hari Ini</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12 mb-3">
                <div class="stats-card">
                    <div class="card-body">
                        <h2 class="stats-number">86.6%</h2>
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
                                    <tr>
                                        <td>
                                            <img alt="image" src="https://ui-avatars.com/api/?name=Ahmad+Fauzi&background=6777ef&color=fff"
                                                class="rounded-circle" width="45" height="45">
                                        </td>
                                        <td>Ahmad Fauzi, S.Pd</td>
                                        <td>07:15 WIB</td>
                                        <td>
                                            <span class="badge badge-success">Hadir</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <img alt="image" src="https://ui-avatars.com/api/?name=Siti+Nurhaliza&background=28a745&color=fff"
                                                class="rounded-circle" width="45" height="45">
                                        </td>
                                        <td>Siti Nurhaliza, M.Pd</td>
                                        <td>07:22 WIB</td>
                                        <td>
                                            <span class="badge badge-success">Hadir</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <img alt="image" src="https://ui-avatars.com/api/?name=Budi+Santoso&background=ffc107&color=fff"
                                                class="rounded-circle" width="45" height="45">
                                        </td>
                                        <td>Budi Santoso, S.Pd</td>
                                        <td>08:10 WIB</td>
                                        <td>
                                            <span class="badge badge-warning">Izin</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <img alt="image" src="https://ui-avatars.com/api/?name=Dewi+Lestari&background=17a2b8&color=fff"
                                                class="rounded-circle" width="45" height="45">
                                        </td>
                                        <td>Dewi Lestari, S.Si</td>
                                        <td>07:35 WIB</td>
                                        <td>
                                            <span class="badge badge-success">Hadir</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <img alt="image" src="https://ui-avatars.com/api/?name=Eko+Prasetyo&background=fd7e14&color=fff"
                                                class="rounded-circle" width="45" height="45">
                                        </td>
                                        <td>Eko Prasetyo, M.Pd</td>
                                        <td>-</td>
                                        <td>
                                            <span class="badge badge-danger">Alpha</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Umum Instansi -->
            <div class="col-lg-4 col-md-12 col-12 col-sm-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <h4>Data Umum Instansi</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h5 class="mb-0 font-weight-bold text-primary">SMA Negeri 1 Jepara</h5>
                        </div>
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Jumlah Guru Aktif</h6>
                                <h3 class="mb-0 text-primary">45</h3>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Hari Efektif Bulan Ini</h6>
                                <h3 class="mb-0 text-success">22</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Kehadiran Hari Ini -->
                <div class="card custom-card">
                    <div class="card-header">
                        <h4>Ringkasan Kehadiran</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="font-weight-bold">
                                    {{-- <i class="fas fa-check-circle text-success"></i> --}}
                                     Hadir
                                </span>
                                <span class="badge badge-success badge-pill">39</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 86.6%"
                                    aria-valuenow="86.6" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="font-weight-bold">
                                    {{-- <i class="fas fa-file-alt text-warning"></i> --}}
                                    Izin
                                </span>
                                <span class="badge badge-warning badge-pill">4</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 8.8%"
                                    aria-valuenow="8.8" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="font-weight-bold">
                                    {{-- <i class="fas fa-times-circle text-danger"></i> --}}
                                     Alpha
                                </span>
                                <span class="badge badge-danger badge-pill">2</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 4.4%"
                                    aria-valuenow="4.4" aria-valuemin="0" aria-valuemax="100"></div>
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
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        // Generate 14 hari terakhir
        function generateLast14Days() {
            const labels = [];
            const today = new Date();

            for (let i = 13; i >= 0; i--) {
                const date = new Date(today);
                date.setDate(date.getDate() - i);

                const dayName = date.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'short'
                });
                labels.push(dayName);
            }

            return labels;
        }

        // Generate data dummy untuk 14 hari
        function generateDummyData() {
            const hadir = [];
            const izin = [];
            const alpha = [];
            const totalGuru = 45;

            for (let i = 0; i < 14; i++) {
                const hadirCount = Math.floor(Math.random() * 7) + 37; // 37-43
                const izinCount = Math.floor(Math.random() * 4) + 2; // 2-5
                const alphaCount = totalGuru - hadirCount - izinCount;

                hadir.push(hadirCount);
                izin.push(izinCount);
                alpha.push(alphaCount > 0 ? alphaCount : 0);
            }

            return { hadir, izin, alpha };
        }

        const categories = generateLast14Days();
        const data = generateDummyData();

        // Konfigurasi ApexCharts
        const options = {
            series: [
                {
                    name: 'Hadir',
                    data: data.hadir
                },
                {
                    name: 'Izin',
                    data: data.izin
                },
                {
                    name: 'Alpha',
                    data: data.alpha
                }
            ],
            chart: {
                type: 'area',
                height: 350,
                stacked: false,
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                    animateGradually: {
                        enabled: true,
                        delay: 150
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 350
                    }
                },
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        zoom: true,
                        zoomin: true,
                        zoomout: true,
                        pan: true,
                        reset: true
                    }
                },
                zoom: {
                    enabled: true
                }
            },
            colors: ['#28a745', '#ffc107', '#dc3545'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.6,
                    opacityTo: 0.2,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: categories,
                labels: {
                    style: {
                        fontSize: '12px',
                        colors: '#6c757d'
                    }
                }
            },
            yaxis: {
                min: 0,
                max: 50,
                tickAmount: 5,
                labels: {
                    formatter: function(value) {
                        return Math.round(value);
                    },
                    style: {
                        fontSize: '12px',
                        colors: '#6c757d'
                    }
                },
                title: {
                    text: 'Jumlah Guru',
                    style: {
                        fontSize: '13px',
                        fontWeight: 600,
                        color: '#6c757d'
                    }
                }
            },
            markers: {
                size: 4,
                strokeColors: '#fff',
                strokeWidth: 2,
                hover: {
                    size: 6
                }
            },
            legend: {
                show: true,
                position: 'top',
                horizontalAlign: 'end',
                fontSize: '13px',
                fontWeight: 500,
                markers: {
                    width: 12,
                    height: 12,
                    radius: 3
                }
            },
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function(value) {
                        return value + ' guru';
                    }
                }
            },
            grid: {
                borderColor: '#e7e7e7',
                strokeDashArray: 4,
                xaxis: {
                    lines: {
                        show: true
                    }
                }
            }
        };

        // Render chart
        const chart = new ApexCharts(document.querySelector("#chartKehadiran"), options);
        chart.render();
    </script>
@endpush



{{-- INI Operator --}}
