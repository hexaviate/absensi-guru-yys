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
                                        $RoleYangAda = ['tenaga_pendidik', 'tenaga_kependidikan'];
                                    @endphp

                                    @forelse ($role as $item)
                                        @if (!$adalahOperator || in_array($item->name, $RoleYangAda))
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
                                    value="{{ old('nomor_induk_yayasan') }}" step="0.01">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="instansi">Instansi</label>
                                <div class="form-group">
                                    @php
                                        $user = auth()->user();
                                        $isOperator = $user->hasRole('operator_instansi');
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
                                            {{-- tampilkan semua instansi untuk non-operator --}}
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
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="d-flex justify-content-end" style="gap: 10px;">
                                    <button type="submit" class="btn btn-primary px-4">Simpan</button>
                                    <a href="{{ route('user.index') }}" class="btn btn-secondary">Batal</a>
                                </div>
                            </div>
                    </form>
                </div>
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"> Import Data Users</h5>
                    </div>
                    <div class="card-body">
                        <!-- INFO BOX -->
                        <div class="alert alert-info mb-3">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle fa-2x mr-2"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="alert-heading"><strong>Panduan Import:</strong></h6>
                                    <ol class="mb-0">
                                        <li>Klik tombol <strong>"Download Template"</strong> untuk mendapatkan format Excel
                                            yang benar</li>
                                        <li>Isi data user sesuai format yang ada di template</li>
                                        <li>Upload file yang sudah diisi dan klik tombol <strong>"Import"</strong></li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <!-- WARNING BOX -->
                        <div class="alert alert-warning mb-4">
                            <div class="d-flex">
                                <div class="flex-shrink-0 mr-2">
                                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="alert-heading"><strong>Perhatian Penting:</strong></h6>
                                    <ul class="mb-0">
                                        <li><strong>Jika ada 1 baris saja yang error, maka SEMUA data tidak akan
                                                diimport</strong></li>
                                        <li>Username tidak boleh duplikat dengan user yang sudah ada</li>
                                        <li>Semua kolom wajib diisi sesuai ketentuan</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- FORM IMPORT -->
                        <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="file" class="form-label">
                                        <i class="fas fa-file-excel"></i> Pilih File Excel
                                    </label>
                                    <input type="file" class="form-control @error('file') is-invalid @enderror"
                                        id="file" name="file" accept=".xlsx,.xls,.csv" required>
                                    @error('file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">
                                        <i class="fas fa-paperclip"></i> Format yang didukung: .xlsx, .xls, .csv (Maksimal
                                        2MB)
                                    </small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Format Kolom Excel</label>
                                    <div class="border rounded p-2" style="background: #f8f9fa; font-size: 12px;">
                                        <div class="row">
                                            <div class="col-6">
                                                <i class="fas fa-check text-success"></i> nomor_induk_yayasan<br>
                                                <i class="fas fa-check text-success"></i> name<br>
                                                <i class="fas fa-check text-success"></i> telp<br>
                                                <i class="fas fa-check text-success"></i> username
                                            </div>
                                            <div class="col-6">
                                                <i class="fas fa-check text-success"></i> password<br>
                                                <i class="fas fa-check text-success"></i> jarak_tempuh<br>
                                                <i class="fas fa-check text-success"></i> nama_instansi<br>
                                                <i class="fas fa-check text-success"></i> peran
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end" style="gap: 10px;">
                                <a href="{{ route('users.template') }}" class="btn btn-success px-4">
                                    <i class="fa-solid fa-download mr-2"></i>Download Template
                                </a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-upload mr-2"></i>Import Sekarang
                                </button>
                                <a href="{{ route('user.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times mr-2"></i>Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </section>
@endsection


{{-- @push('script')
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
@endpush --}}
