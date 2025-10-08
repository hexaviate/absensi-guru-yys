<form class="form-inline mr-auto">
    <ul class="navbar-nav mr-3">
        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
        <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
    </ul>
</form>
<ul class="navbar-nav navbar-right">
    <li class="dropdown"><a href="#" data-toggle="dropdown"
            class="nav-link dropdown-toggle nav-link-lg nav-link-user">
            <img src="{{ $user->foto ? asset('foto/' . $user->foto) : asset('foto/default.jpg') }}" alt="Foto Profil" style=" border: 2px solid rgba(255, 255, 255, 0.3);"
                class="rounded-circle mr-1">
            <div class="d-sm-none d-lg-inline-block"></div>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            <div class="dropdown-title">Logged in 5 min ago</div>
            <a href="{{ route('viewProfile') }}" class="dropdown-item has-icon">
                <i class="far fa-user"></i> Profile
            </a>
            <div class="dropdown-divider"></div>
            <form action="{{ Route('logout') }}" method="POST">
                @csrf
                <button type="submit">
                    <a class="dropdown-item has-icon text-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </button>
            </form>

        </div>

    </li>
</ul>
