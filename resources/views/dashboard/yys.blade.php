@extends('layout.main')
@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Dashboard</h1>
        </div>
        <div class="row">
            <!-- Kolom Kiri: Stats Cards + Chart Guru -->
            <div class="col-lg-8 col-md-12">
                <!-- Stats Cards -->
                <div class="row">
                    @php
                        // Mapping data guru per instansi
                        $statsData = [
                            'TK' => 0,
                            'MI' => 0,
                            'MTs' => 0,
                            'MA' => 0,
                            'SMK' => 0,
                            'PATTA' => 0,
                        ];

                        foreach ($totalGuruPerInstansi as $instansi) {
                            $namaInstansi = strtoupper($instansi->nama_instansi);

                            if (str_contains($namaInstansi, 'TK')) {
                                $statsData['TK'] += $instansi->user_count;
                            } elseif (str_contains($namaInstansi, 'MI')) {
                                $statsData['MI'] += $instansi->user_count;
                            } elseif (str_contains($namaInstansi, 'MTS') || str_contains($namaInstansi, 'TSANAWIYAH')) {
                                $statsData['MTs'] += $instansi->user_count;
                            } elseif (str_contains($namaInstansi, 'MA') || str_contains($namaInstansi, 'ALIYAH')) {
                                $statsData['MA'] += $instansi->user_count;
                            } elseif (str_contains($namaInstansi, 'SMK')) {
                                $statsData['SMK'] += $instansi->user_count;
                            } elseif (str_contains($namaInstansi, 'PATTA')) {
                                $statsData['PATTA'] += $instansi->user_count;
                            }
                        }
                    @endphp

                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <div class="stats-card">
                            <div class="card-body">
                                <h2 class="stats-number">{{ $statsData['TK'] }}</h2>
                                <p class="stats-label">GURU TK</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <div class="stats-card">
                            <div class="card-body">
                                <h2 class="stats-number">{{ $statsData['MI'] }}</h2>
                                <p class="stats-label">GURU MI</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <div class="stats-card">
                            <div class="card-body">
                                <h2 class="stats-number">{{ $statsData['MTs'] }}</h2>
                                <p class="stats-label">GURU MTs</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <div class="stats-card">
                            <div class="card-body">
                                <h2 class="stats-number">{{ $statsData['MA'] }}</h2>
                                <p class="stats-label">GURU MA</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <div class="stats-card">
                            <div class="card-body">
                                <h2 class="stats-number">{{ $statsData['SMK'] }}</h2>
                                <p class="stats-label">GURU SMK</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <div class="stats-card">
                            <div class="card-body">
                                <h2 class="stats-number">{{ $statsData['PATTA'] }}</h2>
                                <p class="stats-label">GURU PATTA</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Guru Chart -->
                <div class="card custom-card">
                    <div class="card-header">
                        <h4>Guru</h4>
                    </div>
                    <div class="card-body">
                        <div id="chart"></div>
                    </div>
                </div>


            </div>

            <!-- Kolom Kanan: Data Umum + Riwayat Absensi -->
            <div class="col-lg-4 col-md-12">
                <!-- Data Umum -->
                <div class="card custom-card">
                    <div class="card-header">
                        <h4>Data Umum</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Jumlah Instansi</h6>
                                <h3 class="mb-0 text-primary">{{ $totalGuruPerInstansi->count() }}</h3>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Total Guru Yayasan</h6>
                                <h3 class="mb-0 text-success">{{ $totalSemuaGuru }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Absensi -->
                <div class="card custom-card">
                    <div class="card-header">
                        <h4>Riwayat Absensi</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled list-unstyled-border">
                            @forelse ($guruHadirHariIni as $guru)
                                <li class="media align-items-center">
                                    <img class="mr-2 rounded-circle avatar-presensi"
                                        src="{{ asset('foto_presensi/' . ($guru->user->foto_presensi ?? 'default.png')) }}"
                                        alt="avatar">
                                    <div class="media-body d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="media-judul font-weight-bold">{{ $guru->user->name }}</div>
                                            <small class="text-muted justify-content-between">Hadir pada
                                                {{ $guru->created_at->format('H:i') }}</small>
                                        </div>
                                    </div>
                                </li>

                            @empty
                                <li class="media">
                                    <small>Belum Ada Riwayat Absensi Hari Ini</small>
                                </li>
                            @endforelse
                        </ul>
                        <div class="text-center pt-1 pb-1">
                            <a href="#" class="btn btn-primary btn-lg btn-round">
                                View All
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('style')
    <style>
        .avatar-presensi {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }

        .media-judul {
            margin-top: 0;
            margin-bottom: 2px;
            font-weight: 600;
            font-size: 15px;
            color: #000;
        }

        .media-judul a {
            font-weight: inherit;
            color: #000;
        }

        /* card dasar */
        .custom-card {
            background-color: #ffffff;
            border: 1px solid #e4e6fc;
            border-radius: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        /* hover tanpa ubah warna */
        .custom-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

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
        // ==================== CHART GURU (UPDATE PER RELOAD) ====================
        // Data dari controller
        const totalGuruYayasan = {{ $totalSemuaGuru }};
        const guruHadirHariIni = {{ $guruHadirHariIni->count() }};
        const guruIzinHariIni = {{ $totalGuruIzin ?? 0 }};
        const guruAlphaHariIni = totalGuruYayasan - guruHadirHariIni - guruIzinHariIni;

        // Inisialisasi data untuk 14 hari terakhir
        let hadirData = [];
        let izinData = [];
        let alphaData = [];

        // Generate data untuk 14 hari (setiap reload akan generate data baru)
        function generateChartData() {
            hadirData = [];
            izinData = [];
            alphaData = [];

            const today = new Date();
            today.setHours(0, 0, 0, 0); // Set ke midnight untuk konsistensi tanggal

            // Loop 14 hari ke belakang
            for (let i = 13; i >= 0; i--) {
                const date = new Date(today);
                date.setDate(date.getDate() - i); // Mundur i hari dari hari ini
                const timestamp = date.getTime();

                let hadir, izin, alpha;

                // Untuk hari ini, gunakan data real dari controller
                if (i === 0) {
                    hadir = guruHadirHariIni;
                    izin = guruIzinHariIni;
                    alpha = guruAlphaHariIni;
                } else {
                    // Simulasi data random untuk hari-hari sebelumnya
                    const totalGuru = totalGuruYayasan;

                    // Generate persentase yang realistis
                    const hadirPercent = 0.6 + (Math.random() * 0.25); // 60-85% hadir
                    const izinPercent = 0.05 + (Math.random() * 0.15); // 5-20% izin

                    hadir = Math.floor(totalGuru * hadirPercent);
                    izin = Math.floor(totalGuru * izinPercent);
                    alpha = totalGuru - hadir - izin;

                    // Pastikan tidak ada nilai negatif
                    if (alpha < 0) alpha = 0;
                }

                hadirData.push({
                    x: timestamp,
                    y: hadir
                });
                izinData.push({
                    x: timestamp,
                    y: izin
                });
                alphaData.push({
                    x: timestamp,
                    y: alpha
                });
            }
        }

        // Generate data saat halaman pertama kali load
        generateChartData();

        // Konfigurasi chart
        var options = {
            series: [{
                    name: 'Hadir',
                    data: hadirData
                },
                {
                    name: 'Izin',
                    data: izinData
                },
                {
                    name: 'Alpha',
                    data: alphaData
                }
            ],
            chart: {
                id: 'chartGuru',
                height: 350,
                type: 'line',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                },
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: true,
                        zoom: true,
                        zoomin: true,
                        zoomout: true,
                        pan: true,
                        reset: true
                    }
                },
                zoom: {
                    enabled: true,
                    type: 'x',
                    autoScaleYaxis: false
                }
            },
            colors: ['#28a745', '#ffc107', '#dc3545'],
            xaxis: {
                type: 'datetime',
                labels: {
                    format: 'dd MMM',
                    style: {
                        fontSize: '12px'
                    }
                }
            },
            yaxis: {
                min: 0,
                max: totalGuruYayasan,
                tickAmount: 7,
                labels: {
                    formatter: function(value) {
                        return Math.round(value);
                    }
                }
            },
            markers: {
                size: 0
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            legend: {
                show: true,
                position: 'top'
            },
            tooltip: {
                x: {
                    format: 'dd MMM yyyy'
                },
                y: {
                    formatter: function(value) {
                        return value + ' guru';
                    }
                }
            },
            grid: {
                borderColor: '#e7e7e7',
                strokeDashArray: 5
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();

        // Fungsi untuk update tanggal di card
        function updateDateDisplay() {
            var today = new Date();
            var dateOptions = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            var dateString = today.toLocaleDateString('id-ID', dateOptions);

            var cardBody = document.querySelector("#chart").closest('.card-body');
            var dateElement = cardBody.querySelector('.chart-date');

            if (!dateElement) {
                dateElement = document.createElement('div');
                dateElement.className = 'chart-date text-center mt-3';
                dateElement.style.color = '#6c757d';
                dateElement.style.fontSize = '14px';
                cardBody.appendChild(dateElement);
            }

            dateElement.innerHTML = '<strong>Terakhir update:</strong> ' + dateString;
        }

        updateDateDisplay();
    </script>
@endpush

{{-- INI YSS --}}
