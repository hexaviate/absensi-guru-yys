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
                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <div class="stats-card">
                            <div class="card-body">
                                <h2 class="stats-number">125</h2>
                                <p class="stats-label">GURU PAUD</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <div class="stats-card">
                            <div class="card-body">
                                <h2 class="stats-number">89</h2>
                                <p class="stats-label">GURU MI</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <div class="stats-card">
                            <div class="card-body">
                                <h2 class="stats-number">156</h2>
                                <p class="stats-label">GURU MTs</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <div class="stats-card">
                            <div class="card-body">
                                <h2 class="stats-number">78</h2>
                                <p class="stats-label">GURU MA</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <div class="stats-card">
                            <div class="card-body">
                                <h2 class="stats-number">234</h2>
                                <p class="stats-label">GURU SMK</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <div class="stats-card">
                            <div class="card-body">
                                <h2 class="stats-number">67</h2>
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
                                <h3 class="mb-0 text-primary">12</h3>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Total Guru Yayasan</h6>
                                <h3 class="mb-0 text-success">749</h3>
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
                                <li class="media">
                                    <img class="mr-3 rounded-circle" width="50"
                                        src="{{ asset('foto_presensi/' . ($guru->foto_presensi ?? 'default.png')) }}"
                                        alt="avatar">
                                    <div class="media-body">
                                        <div class="float-right text-primary">{{ $guru->created_at->diffForHumans() }}</div>
                                        <div class="media-title">{{ $guru->user->name }}</div>
                                        <small class="text-muted">Hadir pada {{ $guru->created_at->format('H:i') }}</small>
                                    </div>
                                </li>
                            @empty
                                <li class="media">
                                    <h4>Belum Ada Yang Absensi Hari Ini</h4>
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
        // Inisialisasi data global
        window.hadirData = window.hadirData || [];
        window.izinData = window.izinData || [];
        window.alphaData = window.alphaData || [];
        window.lastDate = window.lastDate || Date.now() - (60 * 86400000); // Mulai dari 60 hari lalu
        window.XAXISRANGE = window.XAXISRANGE || 5184000000; // 60 hari dalam milliseconds

        // Fungsi untuk generate data baru (simulasi)
        function getNewSeries(baseval) {
            var newDate = baseval + 86400000; // Tambah 1 hari
            window.lastDate = newDate;

            // Simulasi data dengan range lebih lebar (ganti dengan data real dari database)
            var totalGuru = 749;
            var alpha = Math.floor(Math.random() * 150) + 50; // Alpha 50-200
            var izin = Math.floor(Math.random() * 200) + 100; // Izin 100-300
            var hadir = totalGuru - alpha - izin; // Sisanya hadir

            // Pastikan tidak minus
            if (hadir < 0) {
                hadir = Math.floor(Math.random() * 200) + 400; // Hadir 400-600
                izin = Math.floor(Math.random() * 100) + 50; // Izin 50-150
                alpha = totalGuru - hadir - izin; // Sisanya alpha
            }

            window.hadirData.push({
                x: newDate,
                y: hadir
            });
            window.izinData.push({
                x: newDate,
                y: izin
            });
            window.alphaData.push({
                x: newDate,
                y: alpha
            });

            // Batasi data hanya 60 hari
            if (window.hadirData.length > 60) {
                window.hadirData.shift();
                window.izinData.shift();
                window.alphaData.shift();
            }
        }

        // Isi data awal untuk 60 hari
        for (var i = 0; i < 60; i++) {
            getNewSeries(window.lastDate);
        }

        // Konfigurasi chart
        var options = {
            series: [{
                    name: 'Hadir',
                    data: window.hadirData.slice()
                },
                {
                    name: 'Izin',
                    data: window.izinData.slice()
                },
                {
                    name: 'Alpha',
                    data: window.alphaData.slice()
                }
            ],
            chart: {
                id: 'realtime',
                height: 350,
                type: 'line',
                animations: {
                    enabled: true,
                    dynamicAnimation: {
                        speed: 1000
                    }
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
                max: 700,
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
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();

        // Fungsi untuk update tanggal di card
        function updateDateDisplay() {
            var today = new Date();
            var options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            var dateString = today.toLocaleDateString('id-ID', options);

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

        setInterval(function() {
            getNewSeries(window.lastDate);
            chart.updateSeries([{
                    name: 'Hadir',
                    data: window.hadirData
                },
                {
                    name: 'Izin',
                    data: window.izinData
                },
                {
                    name: 'Alpha',
                    data: window.alphaData
                }
            ]);
            updateDateDisplay();
        }, 86400000);
    </script>
@endpush
