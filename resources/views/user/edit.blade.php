<!-- BAGIAN BLADE (HTML) UNTUK EDIT USER -->

@extends('layout.main')
@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Edit User</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">User</a></div>
                <div class="breadcrumb-item">Dashboard</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Form Edit User</h5>
                </div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <div class="card-body">
                    <form enctype="multipart/form-data" action="{{ route('user.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- nama & telp -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Masukkan nama" value="{{ old('name', $user->name) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="telp" class="form-label">No Telepon</label>
                                <input type="text" class="form-control" id="telp" name="telp"
                                    placeholder="08xxxxxxxxxx" value="{{ old('telp', $user->telp) }}">
                            </div>
                        </div>

                        <!-- username & password -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" id="username"
                                    placeholder="Masukkan username" value="{{ old('username', $user->username) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password (Kosongkan jika tidak diubah)</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Masukkan password baru">
                            </div>
                        </div>

                        <!-- foto_presensi & jarak_tempuh -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="foto_presensi" class="form-label">Foto Presensi</label>
                                @if ($user->foto_presensi)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $user->foto_presensi) }}" alt="Foto Presensi"
                                            style="max-width: 100px; max-height: 100px;">
                                    </div>
                                @endif
                                <input type="file" class="form-control" id="foto_presensi" name="foto_presensi">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="jarak_tempuh" class="form-label">Jarak Tempuh (km)</label>
                                <input type="number" class="form-control" id="jarak_tempuh" name="jarak_tempuh"
                                    placeholder="Masukkan jarak tempuh" value="{{ old('jarak_tempuh', $user->jarak_tempuh) }}"
                                    step="0.01">
                            </div>
                        </div>

                        <!-- role & instansi -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Peran <span class="text-danger">*</span></label>
                                <div class="selectgroup selectgroup-pills">
                                    @php
                                        $authUser = auth()->user();
                                        $adalahOperator = $authUser->hasRole('operator_instansi');
                                        $adalahAdminYayasan = $authUser->hasRole('admin_yayasan');
                                        $RoleYangAda = ['tenaga_pendidik', 'tenaga_kependidikan'];
                                        $userCurrentRole = $user->getRoleNames()->first();
                                    @endphp

                                    @forelse ($role as $item)
                                        @if ($adalahAdminYayasan)
                                            {{-- Admin Yayasan bisa lihat semua role --}}
                                            <label class="selectgroup-item">
                                                <input type="radio" name="role_id[]" value="{{ $item->id }}"
                                                    data-role-name="{{ $item->name }}"
                                                    class="selectgroup-input role-radio"
                                                    {{ $userCurrentRole == $item->name ? 'checked' : '' }}>
                                                <span
                                                    class="selectgroup-button">{{ Str::of($item->name)->replace('_', ' ')->title() }}</span>
                                            </label>
                                        @elseif ($adalahOperator && in_array($item->name, $RoleYangAda))
                                            {{-- Operator hanya lihat 2 role: tenaga_pendidik dan tenaga_kependidikan --}}
                                            <label class="selectgroup-item">
                                                <input type="radio" name="role_id[]" value="{{ $item->id }}"
                                                    data-role-name="{{ $item->name }}"
                                                    class="selectgroup-input role-radio"
                                                    {{ $userCurrentRole == $item->name ? 'checked' : '' }}>
                                                <span
                                                    class="selectgroup-button">{{ Str::of($item->name)->replace('_', ' ')->title() }}</span>
                                            </label>
                                        @endif
                                    @empty
                                        <p class="text-muted mb-0">Tidak ada role</p>
                                    @endforelse
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="nomor_induk_yayasan" class="form-label">Nomor Induk</label>
                                <input type="text" class="form-control" id="nomor_induk_yayasan"
                                    name="nomor_induk_yayasan" placeholder="Masukkan Nomor Induk Yayasan"
                                    value="{{ old('nomor_induk_yayasan', $user->nomor_induk_yayasan) }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="instansi">Instansi</label>
                                <div class="form-group" id="instansi-container">
                                    @php
                                        $authUser = auth()->user();
                                        $isOperator = $authUser->hasRole('operator_instansi');
                                        $isAdminYayasan = $authUser->hasRole('admin_yayasan');
                                        $userInstansiIds = $user->instansi->pluck('id')->toArray();
                                    @endphp

                                    @forelse ($instansi as $item)
                                        @if ($isOperator)
                                            {{-- hanya tampilkan instansi milik operator --}}
                                            @if ($authUser->instansi->contains('id', $item->id))
                                                <div class="form-check form-check-inline instansi-item"
                                                    data-instansi-name="{{ strtolower($item->nama_instansi) }}"
                                                    data-instansi-id="{{ $item->id }}">
                                                    <input class="form-check-input instansi-checkbox" type="checkbox"
                                                        id="instansi_{{ $item->id }}" name="instansi_id[]"
                                                        value="{{ $item->id }}" checked disabled>
                                                    <label class="form-check-label text-muted"
                                                        for="instansi_{{ $item->id }}">
                                                        {{ $item->nama_instansi }}
                                                    </label>
                                                </div>
                                            @endif
                                        @else
                                            {{-- tampilkan semua instansi untuk admin yayasan --}}
                                            <div class="form-check form-check-inline instansi-item"
                                                data-instansi-name="{{ strtolower($item->nama_instansi) }}"
                                                data-instansi-id="{{ $item->id }}">
                                                <input class="form-check-input instansi-checkbox" type="checkbox"
                                                    id="instansi_{{ $item->id }}" name="instansi_id[]"
                                                    value="{{ $item->id }}"
                                                    {{ in_array($item->id, $userInstansiIds) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="instansi_{{ $item->id }}">
                                                    {{ $item->nama_instansi }}
                                                </label>
                                            </div>
                                        @endif
                                    @empty
                                        <p class="text-muted mb-0">Tidak ada instansi</p>
                                    @endforelse

                                </div>
                                <small id="instansi-helper" class="d-block mt-2"></small>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="d-flex justify-content-end" style="gap: 10px;">
                                    <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                                    <a href="{{ route('user.index') }}" class="btn btn-secondary">Batal</a>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
    </section>
@endsection

<!-- Tambahkan data attribute untuk cek user yang login di JavaScript -->
<script>
    $('body').attr('data-login-user-role', '{{ auth()->user()->getRoleNames()->first() ?? "admin_yayasan" }}');
</script>

@push('script')
    <script>
        $(document).ready(function() {
            // Cek user yang login dari data attribute
            var loginUserRole = $('body').data('login-user-role') || 'admin_yayasan';

            // Fungsi untuk cek apakah role yang dipilih adalah admin_yayasan
            function isAdminYayasanRole() {
                var selectedRole = $('.role-radio:checked').data('role-name');
                return selectedRole && selectedRole.toLowerCase() === 'admin_yayasan';
            }

            // Fungsi untuk filter dan validasi instansi berdasarkan role
            function filterInstansiByRole() {
                var selectedRole = $('.role-radio:checked').data('role-name');
                var roleName = selectedRole ? selectedRole.toLowerCase() : '';

                // Reset tampilan
                $('.instansi-item').show();
                $('.instansi-checkbox').prop('disabled', false).off('change');
                $('#instansi-helper').text('');

                if (!roleName) {
                    $('.instansi-checkbox').prop('disabled', true);
                    $('#instansi-helper').text('Silakan pilih role terlebih dahulu').addClass('text-warning');
                    return;
                }

                // ROLE: Admin Yayasan - AUTO SELECT SEMUA INSTANSI
                if (roleName === 'admin_yayasan') {
                    // Check semua yang visible
                    $('.instansi-checkbox:visible').prop('checked', true);

                    // Disable semua (tidak bisa diubah)
                    $('.instansi-checkbox').prop('disabled', true);

                    $('#instansi-helper').text('Semua instansi dipilih otomatis untuk Admin Yayasan')
                        .removeClass('text-warning').addClass('text-info');
                }

                // ROLE: Operator Instansi - MAKSIMAL 1 INSTANSI
                else if (roleName === 'operator_instansi') {
                    // Reset checked kecuali 1 yang pertama ter-check
                    var firstChecked = $('.instansi-checkbox:checked:visible').first();
                    $('.instansi-checkbox:visible').prop('checked', false);
                    if (firstChecked.length) {
                        firstChecked.prop('checked', true);
                    }

                    $('.instansi-item').each(function() {
                        var instansiName = $(this).data('instansi-name');
                        if (instansiName.includes('puspela')) {
                            $(this).hide();
                            $(this).find('.instansi-checkbox').prop('checked', false);
                        }
                    });

                    $('#instansi-helper').text('Maksimal memilih 1 instansi (kecuali Puspela)')
                        .addClass('text-warning');

                    validateMaxInstansi(1);
                }

                // ROLE: Tenaga Pendidik - BISA BANYAK INSTANSI (untuk admin) atau MAKSIMAL 1 (untuk operator)
                else if (roleName === 'tenaga_pendidik') {
                    // Sembunyikan Puspela
                    $('.instansi-item').each(function() {
                        var instansiName = $(this).data('instansi-name');
                        if (instansiName.includes('puspela')) {
                            $(this).hide();
                            $(this).find('.instansi-checkbox').prop('checked', false);
                        }
                    });

                    // Bedakan pesan berdasarkan user yang login
                    if (loginUserRole === 'admin_yayasan') {
                        $('#instansi-helper').text('Pilih instansi yang diperlukan (kecuali Puspela) - Bisa lebih dari 1')
                            .removeClass('text-warning').addClass('text-info');
                        // TIDAK ada event listener untuk batasan jumlah
                    } else if (loginUserRole === 'operator_instansi') {
                        // Reset checked kecuali 1 yang pertama ter-check
                        var firstChecked = $('.instansi-checkbox:checked:visible').first();
                        $('.instansi-checkbox:visible').prop('checked', false);
                        if (firstChecked.length) {
                            firstChecked.prop('checked', true);
                        }

                        $('#instansi-helper').text('Maksimal memilih 1 instansi (kecuali Puspela)')
                            .addClass('text-warning');
                        validateMaxInstansi(1);
                    }
                }

                // ROLE: Tenaga Kependidikan - MAKSIMAL 1 INSTANSI
                else if (roleName === 'tenaga_kependidikan') {
                    // Reset checked kecuali 1 yang pertama ter-check
                    var firstChecked = $('.instansi-checkbox:checked:visible').first();
                    $('.instansi-checkbox:visible').prop('checked', false);
                    if (firstChecked.length) {
                        firstChecked.prop('checked', true);
                    }

                    $('.instansi-item').each(function() {
                        var instansiName = $(this).data('instansi-name');
                        if (instansiName.includes('puspela')) {
                            $(this).hide();
                            $(this).find('.instansi-checkbox').prop('checked', false);
                        }
                    });

                    $('#instansi-helper').text('Maksimal memilih 1 instansi (kecuali Puspela)')
                        .addClass('text-warning');

                    validateMaxInstansi(1);
                }
            }

            // Fungsi validasi maksimal instansi dengan auto-uncheck
            function validateMaxInstansi(maxCount) {
                $('.instansi-checkbox:visible').on('change', function() {
                    var checkedCount = $('.instansi-checkbox:checked:visible').length;

                    if (checkedCount > maxCount) {
                        // Uncheck checkbox yang baru saja diklik jika sudah melampaui maksimal
                        $(this).prop('checked', false);
                        alert('Maksimal memilih ' + maxCount + ' instansi!');
                    } else if (checkedCount >= maxCount) {
                        $('.instansi-checkbox:not(:checked):visible').prop('disabled', true);
                    } else {
                        $('.instansi-checkbox:visible').prop('disabled', false);
                    }
                });
            }

            // Event ketika role dipilih
            $('.role-radio').on('change', function() {
                // Filter instansi berdasarkan role
                filterInstansiByRole();
            });

            // Jalankan filter saat pertama kali load
            if ($('.role-radio:checked').length > 0) {
                filterInstansiByRole();
            } else {
                $('.instansi-checkbox').prop('disabled', true);
            }

            // Validasi form submit
            $('form').on('submit', function(e) {
                var selectedRole = $('.role-radio:checked').data('role-name');
                var roleName = selectedRole ? selectedRole.toLowerCase() : '';
                var checkedInstansi = $('.instansi-checkbox:checked').length;

                if (!roleName) {
                    e.preventDefault();
                    alert('Silakan pilih role terlebih dahulu!');
                    return false;
                }

                // Admin Yayasan otomatis select semua, jadi tidak perlu validasi
                if (roleName === 'admin_yayasan') {
                    return true;
                }

                if (checkedInstansi === 0) {
                    e.preventDefault();
                    alert('Silakan pilih minimal 1 instansi!');
                    return false;
                }

                return true;
            });
        });
    </script>
@endpush
