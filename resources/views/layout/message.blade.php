{{-- <script src="{{ asset('assets/modules/izitoast/js/iziToast.min.js') }}"></script> --}}
<script>
    @if(session('success'))
        iziToast.success({
            title: 'Sukses',
            message: "{{ session('success') }}",
            position: 'topRight'
        });
    @endif

    @if(session('error'))
        iziToast.error({
            title: 'Error',
            message: "{{ session('error') }}",
            position: 'topRight'
        });
    @endif
</script>
