@extends('layout.main')

@section('main')
<section class="section">
    <div class="section-header">
        <h1>Profil Pengguna</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
            <div class="breadcrumb-item">Profil</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <!-- Profile Card -->
                <div class="profile-card shadow-lg mb-4">
                    <!-- Header with Gradient Background -->
                    <div class="profile-header">
                        <div class="gradient-bg"></div>
                        <div class="profile-content">
                            <!-- Profile Image -->
                            <div class="profile-avatar">
                                <div class="avatar-wrapper">
                                    <img src="{{ $user->foto ? asset('foto/' . $user->foto) : asset('foto/default.jpg') }}"
                                         alt="Foto Profil"
                                         class="avatar-img">
                                    <div class="avatar-ring"></div>
                                </div>
                            </div>

                            <!-- User Info -->
                            <div class="user-info">
                                <h4 class="user-name">{{ $user->name ?? 'Nama User' }}</h4>
                                <p class="user-role">Pengguna Sistem</p>
                                    @foreach ($role as $item)
                                        <h1 class="text-white">{{$item->name}}<./h1>
                                    @endforeach
                                    @foreach ($instansiName as $item)
                                        <h1 class="text-white">{{$item->nama_instansi}}</h1>
                                    @endforeach

                            </div>

                            <!-- Contact Info -->
                            <div class="contact-info">
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <div class="contact-text">
                                        <small>Nomor Telepon</small>
                                        <p>{{ $user->telp ?? '08123456789' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Body Content -->
                    <div class="profile-body">

                        <!-- Instansi Card -->
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="info-content">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">Daftar Instansi</h6>
                                    @if($instansiName && $instansiName->count() > 0)
                                        <span class="instansi-badge">{{ $instansiName->count() }}</span>
                                    @endif
                                </div>

                                @if($instansiName && $instansiName->count() > 0)
                                    <div class="instansi-list">
                                        @foreach($instansiName as $index => $instansi)
                                            <div class="instansi-card">
                                                <div class="instansi-number">{{ $index + 1 }}</div>
                                                <div class="instansi-detail">
                                                    <h6>{{ $instansi->nama_instansi ?? 'Nama Instansi' }}</h6>
                                                    <p>
                                                        <i class="fas fa-map-marker-alt"></i>
                                                        {{ $instansi->alamat_instansi ?? 'Alamat tidak tersedia' }}
                                                    </p>
                                                </div>
                                                <div class="instansi-check">
                                                    <i class="fas fa-check-circle"></i>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="empty-state">
                                        <i class="fas fa-building"></i>
                                        <p>Belum terdaftar di instansi manapun</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* Profile Card */
    .profile-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        border: none;
    }

    /* Profile Header */
    .profile-header {
        position: relative;
        padding: 30px 20px 180px;
        overflow: hidden;
    }

    .gradient-bg {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, #6777ef 0%, #4d63d5 100%);
    }

    .gradient-bg::before,
    .gradient-bg::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
    }

    .gradient-bg::before {
        width: 200px;
        height: 200px;
        top: -50px;
        right: -50px;
    }

    .gradient-bg::after {
        width: 150px;
        height: 150px;
        bottom: -30px;
        left: -30px;
    }

    .profile-content {
        position: relative;
        z-index: 2;
    }

    /* Profile Avatar */
    .profile-avatar {
        text-align: center;
        margin-bottom: 20px;
    }

    .avatar-wrapper {
        position: relative;
        display: inline-block;
    }

    .avatar-img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 5px solid rgba(255, 255, 255, 0.9);
        object-fit: cover;
        display: block;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
    }

    .avatar-ring {
        position: absolute;
        top: -8px;
        left: -8px;
        right: -8px;
        bottom: -8px;
        border-radius: 50%;
        border: 3px solid rgba(255, 255, 255, 0.3);
        animation: pulse-ring 2s ease-in-out infinite;
    }

    @keyframes pulse-ring {
        0%, 100% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.05);
            opacity: 0.7;
        }
    }

    /* User Info */
    .user-info {
        text-align: center;
        margin-bottom: 25px;
    }

    .user-name {
        color: white;
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .user-role {
        color: rgba(255, 255, 255, 0.8);
        font-size: 14px;
        margin: 0;
    }

    /* Contact Info */
    .contact-info {
        max-width: 350px;
        margin: 0 auto;
    }

    .contact-item {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        padding: 15px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .contact-icon {
        width: 45px;
        height: 45px;
        background: rgba(255, 255, 255, 0.25);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
        flex-shrink: 0;
    }

    .contact-text {
        flex: 1;
        text-align: left;
    }

    .contact-text small {
        display: block;
        color: rgba(255, 255, 255, 0.7);
        font-size: 12px;
        margin-bottom: 3px;
    }

    .contact-text p {
        color: white;
        font-weight: 600;
        margin: 0;
        font-size: 15px;
    }

    /* Profile Body */
    .profile-body {
        padding: 20px;
        margin-top: -140px;
        position: relative;
        z-index: 3;
    }

    /* Section Title */
    .section-title {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
        padding: 20px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 2px 12px rgba(103, 119, 239, 0.1);
    }

    .title-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #6777ef 0%, #4d63d5 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 22px;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(103, 119, 239, 0.3);
    }

    .title-text h5 {
        color: #6777ef;
        font-weight: 700;
        font-size: 18px;
        margin: 0 0 3px 0;
    }

    .title-text small {
        color: #8898aa;
        font-size: 13px;
    }

    /* Info Card */
    .info-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 15px;
        box-shadow: 0 2px 12px rgba(103, 119, 239, 0.1);
        border-left: 4px solid #6777ef;
    }

    .info-card:last-child {
        margin-bottom: 0;
    }

    .info-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #6777ef 0%, #4d63d5 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        margin-bottom: 15px;
        box-shadow: 0 4px 12px rgba(103, 119, 239, 0.25);
    }

    .info-content h6 {
        color: #6777ef;
        font-weight: 700;
        font-size: 16px;
        margin-bottom: 8px;
    }

    .info-content p {
        color: #6c757d;
        font-size: 14px;
        margin: 0;
        line-height: 1.6;
    }

    /* Instansi Badge */
    .instansi-badge {
        background: #6777ef;
        color: white;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
    }

    /* Instansi List */
    .instansi-list {
        margin-top: 15px;
    }

    .instansi-card {
        background: #f8f9fc;
        border: 2px solid #e3e8ef;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.3s ease;
    }

    .instansi-card:last-child {
        margin-bottom: 0;
    }

    .instansi-card:active {
        transform: scale(0.98);
        background: white;
        border-color: #6777ef;
    }

    .instansi-number {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #6777ef 0%, #4d63d5 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 16px;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(103, 119, 239, 0.3);
    }

    .instansi-detail {
        flex: 1;
        min-width: 0;
    }

    .instansi-detail h6 {
        color: #2c3e50;
        font-weight: 700;
        font-size: 15px;
        margin: 0 0 6px 0;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .instansi-detail p {
        color: #8898aa;
        font-size: 13px;
        margin: 0;
        display: flex;
        align-items: flex-start;
        gap: 6px;
        line-height: 1.5;
    }

    .instansi-detail p i {
        color: #6777ef;
        font-size: 12px;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .instansi-check {
        flex-shrink: 0;
    }

    .instansi-check i {
        color: #28c76f;
        font-size: 20px;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 30px 20px;
        background: #f8f9fc;
        border-radius: 12px;
        margin-top: 15px;
    }

    .empty-state i {
        font-size: 50px;
        color: #d1d5db;
        margin-bottom: 12px;
    }

    .empty-state p {
        color: #8898aa;
        font-size: 14px;
        margin: 0;
    }

    /* Responsive Adjustments */
    @media (max-width: 576px) {
        .profile-header {
            padding: 25px 15px 160px;
        }

        .avatar-img {
            width: 100px;
            height: 100px;
        }

        .user-name {
            font-size: 20px;
        }

        .contact-item {
            padding: 12px;
        }

        .profile-body {
            padding: 15px;
        }

        .section-title,
        .info-card {
            padding: 15px;
        }

        .title-icon,
        .info-icon {
            width: 45px;
            height: 45px;
            font-size: 18px;
        }

        .instansi-card {
            padding: 12px;
        }

        .instansi-number {
            width: 35px;
            height: 35px;
            font-size: 14px;
        }
    }
</style>
@endsection
