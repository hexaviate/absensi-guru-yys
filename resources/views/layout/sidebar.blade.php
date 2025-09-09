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
             <i class="fas fa-house"></i>
             <span>Dashboard</span>
         </a>
     </li>
     <li class="menu-header">Management</li>
     <li class="{{ Route::is('role.*') ? 'active' : '' }}">
         <a href="{{ route('role.index') }}" class="nav-link">
             <i class="fa-solid fa-user-shield"></i>
             <span>Management Peran</span>
         </a>
     </li>
     <li class="{{ Route::is('user.*') ? 'active' : '' }}">
         <a href="{{ route('user.index') }}" class="nav-link">
             <i class="fa-solid fa-user-tie">
             </i><span>Management User</span>
         </a>
     </li>
     <li class="{{ Route::is('instansi.*') ? 'active' : '' }}">
         <a href="{{ route('instansi.index') }}" class="nav-link">
             <i class="fa-solid fa-school"></i>
             <span>Management Instansi</span>
         </a>
     </li>
     <li class="">
         <a href="#" class="nav-link">
             <i class="fa-solid fa-calendar-check"></i>
             <span>Kelola Presensi</span>
         </a>
     </li>
     <li class="{{ Route::is('tapel.*') ? 'active' : '' }}">
         <a href="{{ route('tapel.index') }}" class="nav-link">
             <i class="fa-solid fa-calendar-alt"></i>
             <span>Management Kaldik</span>
         </a>
     </li>
     <li class="{{ Route::is('jadwal.*') ? 'active' : '' }}">
         <a href="{{ route('jadwal.index') }}" class="nav-link">
             <i class="fa-solid fa-calendar-day"></i>
             <span>Management Jadwal</span>
         </a>
     </li>
     <li>
         <a href="#" class="nav-link">
             <i class="fa-solid fa-umbrella-beach">
             </i><span>Management Hari Libur</span>
         </a>
     </li>
     <li class="menu-header">Rekap</li>
     <li class="">
         <a href="#" class="nav-link"><i class="fa-solid fa-file-lines"></i><span>Laporan Absensi</span></a>
     </li>
