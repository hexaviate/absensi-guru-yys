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
                    <h5 class="mb-0">Edit User</h5>
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
                                <label for="password" class="form-label">Password (kosongkan jika tidak diganti)</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Masukkan password">
                            </div>
                        </div>

                        <!-- foto_presensi & jarak_tempuh -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="foto_presensi" class="form-label">Foto Presensi</label>
                                <input type="file" class="form-control" id="foto_presensi" name="foto_presensi">

                                <div class="d-flex align-items-start mt-3 gap-3 ">
                                    @if ($user->foto_presensi)
                                        <div>
                                            <small class="d-block">Foto Sebelumnya:</small>
                                            <img src="{{ asset('foto_presensi/' . $user->foto_presensi) }}" alt="Foto Lama"
                                                style="object-fit: cover; height: 120px; width: 120px;"
                                                class="rounded border mr-4">
                                        </div>
                                    @endif

                                    <div>
                                        <small class="d-block">Foto Baru:</small>
                                        <img id="preview_foto" src="#" alt="Preview Foto"
                                            class="rounded border d-none"
                                            style="object-fit: cover; height: 120px; width: 120px;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="jarak_tempuh" class="form-label">Jarak Tempuh (km)</label>
                                <input type="number" class="form-control" id="jarak_tempuh" name="jarak_tempuh"
                                    placeholder="Masukkan jarak tempuh"
                                    value="{{ old('jarak_tempuh', $user->jarak_tempuh) }}" step="0.01">
                            </div>
                        </div>

                        <!-- role & instansi -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Peran <span class="text-danger">*</span></label>
                                <div class="selectgroup selectgroup-pills">
                                    @forelse ($role as $item)
                                        <label class="selectgroup-item">
                                            <input type="radio" name="role_id" value="{{ $item->id }}"
                                                data-role-name="{{ $item->name }}" class="selectgroup-input role-radio"
                                                {{ old('role_id', $user->roles->first()->id ?? '') == $item->id ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ $item->name }}</span>
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
                                                {{ in_array($item->id, old('instansi_id', $user->instansi->pluck('id')->toArray())) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="instansi_{{ $item->id }}">
                                                {{ $item->nama_instansi }}
                                            </label>
                                        </div>
                                    @empty
                                        <p class="text-muted mb-0">Tidak ada instansi</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-4">Update</button>
                            <a href="{{ route('user.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </section>
@endsection

@push('script')
    <script>
        document.getElementById('foto_presensi').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('preview_foto');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            } else {
                preview.src = '#';
                preview.classList.add('d-none');
            }
        });
    </script>


    <script>
        // Preview foto presensi
        document.getElementById('foto_presensi').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('preview_foto');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            } else {
                preview.src = '#';
                preview.classList.add('d-none');
            }
        });

        // Validasi instansi berdasarkan role
        $(document).ready(function() {

            // Fungsi untuk filter dan validasi instansi berdasarkan role
            function filterInstansiByRole() {
                var selectedRole = $('.role-radio:checked').data('role-name');
                var roleName = selectedRole ? selectedRole.toLowerCase() : '';

                // Simpan instansi yang sudah dicentang (untuk preserve data existing)
                var previouslyChecked = [];
                $('.instansi-checkbox:checked').each(function() {
                    previouslyChecked.push($(this).val());
                });

                // Reset display dan event listener
                $('.instansi-item').show();
                $('.instansi-checkbox').prop('disabled', false).off('change.maxValidation');

                if (!roleName) {
                    // Jika belum pilih role, disable semua instansi
                    $('.instansi-checkbox').prop('disabled', true);
                    return;
                }

                // ROLE: Admin Yayasan - Tampil semua instansi
                if (roleName === 'admin_yayasan' || roleName === 'admin yayasan') {
                    // Tampilkan semua, tidak ada batasan
                    restoreChecked(previouslyChecked);
                }

                // ROLE: Operator Instansi - Maksimal 1 instansi, kecuali Puspela
                else if (roleName === 'operator_instansi' || roleName === 'operator instansi') {
                    // Sembunyikan Puspela
                    hidePuspela();

                    // Restore checked yang valid (maksimal 1)
                    restoreChecked(previouslyChecked, 1);

                    // Validasi maksimal 1 checkbox
                    validateMaxInstansi(1);
                }

                // ROLE: Tenaga Pendidik - Semua kecuali Puspela (BISA BANYAK)
                else if (roleName === 'tenaga_pendidik' || roleName === 'tenaga pendidik') {
                    // Sembunyikan Puspela
                    hidePuspela();

                    // Restore semua checked yang valid (tidak ada batasan)
                    restoreChecked(previouslyChecked);
                }

                // ROLE: Tenaga Kependidikan - Maksimal 1, kecuali Puspela
                else if (roleName === 'tenaga_kependidikan' || roleName === 'tenaga kependidikan') {
                    // Sembunyikan Puspela
                    hidePuspela();

                    // Restore checked yang valid (maksimal 1)
                    restoreChecked(previouslyChecked, 1);

                    // Validasi maksimal 1 checkbox
                    validateMaxInstansi(1);
                }
            }

            // Fungsi helper: sembunyikan Puspela
            function hidePuspela() {
                $('.instansi-item').each(function() {
                    var instansiName = $(this).data('instansi-name');
                    if (instansiName.includes('puspela')) {
                        $(this).hide();
                        $(this).find('.instansi-checkbox').prop('checked', false);
                    }
                });
            }

            // Fungsi helper: restore checkbox yang masih valid
            function restoreChecked(checkedIds, maxCount = null) {
                // Uncheck semua dulu
                $('.instansi-checkbox').prop('checked', false);

                var count = 0;
                checkedIds.forEach(function(id) {
                    var checkbox = $('.instansi-checkbox[value="' + id + '"]');
                    // Hanya restore yang masih visible
                    if (checkbox.is(':visible')) {
                        if (maxCount === null || count < maxCount) {
                            checkbox.prop('checked', true);
                            count++;
                        }
                    }
                });
            }

            // Fungsi validasi maksimal instansi
            function validateMaxInstansi(maxCount) {
                // Event listener dengan namespace untuk avoid multiple binding
                $('.instansi-checkbox:visible').on('change.maxValidation', function() {
                    var checkedCount = $('.instansi-checkbox:checked:visible').length;

                    if (checkedCount >= maxCount) {
                        // Disable checkbox yang belum dicentang
                        $('.instansi-checkbox:not(:checked):visible').prop('disabled', true);
                    } else {
                        // Enable semua checkbox yang visible
                        $('.instansi-checkbox:visible').prop('disabled', false);
                    }
                });

                // Trigger validation pertama kali
                var checkedCount = $('.instansi-checkbox:checked:visible').length;
                if (checkedCount >= maxCount) {
                    $('.instansi-checkbox:not(:checked):visible').prop('disabled', true);
                }
            }

            // Event ketika role dipilih/diubah
            $('.role-radio').on('change', function() {
                // PENTING: TIDAK uncheck semua instansi
                // filterInstansiByRole() akan handle preserve data yang masih valid
                filterInstansiByRole();
            });

            // Jalankan filter saat pertama kali load (untuk data existing)
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
                var checkedInstansi = $('.instansi-checkbox:checked:visible').length;

                // Validasi role harus dipilih
                if (!roleName) {
                    e.preventDefault();
                    alert('Silakan pilih peran terlebih dahulu!');
                    return false;
                }

                // Validasi instansi harus dipilih (kecuali admin yayasan yang bisa kosong)
                if (checkedInstansi === 0 && roleName !== 'admin_yayasan' && roleName !== 'admin yayasan') {
                    e.preventDefault();
                    alert('Silakan pilih minimal 1 instansi!');
                    return false;
                }

                // Validasi maksimal 1 instansi untuk Operator Instansi dan Tenaga Kependidikan
                if ((roleName === 'operator_instansi' || roleName === 'operator instansi' ||
                        roleName === 'tenaga_kependidikan' || roleName === 'tenaga kependidikan') &&
                    checkedInstansi > 1) {
                    e.preventDefault();
                    alert('Maksimal memilih 1 instansi untuk peran ' + selectedRole + '!');
                    return false;
                }

                return true;
            });
        });
    </script>
@endpush
