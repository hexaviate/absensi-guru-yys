@extends('layout.main')
@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Tambah User</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">User</a></div>
                <div class="breadcrumb-item">Dashboard</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Form Tambah User</h5>
                </div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <div class="card-body">
                    <form enctype="multipart/form-data" action="{{ route('user.store') }}" method="POST">
                        @csrf

                        <!-- nama & telp -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Masukkan nama" value="{{ old('name') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="telp" class="form-label">No Telepon</label>
                                <input type="text" class="form-control" id="telp" name="telp"
                                    placeholder="08xxxxxxxxxx" value="{{ old('telp') }}">
                            </div>
                        </div>

                        <!-- username & password -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" id="username"
                                    placeholder="Masukkan username" value="{{ old('username') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Masukkan password">
                            </div>
                        </div>

                        <!-- foto_presensi & jarak_tempuh -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="foto_presensi" class="form-label">Foto Presensi</label>
                                <input type="file" class="form-control" id="foto_presensi" name="foto_presensi">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="jarak_tempuh" class="form-label">Jarak Tempuh (km)</label>
                                <input type="number" class="form-control" id="jarak_tempuh" name="jarak_tempuh"
                                    placeholder="Masukkan jarak tempuh" value="{{ old('jarak_tempuh') }}" step="0.01">
                            </div>
                        </div>

                        <!-- role & instansi -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Peran <span class="text-danger">*</span></label>
                                <div class="selectgroup selectgroup-pills">
                                    @forelse ($role as $item)
                                        <label class="selectgroup-item">
                                            <input type="radio" name="role_id[]" value="{{ $item->id }}"
                                                data-role-name="{{ $item->name }}" class="selectgroup-input role-radio"
                                                {{ old('role_id') == $item->id ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ Str::of($item->name)->replace('_', ' ')->title() }}</span>
                                        </label>
                                    @empty
                                        <p class="text-muted mb-0">Tidak ada role</p>
                                    @endforelse
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Instansi</label>
                                <div class="form-group">
                                    @forelse ($instansi as $item)
                                        <div class="form-check form-check-inline instansi-item"
                                            data-instansi-name="{{ strtolower($item->nama_instansi) }}"
                                            data-instansi-id="{{ $item->id }}">
                                            <input class="form-check-input instansi-checkbox" type="checkbox"
                                                id="instansi_{{ $item->id }}" name="instansi_id[]"
                                                value="{{ $item->id }}"
                                                {{ collect(old('instansi_id'))->contains($item->id) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="instansi_{{ $item->id }}">
                                                {{ $item->nama_instansi }}
                                            </label>
                                        </div>
                                    @empty
                                        <p class="text-muted mb-0">Tidak ada instansi</p>
                                    @endforelse
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="d-flex justify-content-end" style="gap: 10px;">
                                    <button type="submit" class="btn btn-primary px-4">Simpan</button>
                                    <a href="{{ route('user.index') }}" class="btn btn-secondary">Batal</a>
                                </div>
                            </div>
                    </form>
                </div>

            </div>
        </div>
    </section>
@endsection


@push('script')
    <script>
        $(document).ready(function() {

            // Fungsi untuk filter dan validasi instansi berdasarkan role
            function filterInstansiByRole() {
                var selectedRole = $('.role-radio:checked').data('role-name');
                var roleName = selectedRole ? selectedRole.toLowerCase() : '';

                // Reset semua instansi
                $('.instansi-item').show();
                $('.instansi-checkbox').prop('disabled', false).off('change'); // Hapus event listener lama
                $('#instansi-helper').text('');

                if (!roleName) {
                    // Jika belum pilih role, disable semua instansi
                    $('.instansi-checkbox').prop('disabled', true);
                    $('#instansi-helper').text('Silakan pilih role terlebih dahulu').addClass('text-warning');
                    return;
                }

                // ROLE: Admin Yayasan - Tampil semua instansi
                if (roleName === 'admin_yayasan' || roleName === 'admin yayasan') {
                    $('#instansi-helper').text('Pilih semua instansi yang diperlukan').removeClass('text-warning')
                        .addClass('text-info');
                }

                // ROLE: Operator Instansi - Maksimal 1 instansi, kecuali Puspela
                else if (roleName === 'operator_instansi' || roleName === 'operator instansi') {
                    // Sembunyikan Puspela
                    $('.instansi-item').each(function() {
                        var instansiName = $(this).data('instansi-name');
                        if (instansiName.includes('puspela')) {
                            $(this).hide();
                            $(this).find('.instansi-checkbox').prop('checked', false);
                        }
                    });

                    $('#instansi-helper').text('Maksimal memilih 1 instansi (kecuali Puspela)').addClass(
                        'text-warning');

                    // Validasi maksimal 1 checkbox
                    validateMaxInstansi(1);
                }

                // ROLE: Tenaga Pendidik - Semua kecuali Puspela (BISA BANYAK)
                else if (roleName === 'tenaga_pendidik' || roleName === 'tenaga pendidik') {
                    // Sembunyikan Puspela
                    $('.instansi-item').each(function() {
                        var instansiName = $(this).data('instansi-name');
                        if (instansiName.includes('puspela')) {
                            $(this).hide();
                            $(this).find('.instansi-checkbox').prop('checked', false);
                        }
                    });

                    $('#instansi-helper').text('Pilih instansi yang diperlukan (kecuali Puspela)').removeClass(
                            'text-warning')
                        .addClass('text-info');
                    // TIDAK ada batasan jumlah
                }

                // ROLE: Tenaga Kependidikan - Maksimal 1, kecuali Puspela
                else if (roleName === 'tenaga_kependidikan' || roleName === 'tenaga kependidikan') {
                    // Sembunyikan Puspela
                    $('.instansi-item').each(function() {
                        var instansiName = $(this).data('instansi-name');
                        if (instansiName.includes('puspela')) {
                            $(this).hide();
                            $(this).find('.instansi-checkbox').prop('checked', false);
                        }
                    });

                    $('#instansi-helper').text('Maksimal memilih 1 instansi (kecuali Puspela)').addClass(
                        'text-warning');

                    // Validasi maksimal 1 checkbox
                    validateMaxInstansi(1);
                } else {
                    $('#instansi-helper').text('Pilih instansi yang sesuai').removeClass('text-warning').addClass(
                        'text-info');
                }
            }

            // Fungsi validasi maksimal instansi
            function validateMaxInstansi(maxCount) {
                $('.instansi-checkbox:visible').on('change', function() {
                    var checkedCount = $('.instansi-checkbox:checked:visible').length;

                    if (checkedCount >= maxCount) {
                        // Disable checkbox yang belum dicentang
                        $('.instansi-checkbox:not(:checked):visible').prop('disabled', true);
                    } else {
                        // Enable semua checkbox yang visible
                        $('.instansi-checkbox:visible').prop('disabled', false);
                    }
                });
            }

            // Event ketika role dipilih
            $('.role-radio').on('change', function() {
                // Uncheck semua instansi
                $('.instansi-checkbox').prop('checked', false);

                // Filter instansi berdasarkan role
                filterInstansiByRole();
            });

            // Jalankan filter saat pertama kali load (jika ada old input)
            if ($('.role-radio:checked').length > 0) {
                filterInstansiByRole();
            } else {
                // Disable instansi jika belum pilih role
                $('.instansi-checkbox').prop('disabled', true);
            }

            // Validasi form submit
            $('form').on('submit', function(e) {
                var selectedRole = $('.role-radio:checked').data('role-name');
                var roleName = selectedRole ? selectedRole.toLowerCase() : '';
                var checkedInstansi = $('.instansi-checkbox:checked').length;

                // Validasi role harus dipilih
                if (!roleName) {
                    e.preventDefault();
                    alert('Silakan pilih role terlebih dahulu!');
                    return false;
                }

                // Validasi instansi harus dipilih
                if (checkedInstansi === 0) {
                    e.preventDefault();
                    alert('Silakan pilih minimal 1 instansi!');
                    return false;
                }

                // Validasi maksimal 1 instansi untuk Operator Instansi dan Tenaga Kependidikan
                if ((roleName === 'operator_instansi' || roleName === 'operator instansi' ||
                        roleName === 'tenaga_kependidikan' || roleName === 'tenaga kependidikan') &&
                    checkedInstansi > 1) {
                    e.preventDefault();
                    alert('Maksimal memilih 1 instansi untuk role ' + selectedRole + '!');
                    return false;
                }

                return true;
            });
        });
    </script>
@endpush
