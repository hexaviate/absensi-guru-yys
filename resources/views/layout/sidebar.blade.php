<<<<<<< HEAD
 <div class="sidebar-brand">
     <img src="{{ asset('asset/image/logoYayasan.png') }}" alt="Logo" width="" height="100pt" class="mt-2">
 </div>
 <div class="sidebar-brand sidebar-brand-sm">
     {{-- <a href="index.html">Slf</a> --}}
     <img src="{{ asset('asset/image/logoYayasan.png') }}" alt="Logo" width="50pt" class="mt-1">
 </div>
 <ul class="sidebar-menu">
     <li class="menu-header mt-5">Dashboard</li>
     <li class="{{ Route::is('dashboard') ? 'active' : '' }}">
         <a href="{{ route('dashboard') }}" class="nav-link">
             <i class="fas fa-home"></i>
             <span>Dashboard</span>
         </a>
     </li>


     @hasanyrole('admin_yayasan|operator_instansi')
         <li class="menu-header">Management</li>
         <li
             class="dropdown {{ Route::is('role.*') || Route::is('user.*') || Route::is('instansi.*') || Route::is('tapel.*') || Route::is('jadwal.*') || Route::is('hariLibur.*') ? 'active' : '' }}">
             <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                 <i class="fas fa-cogs"></i> <span>Management</span>
             </a>
             <ul class="dropdown-menu">
                 <li class="{{ Route::is('role.*') ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('role.index') }}">Management Peran</a>
                 </li>
                 <li class="{{ Route::is('user.*') ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('user.index') }}">Management User</a>
                 </li>
                 <li class="{{ Route::is('instansi.*') ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('instansi.index') }}">Management Instansi</a>
                 </li>
                 <li class="{{ Route::is('tapel.*') ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('tapel.index') }}">Management Kaldik</a>
                 </li>
                 <li class="{{ Route::is('jadwal.*') ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('jadwal.index') }}">Management Jadwal</a>
                 </li>
                 <li class="{{ Route::is('hariLibur.*') ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('hariLibur.index') }}">Management Hari Libur</a>
                 </li>
             </ul>
         </li>
     @endhasrole

     <li class="menu-header">PRESENSI</li>

     <li>
         <a href="{{ route('jadwalUser') }}" class="nav-link">
             <i class="fas fa-calendar-alt"></i>
             <span>Halaman Jadwal</span>
         </a>
     </li>

     <li class="">
         <a href="{{ route('presensi') }}" class="nav-link">
             <i class="fas fa-chalkboard-teacher"></i>
             <span>Halaman Presensi</span>
         </a>
     </li>

     {{-- menu izin (user) --}}
     <li class="dropdown {{ request()->is('izinIndexUser') || request()->is('viewIzinCreate') ? 'active' : '' }}">
         <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
             <i class="fas fa-file-signature"></i> <span>Izin</span>
         </a>
         <ul class="dropdown-menu">
             <li class="{{ request()->routeIs('izinIndexUser') ? 'active' : '' }}">
                 <a class="nav-link" href="{{ route('izinIndexUser') }}">Daftar Izin</a>
             </li>
             <li class="{{ request()->routeIs('viewIzinCreate') ? 'active' : '' }}">
                 <a class="nav-link" href="{{ route('viewIzinCreate') }}">Buat Izin</a>
             </li>
         </ul>
     </li>

     {{-- menu cek izin (operator/admin) --}}
     <li class="dropdown {{ request()->is('izinIndexOperator') || request()->is('viewIzinVerify') ? 'active' : '' }}">
         <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
             <i class="fas fa-user-check"></i> <span>Verifikasi Izin</span>
         </a>
         <ul class="dropdown-menu">
             <li class="{{ request()->routeIs('izinIndexOperator') ? 'active' : '' }}">
                 <a class="nav-link" href="{{ route('izinIndexOperator') }}">Daftar Izin</a>
             </li>
         </ul>
     </li>


     <li class="menu-header">Rekap</li>
     <li class="">
         <a href="#" class="nav-link"> <i class="fas fa-clipboard-list"></i><span>Laporan Absensi</span></a>
     </li>
=======
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
>>>>>>> 80d038483f0a06e0601971c98f4147d843b7b7af
