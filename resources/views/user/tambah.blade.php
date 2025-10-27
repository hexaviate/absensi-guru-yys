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
                                    @php
                                        $user = auth()->user();
                                        $adalahOperator = $user->hasRole('operator_instansi');
                                        $adalahAdminYayasan = $user->hasRole('admin_yayasan');
                                        $RoleYangAda = ['tenaga_pendidik', 'tenaga_kependidikan'];
                                    @endphp

                                    @forelse ($role as $item)
                                        @if ($adalahAdminYayasan)
                                            {{-- Admin Yayasan bisa lihat semua role --}}
                                            <label class="selectgroup-item">
                                                <input type="radio" name="role_id[]" value="{{ $item->id }}"
                                                    data-role-name="{{ $item->name }}"
                                                    class="selectgroup-input role-radio"
                                                    {{ old('role_id') == $item->id ? 'checked' : '' }}>
                                                <span
                                                    class="selectgroup-button">{{ Str::of($item->name)->replace('_', ' ')->title() }}</span>
                                            </label>
                                        @elseif ($adalahOperator && in_array($item->name, $RoleYangAda))
                                            {{-- Operator hanya lihat 2 role: tenaga_pendidik dan tenaga_kependidikan --}}
                                            <label class="selectgroup-item">
                                                <input type="radio" name="role_id[]" value="{{ $item->id }}"
                                                    data-role-name="{{ $item->name }}"
                                                    class="selectgroup-input role-radio"
                                                    {{ old('role_id') == $item->id ? 'checked' : '' }}>
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
                                    value="{{ old('nomor_induk_yayasan') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="instansi">Instansi</label>
                                <div class="form-group" id="instansi-container">
                                    @php
                                        $user = auth()->user();
                                        $isOperator = $user->hasRole('operator_instansi');
                                        $isAdminYayasan = $user->hasRole('admin_yayasan');
                                    @endphp

                                    @forelse ($instansi as $item)
                                        @if ($isOperator)
                                            {{-- hanya tampilkan instansi milik operator --}}
                                            @if ($user->instansi->contains('id', $item->id))
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
                                                    {{ collect(old('instansi_id'))->contains($item->id) ? 'checked' : '' }}>
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
                                    <button type="submit" class="btn btn-primary px-4">Simpan</button>
                                    <a href="{{ route('user.index') }}" class="btn btn-secondary">Batal</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
    </section>
@endsection


@push('script')
    <script>
        $(document).ready(function() {
            // Data semua instansi untuk digunakan saat role admin_yayasan dipilih
            var allInstansiIds = [];
            $('.instansi-checkbox').each(function() {
                allInstansiIds.push($(this).val());
            });

            // Cek user yang login dari data attribute (tambahkan di blade)
            var loginUserRole = $('body').data('login-user-role') || 'admin_yayasan';

            // Fungsi untuk cek apakah user adalah admin_yayasan (check dari radio yang ada)
            function isAdminYayasanRole() {
                var selectedRole = $('.role-radio:checked').data('role-name');
                return selectedRole && selectedRole.toLowerCase() === 'admin_yayasan';
            }

            // Fungsi untuk cek apakah user adalah operator_instansi (check dari radio yang ada)
            function isOperatorInstansiRole() {
                var selectedRole = $('.role-radio:checked').data('role-name');
                return selectedRole && selectedRole.toLowerCase() === 'operator_instansi';
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
                    // Uncheck semua dulu
                    $('.instansi-checkbox').prop('checked', false);

                    // Check semua yang visible
                    $('.instansi-checkbox:visible').prop('checked', true);

                    // Disable semua (tidak bisa diubah)
                    $('.instansi-checkbox').prop('disabled', true);

                    $('#instansi-helper').text('Semua instansi dipilih otomatis untuk Admin Yayasan')
                        .removeClass('text-warning').addClass('text-info');
                }

                // ROLE: Operator Instansi - MAKSIMAL 1 INSTANSI
                else if (roleName === 'operator_instansi') {
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

                // ROLE: Tenaga Pendidik - BISA BANYAK INSTANSI
                else if (roleName === 'tenaga_pendidik') {
                    $('.instansi-item').each(function() {
                        var instansiName = $(this).data('instansi-name');
                        if (instansiName.includes('puspela')) {
                            $(this).hide();
                            $(this).find('.instansi-checkbox').prop('checked', false);
                        }
                    });

                    @if ($user->hasRole('admin_yayasan'))
                        $('#instansi-helper').text(
                                'Pilih instansi yang diperlukan - Bisa lebih dari 1')
                            .removeClass('text-warning').addClass('text-info');
                    @else
                        $('#instansi-helper').text(
                                'Pilih instansi')
                            .removeClass('text-warning').addClass('text-info');
                    @endif

                    // TIDAK ada event listener untuk batasan jumlah
                }

                // ROLE: Tenaga Kependidikan - MAKSIMAL 1 INSTANSI
                else if (roleName === 'tenaga_kependidikan') {
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
                // Uncheck semua instansi (KECUALI untuk admin_yayasan yang akan di-handle di filterInstansiByRole)
                if (!isAdminYayasanRole()) {
                    $('.instansi-checkbox').prop('checked', false);
                }

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
