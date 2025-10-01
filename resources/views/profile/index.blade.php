@extends('layout.main')

@section('main')
<section class="section">
    <div class="section-header">
        <h1>Dashboard User</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
            <div class="breadcrumb-item">User</div>
        </div>
    </div>

    <div class="section-body">
        <!-- Profil User - Card Besar -->
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-body py-5">
                        <div class="row align-items-center">
                            <div class="col-md-3 text-center mb-4 mb-md-0">
                                <img src="/foto/default.jpg" alt="Profile Picture" class="rounded-circle shadow" width="150" height="150">
                            </div>
                            <div class="col-md-9">
                                <h2 class="text-white mb-3">user_demo</h2>
                                <h5 class="text-white mb-4">
                                    <i class="fas fa-phone"></i> 08123456789
                                </h5>
                                <div class="text-white">
                                    <p class="mb-2"><i class="fas fa-user-circle"></i> Status: <strong>Aktif</strong></p>
                                    <p class="mb-0"><i class="fas fa-calendar-check"></i> Member sejak: <strong>Januari 2025</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jadwal Hari Ini -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Jadwal Hari Ini</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="text-center p-4 bg-light rounded">
                                    <h5 class="text-muted mb-3">Jam Datang</h5>
                                    <h1 class="text-primary mb-0">07:00</h1>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-center p-4 bg-light rounded">
                                    <h5 class="text-muted mb-3">Jam Pulang</h5>
                                    <h1 class="text-danger mb-0">15:00</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jadwal Minggu Ini -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Jadwal Minggu Ini</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Hari</th>
                                        <th>Jam Datang</th>
                                        <th>Jam Pulang</th>
                                        <th>Nama Instansi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Senin</td>
                                        <td>07:00</td>
                                        <td>15:00</td>
                                        <td>SMA Negeri 1</td>
                                    </tr>
                                    <tr>
                                        <td>Selasa</td>
                                        <td>07:00</td>
                                        <td>15:00</td>
                                        <td>SMA Negeri 1</td>
                                    </tr>
                                    <tr>
                                        <td>Rabu</td>
                                        <td>07:00</td>
                                        <td>15:00</td>
                                        <td>SMA Negeri 1</td>
                                    </tr>
                                    <tr>
                                        <td>Kamis</td>
                                        <td>07:00</td>
                                        <td>15:00</td>
                                        <td>SMA Negeri 1</td>
                                    </tr>
                                    <tr>
                                        <td>Jumat</td>
                                        <td>07:00</td>
                                        <td>12:00</td>
                                        <td>SMA Negeri 1</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Riwayat Absensi -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Riwayat Absensi</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Hari/Tanggal</th>
                                        <th>Jam Datang</th>
                                        <th>Jam Pulang</th>
                                        <th>Nama Instansi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Senin, 2025-09-29</td>
                                        <td>07:05</td>
                                        <td>15:01</td>
                                        <td>SMA Negeri 1</td>
                                    </tr>
                                    <tr>
                                        <td>Selasa, 2025-09-28</td>
                                        <td>07:10</td>
                                        <td>15:00</td>
                                        <td>SMA Negeri 1</td>
                                    </tr>
                                    <tr>
                                        <td>Rabu, 2025-09-27</td>
                                        <td>07:02</td>
                                        <td>15:10</td>
                                        <td>SMA Negeri 1</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
