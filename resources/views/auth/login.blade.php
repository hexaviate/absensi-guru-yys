<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Login — Absensi Guru Yayasan</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('asset/dist/assets/modules/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/dist/assets/modules/fontawesome/css/all.min.css') }}">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('asset/dist/assets/modules/bootstrap-social/bootstrap-social.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/dist/assets/modules/izitoast/css/iziToast.min.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('asset/dist/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/dist/assets/css/components.css') }}">

    <link rel="shortcut icon" href="{{ asset('/') }}/asset/image/logoYayasan.png" type="image/x-icon">

    <!-- Start GA -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
</head>

<body>
    <div id="app">
        <section class="section section d-flex align-items-center justify-content-center" style="min-height: 100vh">
            <div class="container">
                <div class="row">
                    <div
                        class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                        <div class="login-brand text-center">
                            <img src="{{ asset('asset/image/logoYayasan.svg') }}" alt="logo" width="100"
                                class="">

                            {{-- <h2 class="fw-bold text-primary mb-1 mt-4" style="letter-spacing: 2px;">SIAP</h2>
                            <p class="text-primary mb-0" style="font-size: 14px;">Sistem Informasi Administrasi Presensi
                            </p> --}}
                        </div>


                        <div class="card card-primary shadow-lg">
                            <div class="card-header">
                                <h4>Login</h4>
                            </div>

                            <div class="card-body">
                                <form method="POST" action="{{ route('doLogin') }}" class="needs-validation"
                                    novalidate="">
                                    @csrf
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input id="username" type="text" class="form-control" name="username"
                                            tabindex="1" required autofocus>
                                        <div class="invalid-feedback">
                                            Tolong Isi Username Anda
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password" name="password"
                                                required tabindex="2">

                                            <div class="input-group-append">
                                                <span class="input-group-text" style="cursor: pointer;" tabindex="3">
                                                    <i class="fa fa-eye toggle-password"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="invalid-feedback">
                                            Tolong isi Password Anda
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                            Login
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- General JS Scripts - LOAD FIRST -->
    <script src="{{ asset('asset/dist/assets/modules/jquery.min.js') }}"></script>
    <script src="{{ asset('asset/dist/assets/modules/izitoast/js/iziToast.min.js') }}"></script>
    <script src="{{ asset('asset/dist/assets/modules/popper.js') }}"></script>
    <script src="{{ asset('asset/dist/assets/modules/tooltip.js') }}"></script>
    <script src="{{ asset('asset/dist/assets/modules/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('asset/dist/assets/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('asset/dist/assets/modules/moment.min.js') }}"></script>
    <script src="{{ asset('asset/dist/assets/js/stisla.js') }}"></script>

    <!-- Template JS File -->
    <script src="{{ asset('asset/dist/assets/js/scripts.js') }}"></script>
    <script src="{{ asset('asset/dist/assets/js/custom.js') }}"></script>

    <!-- Error Toast - LOAD AFTER iziToast -->
    <script>
        @if (session('error'))
            document.addEventListener("DOMContentLoaded", function() {
                iziToast.error({
                    title: 'Error',
                    message: "{{ session('error') }}",
                    position: 'topRight'
                });
            });
        @endif
    </script>

    <!-- Eye Toggle -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggle = document.querySelector(".toggle-password");
            const input = document.getElementById("password");

            toggle.addEventListener("click", function() {
                const type = input.getAttribute("type") === "password" ? "text" : "password";
                input.setAttribute("type", type);
                this.classList.toggle("fa-eye");
                this.classList.toggle("fa-eye-slash");
            });
        });
    </script>
</body>

</html>
