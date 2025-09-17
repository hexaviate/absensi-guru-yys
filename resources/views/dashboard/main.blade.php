@extends('layout.main')
@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Dashboard</h1>
        </div>
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-body">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="card-content">
                            <div class="card-title">Total Guru PAUD</div>
                            <div class="card-value">125</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-body">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div class="card-content">
                            <div class="card-title">Total Guru MI</div>
                            <div class="card-value">89</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-body">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-content">
                            <div class="card-title">Total Guru MTs</div>
                            <div class="card-value">156</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-body">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="card-content">
                            <div class="card-title">Total Guru MA</div>
                            <div class="card-value">78</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Baris 2: 2 Card Besar -->
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-body">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-tools"></i>
                        </div>
                        <div class="card-content">
                            <div class="card-title">Total Guru SMK</div>
                            <div class="card-value">234</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-body">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-mosque"></i>
                        </div>
                        <div class="card-content">
                            <div class="card-title">Total Guru Patta</div>
                            <div class="card-value">67</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Baris 3: 3 Card Kehadiran -->
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                <div class="card card-statistic-1">
                    <div class="card-body">
                        <div class="card-icon bg-success">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="card-content">
                            <div class="card-title">Guru Hadir Hari Ini</div>
                            <div class="card-value">642</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                <div class="card card-statistic-1">
                    <div class="card-body">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-user-times"></i>
                        </div>
                        <div class="card-content">
                            <div class="card-title">Guru Izin Hari Ini</div>
                            <div class="card-value">15</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                <div class="card card-statistic-1">
                    <div class="card-body">
                        <div class="card-icon bg-success">
                            <i class="fas fa-percentage"></i>
                        </div>
                        <div class="card-content">
                            <div class="card-title">Persentase Kehadiran</div>
                            <div class="card-value">97.7%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Baris 4: 3 Card Data Umum -->
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-body">
                        <div class="card-icon bg-info">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="card-content">
                            <div class="card-title">Jumlah Instansi</div>
                            <div class="card-value">12</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-body">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="card-content">
                            <div class="card-title">Jumlah Jadwal</div>
                            <div class="card-value">1,248</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-body">
                        <div class="card-icon bg-info">
                            <i class="fas fa-users-cog"></i>
                        </div>
                        <div class="card-content">
                            <div class="card-title">Total Guru Yayasan</div>
                            <div class="card-value">749</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 col-md-12 col-12 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Statistics</h4>
                        <div class="card-header-action">
                            <div class="btn-group">
                                <a href="#" class="btn btn-primary">Week</a>
                                <a href="#" class="btn">Month</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="myChart" height="182"></canvas>
                        <div class="statistic-details mt-sm-4">
                            <div class="statistic-details-item">
                                <span class="text-muted"><span class="text-primary"><i
                                            class="fas fa-caret-up"></i></span>
                                    7%</span>
                                <div class="detail-value">$243</div>
                                <div class="detail-name">Today's Sales</div>
                            </div>
                            <div class="statistic-details-item">
                                <span class="text-muted"><span class="text-danger"><i
                                            class="fas fa-caret-down"></i></span>
                                    23%</span>
                                <div class="detail-value">$2,902</div>
                                <div class="detail-name">This Week's Sales</div>
                            </div>
                            <div class="statistic-details-item">
                                <span class="text-muted"><span class="text-primary"><i
                                            class="fas fa-caret-up"></i></span>9%</span>
                                <div class="detail-value">$12,821</div>
                                <div class="detail-name">This Month's Sales</div>
                            </div>
                            <div class="statistic-details-item">
                                <span class="text-muted"><span class="text-primary"><i
                                            class="fas fa-caret-up"></i></span>
                                    19%</span>
                                <div class="detail-value">$92,142</div>
                                <div class="detail-name">This Year's Sales</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12 col-12 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Recent Activities</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled list-unstyled-border">
                            <li class="media">
                                <img class="mr-3 rounded-circle" width="50" src="assets/img/avatar/avatar-1.png"
                                    alt="avatar">
                                <div class="media-body">
                                    <div class="float-right text-primary">Now</div>
                                    <div class="media-title">Farhan A Mujib</div>
                                    <span class="text-small text-muted">Cras sit amet nibh libero, in
                                        gravida nulla. Nulla vel metus scelerisque ante sollicitudin.</span>
                                </div>
                            </li>
                            <li class="media">
                                <img class="mr-3 rounded-circle" width="50" src="assets/img/avatar/avatar-2.png"
                                    alt="avatar">
                                <div class="media-body">
                                    <div class="float-right">12m</div>
                                    <div class="media-title">Ujang Maman</div>
                                    <span class="text-small text-muted">Cras sit amet nibh libero, in
                                        gravida nulla. Nulla vel metus scelerisque ante sollicitudin.</span>
                                </div>
                            </li>
                            <li class="media">
                                <img class="mr-3 rounded-circle" width="50" src="assets/img/avatar/avatar-3.png"
                                    alt="avatar">
                                <div class="media-body">
                                    <div class="float-right">17m</div>
                                    <div class="media-title">Rizal Fakhri</div>
                                    <span class="text-small text-muted">Cras sit amet nibh libero, in
                                        gravida nulla. Nulla vel metus scelerisque ante sollicitudin.</span>
                                </div>
                            </li>
                            <li class="media">
                                <img class="mr-3 rounded-circle" width="50" src="assets/img/avatar/avatar-4.png"
                                    alt="avatar">
                                <div class="media-body">
                                    <div class="float-right">21m</div>
                                    <div class="media-title">Alfa Zulkarnain</div>
                                    <span class="text-small text-muted">Cras sit amet nibh libero, in
                                        gravida nulla. Nulla vel metus scelerisque ante sollicitudin.</span>
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

