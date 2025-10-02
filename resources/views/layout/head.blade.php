<meta charset="UTF-8">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
<title>General Dashboard &mdash; Stisla</title>

@stack('style')


<!-- General CSS Files -->
<link rel="stylesheet" href="{{ asset('asset/dist/assets/modules/bootstrap/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('asset/dist/assets/modules/fontawesome/css/all.min.css') }}">

<!-- CSS Libraries -->
<link rel="stylesheet" href="{{ asset('asset/dist/assets/modules/jqvmap/dist/jqvmap.min.css') }}">
<link rel="stylesheet" href="{{ asset('asset/dist/assets/modules/weather-icon/css/weather-icons.min.css') }}">
<link rel="stylesheet" href="{{ asset('asset/dist/assets/modules/weather-icon/css/weather-icons-wind.min.css') }}">
<link rel="stylesheet" href="{{ asset('asset/dist/assets/modules/summernote/summernote-bs4.css') }}">
{{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
<!-- Template CSS -->
<link rel="stylesheet" href="{{ asset('asset/dist/assets/css/style.css') }}">
<link rel="stylesheet" href="{{asset("asset/dist/assets/modules/ionicons/css/ionicons.min.css")}}">
  <link rel="stylesheet" href="{{asset("asset/dist/assets/modules/izitoast/css/iziToast.min.css")}}">
<link rel="stylesheet" href="{{ asset('asset/dist/assets/css/components.css') }}">
<!-- Start GA -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'UA-94034622-3');
</script>

<style>
    .card-sukes {
            border-top: 4px solid #6CC070 !important;
        }

        .card-wng {
            border-top: 4px solid #FFA500 !important;
        }

        .card-dgr {
            border-top: 4px solid #D14249 !important;
        }

        .empty-state {
            padding: 3rem 1rem;
        }

        .badge-sukes {
            background-color: #6CC070;
            color: white;
        }

        .badge-dgr {
            color: white;
            background-color: #D14249;
        }
        .badge-wng {
            color: white;
            background-color: #FFA500;
        }
</style>
<!-- /END GA -->
