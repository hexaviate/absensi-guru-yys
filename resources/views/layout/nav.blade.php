@php
    $user = auth()->user();

    if (!empty($user->foto)) {
        $fotoPath = asset('foto/' . $user->foto);
    } elseif (!empty($user->foto_presensi)) {
        $fotoPath = asset('foto_presensi/' . $user->foto_presensi);
    } else {
        $fotoPath = asset('foto/default.jpg');
    }
@endphp

<form class="form-inline mr-auto">
    <ul class="navbar-nav mr-3">
        <li>
            <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>
</form>

<ul class="navbar-nav navbar-right">
    <li class="dropdown">
        <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
            <img src="{{ $fotoPath }}" alt="Foto Profil" class="rounded-circle mr-2"
                style="width: 40px; height: 40px; object-fit: cover; border: 2px solid rgba(255, 255, 255, 0.3);">
            <div class="d-sm-none d-lg-inline-block">
                {{ $user->name }}
            </div>
        </a>

        <div class="dropdown-menu dropdown-menu-right">

            <a href="{{ route('viewProfile') }}" class="dropdown-item has-icon">
                <i class="far fa-user"></i> Profile
            </a>

            <div class="dropdown-divider"></div>

            <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                @csrf
                <button type="submit" class="dropdown-item text-danger d-flex align-items-center"
                    style="border: none; background: none; width: 100%; text-align: left;">
                    <i class="fas fa-sign-out-alt mr-2" style="font-size: 14px; line-height: 1;"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>

    </li>
</ul>