@push('style')
<!-- CSS Styles -->
        <style>
            .card-statistic-1 {
                background: white;
                border-radius: 8px;
                border: 1px solid #e9ecef;
                box-shadow: 0 2px 6px 0 rgba(4, 26, 55, 0.16);
                transition: all 0.3s ease-in-out;
                overflow: hidden;
                margin-bottom: 20px;
                height: auto;
            }

            .card-statistic-1:hover {
                box-shadow: 0 4px 12px 0 rgba(4, 26, 55, 0.2);
                transform: translateY(-2px);
            }

            .card-statistic-1 .card-body {
                padding: 20px;
                display: flex;
                align-items: center;
            }

            .card-statistic-1 .card-icon {
                width: 60px;
                height: 60px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                margin-right: 15px;
            }

            .card-statistic-1 .card-icon i {
                font-size: 24px;
                color: white;
            }

            .card-statistic-1 .card-content {
                flex: 1;
                min-width: 0;
            }

            .card-statistic-1 .card-title {
                font-size: 13px;
                color: #6c757d;
                margin: 0 0 8px 0;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                line-height: 1.2;
                word-wrap: break-word;
            }

            .card-statistic-1 .card-value {
                font-size: 28px;
                font-weight: 700;
                color: #34395e;
                margin: 0;
                line-height: 1;
            }

            /* Background Colors */
            .bg-primary {
                background: linear-gradient(45deg, #4099ff, #73b4ff);
            }

            .bg-success {
                background: linear-gradient(45deg, #2ed8b6, #59e0c5);
            }

            .bg-danger {
                background: linear-gradient(45deg, #FF5370, #ff869a);
            }

            .bg-warning {
                background: linear-gradient(45deg, #FFB64D, #ffcb80);
            }

            .bg-info {
                background: linear-gradient(45deg, #17a2b8, #5bc0de);
            }

            /* Responsive */
            @media (max-width: 768px) {
                .card-statistic-1 .card-body {
                    padding: 15px;
                }

                .card-statistic-1 .card-icon {
                    width: 50px;
                    height: 50px;
                    margin-right: 12px;
                }

                .card-statistic-1 .card-icon i {
                    font-size: 20px;
                }

                .card-statistic-1 .card-value {
                    font-size: 24px;
                }

                .card-statistic-1 .card-title {
                    font-size: 12px;
                }
            }

            @media (max-width: 576px) {
                .card-statistic-1 .card-body {
                    padding: 12px;
                }

                .card-statistic-1 .card-icon {
                    width: 45px;
                    height: 45px;
                    margin-right: 10px;
                }

                .card-statistic-1 .card-icon i {
                    font-size: 18px;
                }

                .card-statistic-1 .card-value {
                    font-size: 22px;
                }
            }
        </style>

@endpush

