@extends('layout.main')
@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Dashboard</h1>
        </div>

        <div class="row">
    <!-- Total Guru per Jenjang -->
    <div class="col-lg-8 col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Total Guru per Jenjang</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-6 mb-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center py-4">
                                <h2 class="mb-2">125</h2>
                                <p class="mb-0">PAUD</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-6 mb-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center py-4">
                                <h2 class="mb-2">89</h2>
                                <p class="mb-0">MI</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-6 mb-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center py-4">
                                <h2 class="mb-2">156</h2>
                                <p class="mb-0">MTs</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-6 mb-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center py-4">
                                <h2 class="mb-2">78</h2>
                                <p class="mb-0">MA</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-6 mb-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body text-center py-4">
                                <h2 class="mb-2">234</h2>
                                <p class="mb-0">SMK</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-6 mb-3">
                        <div class="card bg-secondary text-white">
                            <div class="card-body text-center py-4">
                                <h2 class="mb-2">67</h2>
                                <p class="mb-0">PATTA</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Guru dipindahkan ke sini -->
        <div class="card">
            <div class="card-header">
                <h4>Guru</h4>
            </div>
            <div class="card-body">
                <div id="chart"></div>
            </div>
        </div>
    </div>

    <!-- Data Umum -->
    <div class="col-lg-4 col-md-12">
        <div class="card">
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
        <div class="card">
            <div class="card-header">
                <h4>Riwayat Absensi</h4>
            </div>
            <div class="card-body">
                <ul class="list-unstyled list-unstyled-border">
                    <li class="media">
                        <img class="mr-3 rounded-circle" width="50" src="assets/img/avatar/avatar-1.png"
                            alt="avatar">
                        <div class="media-body">
                            <div class="float-right text-primary">Now</div>
                            <div class="media-title">Farhan A Mujib</div>
                        </div>
                    </li>
                    <li class="media">
                        <img class="mr-3 rounded-circle" width="50" src="assets/img/avatar/avatar-1.png"
                            alt="avatar">
                        <div class="media-body">
                            <div class="float-right text-primary">Now</div>
                            <div class="media-title">Farhan A Mujib</div>
                        </div>
                    </li>
                    <li class="media">
                        <img class="mr-3 rounded-circle" width="50" src="assets/img/avatar/avatar-1.png"
                            alt="avatar">
                        <div class="media-body">
                            <div class="float-right text-primary">Now</div>
                            <div class="media-title">Farhan A Mujib</div>
                        </div>
                    </li>
                    <li class="media">
                        <img class="mr-3 rounded-circle" width="50" src="assets/img/avatar/avatar-1.png"
                            alt="avatar">
                        <div class="media-body">
                            <div class="float-right text-primary">Now</div>
                            <div class="media-title">Farhan A Mujib</div>
                        </div>
                    </li>
                    <li class="media">
                        <img class="mr-3 rounded-circle" width="50" src="assets/img/avatar/avatar-1.png"
                            alt="avatar">
                        <div class="media-body">
                            <div class="float-right text-primary">Now</div>
                            <div class="media-title">Farhan A Mujib</div>
                        </div>
                    </li>
                    <li class="media">
                        <img class="mr-3 rounded-circle" width="50" src="assets/img/avatar/avatar-2.png"
                            alt="avatar">
                        <div class="media-body">
                            <div class="float-right">12m</div>
                            <div class="media-title">Ujang Maman</div>
                        </div>
                    </li>
                    <li class="media">
                        <img class="mr-3 rounded-circle" width="50" src="assets/img/avatar/avatar-3.png"
                            alt="avatar">
                        <div class="media-body">
                            <div class="float-right">17m</div>
                            <div class="media-title">Rizal Fakhri</div>
                        </div>
                    </li>
                    <li class="media">
                        <img class="mr-3 rounded-circle" width="50" src="assets/img/avatar/avatar-4.png"
                            alt="avatar">
                        <div class="media-body">
                            <div class="float-right">21m</div>
                            <div class="media-title">Alfa Zulkarnain</div>
                        </div>
                    </li>
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
    series: [
        {
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
    chart.updateSeries([
        {
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
