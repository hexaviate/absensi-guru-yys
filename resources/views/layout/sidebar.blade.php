<div class="sidebar-brand">
    <img src="{{ asset('asset/image/logoYayasan.png') }}" alt="Logo" width="" height="100pt" class="mt-2">
</div>
<div class="sidebar-brand sidebar-brand-sm">
    {{-- <a href="index.html">Slf</a> --}}
    <img src="{{ asset('asset/image/logoYayasan.png') }}" alt="Logo" width="50pt" class="mt-1">
</div>
<ul class="sidebar-menu">
    <li class="menu-header mt-5">Dashboard</li>
    <li class="active">
        <a href="{{ url('/') }}" class="nav-link"><i class="fas fa-house"></i><span>Dashboard</span></a>
    </li>
    <li class="menu-header">Management</li>
    <li class="dropdown">
        <a href="{{ route('role.index') }}" class="nav-link"><i class="fa-solid fa-user-shield"></i><span>Management
                Peran</span></a>
        <a href="{{ route('user.index') }}" class="nav-link"><i class="fa-solid fa-user-tie"></i><span>Management
                User</span></a>
        <a href="{{ route('instansi.index') }}" class="nav-link"><i class="fa-solid fa-school"></i><span>Management
                Instansi</span></a>
        <a href="" class="nav-link"><i class="fa-solid fa-calendar-check"></i><span>Kelola Presensi</span></a>
        <a href="{{ route('tapel.index') }}" class="nav-link"><i class="fa-solid fa-calendar-alt"></i><span>Management
                Kaldik</span></a>
        <a href="#" class="nav-link"><i class="fa-solid fa-calendar-day"></i><span>Management Jadwal</span></a>
        <a href="#" class="nav-link"><i class="fa-solid fa-umbrella-beach"></i><span>Management Hari
                Libur</span></a>
    </li>
    <li class="menu-header">Rekap</li>
    <li class="">
        <a href="{{ route('rekap_absensi.index') }}" class="nav-link"><i
                class="fa-solid fa-file-lines"></i><span>Laporan Absensi</span></a>
    </li>
