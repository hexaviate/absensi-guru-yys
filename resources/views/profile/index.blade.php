@php
    $user = auth()->user();
@endphp

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

                            <!-- Edit Button -->
                            <a href="{{ route('viewEditProfile', $user->id) }}" class="btn-edit-profile">
                                <i class="fas fa-edit"></i>
                                <span>Edit Profile</span>
                            </a>

                            <div class="profile-content">
                                <div class="profile-avatar">
                                    <div class="avatar-wrapper">
                                        <img src="
                                               @if (!empty($user->foto)) {{ asset('foto/' . $user->foto) }}
                                               @elseif (!empty($user->foto_presensi))
                                                   {{ asset('foto_presensi/' . $user->foto_presensi) }}
                                               @else
                                                   {{ asset('foto/default.jpg') }} @endif "
                                            alt="Foto Profil" class="avatar-img" style="object-fit: cover;">
                                        <div class="avatar-ring"></div>
                                    </div>
                                </div>

                                <!-- User Info -->
                                <div class="user-info">
                                    <h4 class="user-name">{{ $user->name ?? 'Nama User' }}</h4>
                                    <div class="user-info text-white">
                                        @if ($user->hasRole('admin_yayasan'))
                                            <span class="font-weight-bold">Admin Yayasan</span>
                                        @elseif ($user->hasRole('operator_instansi'))
                                            @foreach ($instansiName as $item)
                                                <span class="font-weight-bold">Operator {{ $item->nama_instansi }}</span>
                                            @endforeach
                                        @elseif ($user->hasRole('tenaga_pendidik') || $user->hasRole('tenaga_kependidikan'))
                                            <span class="font-weight-bold">{{ $user->name }}</span>
                                        @else
                                            <span class="font-weight-bold">Role Tidak Dikenal</span>
                                        @endif
                                    </div>
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

                                <!-- Instansi Card (Di dalam Background Biru) -->
                                <div class="instansi-section">
                                    <div class="instansi-card-wrapper">
                                        <div class="instansi-card-header">
                                            <h6 class="text-center text-dark">Daftar Instansi</h6>
                                        </div>

                                        @if ($instansiName && $instansiName->count() > 0)
                                            @php
                                                // cek apakah ada instansi dengan nama 'puspela'
                                                $puspela = $instansiName->firstWhere('nama_instansi', 'PUSPELA');
                                            @endphp

                                            <div class="instansi-list">
                                                @if ($puspela)
                                                    {{-- tampilkan hanya instansi puspela --}}
                                                    <div class="instansi-card">
                                                        <div class="instansi-number">1</div>
                                                        <div class="instansi-detail">
                                                            <h6>{{ $puspela->nama_instansi }}</h6>
                                                            <p>
                                                                <i class="fas fa-map-marker-alt"></i>
                                                                {{ $puspela->alamat_instansi ?? 'Alamat tidak tersedia' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                @else
                                                    {{-- kalau tidak ada puspela, tampilkan semua instansi --}}
                                                    @foreach ($instansiName as $index => $instansi)
                                                        <div class="instansi-card">
                                                            <div class="instansi-number">{{ $index + 1 }}</div>
                                                            <div class="instansi-detail">
                                                                <h6>{{ $instansi->nama_instansi ?? 'Nama Instansi' }}</h6>
                                                                <p>
                                                                    <i class="fas fa-map-marker-alt"></i>
                                                                    {{ $instansi->alamat_instansi ?? 'Alamat tidak tersedia' }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
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
            padding: 30px 20px 30px;
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

        /* Edit Button */
        .btn-edit-profile {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 10;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            color: #6777ef;
            padding: 12px 24px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .btn-edit-profile:hover {
            background: white;
            color: #4d63d5;
            border-color: rgba(103, 119, 239, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(103, 119, 239, 0.3);
            text-decoration: none;
        }

        .btn-edit-profile:active {
            transform: translateY(0);
        }

        .btn-edit-profile i {
            font-size: 16px;
        }

        .profile-content {
            position: relative;
            z-index: 2;
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
        /* Profile Avatar */
        .profile-avatar {
            text-align: center;
            margin-bottom: 20px;
        }

        .avatar-wrapper {
            position: relative;
            display: inline-block;
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

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.05);
                opacity: 0.7;
            }
        }
        @keyframes pulse-ring {

            0%,
            100% {
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
        .user-role {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            margin: 0;
        }

        /* Contact Info */
        .contact-info {
            max-width: 350px;
            margin: 0 auto 30px;
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
        .contact-text p {
            color: white;
            font-weight: 600;
            margin: 0;
            font-size: 15px;
        }

        /* Instansi Section (Di dalam Background Biru) */
        .instansi-section {
            max-width: 600px;
            margin: 0 auto;
        }

        .instansi-card-wrapper {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .instansi-card-header {
            margin-bottom: 15px;
            padding-bottom: 12px;
            border-bottom: 2px solid #e3e8ef;
        }

        .section-title {
            color: #6777ef;
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 0;
        }

        /* Instansi List */
        .instansi-list {
            margin-top: 0;
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
        .instansi-card:last-child {
            margin-bottom: 0;
        }

        .instansi-card:hover {
            transform: translateY(-2px);
            background: white;
            border-color: #6777ef;
            box-shadow: 0 2px 8px rgba(103, 119, 239, 0.2);
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

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 30px 20px;
            background: #f8f9fc;
            border-radius: 12px;
            margin-top: 0;
        }

        .empty-state i {
            font-size: 50px;
            color: #d1d5db;
            margin-bottom: 12px;
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
        .empty-state p {
            color: #8898aa;
            font-size: 14px;
            margin: 0;
        }

        /* Responsive Adjustments */
        @media (max-width: 576px) {
            .profile-header {
                padding: 25px 15px 25px;
            }

            .btn-edit-profile {
                padding: 10px 18px;
                font-size: 13px;
            }

            .btn-edit-profile span {
                display: none;
            }

            .btn-edit-profile i {
                font-size: 18px;
            }

            .avatar-img {
                width: 100px;
                height: 100px;
            }
            .avatar-img {
                width: 100px;
                height: 100px;
            }

            .user-name {
                font-size: 20px;
            }
            .user-name {
                font-size: 20px;
            }

            .contact-item {
                padding: 12px;
            }

            .instansi-card {
                padding: 12px;
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
            .instansi-number {
                width: 35px;
                height: 35px;
                font-size: 14px;
            }
        }
    </style>
@endsection
