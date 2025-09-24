<!-- General JS Scripts -->
<script src="{{asset('asset/dist/assets/modules/jquery.min.js')}}"></script>
<script src="{{asset('asset/dist/assets/modules/popper.js')}}"></script>
<script src="{{asset('asset/dist/assets/modules/tooltip.js')}}"></script>
<script src="{{asset('asset/dist/assets/modules/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('asset/dist/assets/modules/nicescroll/jquery.nicescroll.min.js')}}"></script>
<script src="{{asset('asset/dist/assets/modules/moment.min.js')}}"></script>
<script src="{{asset('asset/dist/assets/js/stisla.js')}}"></script>

<!-- JS Libraries -->
<script src="{{asset('asset/dist/assets/modules/simple-weather/jquery.simpleWeather.min.js')}}"></script>
<script src="{{asset('asset/dist/assets/modules/chart.min.js')}}"></script>
<script src="{{asset('asset/dist/assets/modules/jqvmap/dist/jquery.vmap.min.js')}}"></script>
<script src="{{asset('asset/dist/assets/modules/jqvmap/dist/maps/jquery.vmap.world.js')}}"></script>
<script src="{{asset('asset/dist/assets/modules/summernote/summernote-bs4.js')}}"></script>
<script src="{{asset('asset/dist/assets/modules/chocolat/dist/js/jquery.chocolat.min.js')}}"></script>

<!-- Page Specific JS File -->
<script src="{{asset('asset/dist/assets/js/page/index-0.js')}}"></script>

<!-- Template JS File -->
<script src="{{asset('asset/dist/assets/js/scripts.js')}}"></script>
<script src="{{asset('asset/dist/assets/js/custom.js')}}"></script>

<!-- Font Awesome -->
{{-- <script src="https://kit.fontawesome.com/40f5c7c2d3.js" crossorigin="anonymous"></script> --}}

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const alert = document.getElementById("alertMessage");
        if (alert) {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 3000);
        }
    });
</script>


@stack('script')
