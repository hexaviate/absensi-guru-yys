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
                            'PAUD' => 0,
                            'MI' => 0,
                            'MTs' => 0,
                            'MA' => 0,
                            'SMK' => 0,
                            'PATTA' => 0,
                        ];

                        foreach ($totalGuruPerInstansi as $instansi) {
                            $namaInstansi = strtoupper($instansi->nama_instansi);

                            if (str_contains($namaInstansi, 'PAUD')) {
                                $statsData['PAUD'] += $instansi->user_count;
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
                                <h2 class="stats-number">50</h2>
                                <p class="stats-label">Guru & Karyawan PAUD</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <div class="stats-card">
                            <div class="card-body">
                                <h2 class="stats-number">84</h2>
                                <p class="stats-label">Guru & Karyawan <br>MI</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <div class="stats-card">
                            <div class="card-body">
                                {{-- <h2 class="stats-number">{{ $statsData['MTs'] }}</h2> --}}
                                <h2 class="stats-number">124</h2>
                                <p class="stats-label">Guru & Karyawan <br>MTs</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-12 mb-3">
                        <div class="stats-card">
                            <div class="card-body">
                                <h2 class="stats-number">134</h2>
                                <p class="stats-label">Guru & Karyawan <br>MA</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-12 mb-3">
                        <div class="stats-card">
                            <div class="card-body">
                                <h2 class="stats-number">{{ $statsData['SMK'] }}</h2>
                                <p class="stats-label">Guru & Karyawan <br>SMK</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-12 mb-3">
                        <div class="stats-card">
                            <div class="card-body">
                                <h2 class="stats-number">24</h2>
                                <p class="stats-label">Guru & Karyawan PATTA</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 mb-3">
                        <div class="stats-card">
                            <div class="card-body">
                                <h2 class="stats-number">4</h2>
                                <p class="stats-label">KARYAWAN YAYASAN (PUSPELA)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Guru Chart -->
                <div class="card custom-card">
                    <div class="card-header">
                        <h4>Statistik Absensi</h4>
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
                                <h6 class="mb-0">Total Guru & Karyawan </h6>
                                {{-- <h3 class="mb-0 text-primary">{{ $totalSemuaGuru }}</h3> --}}
                                <h3 class="mb-0 text-primary">464</h3>
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
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-header">
                        <h2>Kalender Yayasan</h2>
                    </div>
                </div>
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('modal')
    <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="eventModalLabel">Detail Event</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Judul:</strong> <span id="modalTitle"></span></p>
                    <p><strong>Tanggal:</strong> <span id="modalDate"></span></p>
                    <p><strong>Deskripsi:</strong></p>
                    <p id="modalDescription"></p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />


    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        #calendar {
            max-width: 900px;
            margin: 0 auto;
        }

        /* responsif untuk mobile */
        @media (max-width: 768px) {
            #calendar {
                width: 100%;
                font-size: 13px;
            }

            .fc-toolbar {
                flex-direction: column;
                gap: 5px;
            }

            .fc-toolbar-title {
                font-size: 16px;
            }
        }
    </style>

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
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 'auto',
                events: [{
                        title: 'Rapat Tim',
                        start: '2025-10-29',
                        description: 'Diskusi proyek laravel dan testing API'
                    },
                    {
                        title: 'Ujian Tengah Semester',
                        start: '2025-10-30',
                        description: 'UTS Pemrograman Lanjut'
                    }
                ],
                eventClick: function(info) {
                    // isi data ke modal
                    document.getElementById('modalTitle').innerText = info.event.title;
                    document.getElementById('modalDate').innerText = info.event.start
                        .toLocaleDateString();
                    document.getElementById('modalDescription').innerText = info.event.extendedProps
                        .description || '-';

                    // tampilkan modal (pakai jQuery)
                    $('#eventModal').modal('show');
                }
            });
            calendar.render();
        });
    </script>

    </script>
    <script>
        // ==================== CHART GURU (DATA REAL DARI DATABASE) ====================

        // Data dari controller
        const totalGuruYayasan = {{ $totalSemuaGuru }};
        const statistikHariIni = @json($statistikHariIni);

        console.log('Total Guru:', totalGuruYayasan);
        console.log('Statistik Hari Ini:', statistikHariIni);

        // Ambil data presensi 14 hari terakhir dari database (langsung dari controller)
        let hadirData = [];
        let izinData = [];
        let tidakHadirData = [];

        // Data sudah disiapkan di controller
        const dataFromDB = @json($chartData);

        console.log('Data Chart dari Controller:', dataFromDB);

        // Populate data dari PHP ke JavaScript
        dataFromDB.forEach(function(item) {
            hadirData.push({
                x: new Date(item.tanggal).getTime(),
                y: item.hadir
            });
            izinData.push({
                x: new Date(item.tanggal).getTime(),
                y: item.izin
            });
            tidakHadirData.push({
                x: new Date(item.tanggal).getTime(),
                y: item.tidak_hadir
            });
        });

        console.log('Hadir Data:', hadirData);
        console.log('Izin Data:', izinData);
        console.log('Tidak Hadir Data:', tidakHadirData);

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
                    name: 'Tidak Hadir',
                    data: tidakHadirData
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
                max: Math.max(totalGuruYayasan, 10), // Minimal 10 untuk tampilan
                tickAmount: 7,
                labels: {
                    formatter: function(value) {
                        return Math.round(value);
                    }
                }
            },
            markers: {
                size: 4,
                hover: {
                    size: 6
                }
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
                position: 'top',
                horizontalAlign: 'center'
            },
            tooltip: {
                x: {
                    format: 'dd MMM yyyy'
                },
                y: {
                    formatter: function(value) {
                        return Math.round(value) + ' guru';
                    }
                }
            },
            grid: {
                borderColor: '#e7e7e7',
                strokeDashArray: 5
            },
            noData: {
                text: 'Belum ada data presensi',
                align: 'center',
                verticalAlign: 'middle',
                style: {
                    fontSize: '16px'
                }
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
