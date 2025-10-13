@extends('layout.main')

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Edit Profil</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('viewProfile') }}">Profil</a></div>
                <div class="breadcrumb-item">Edit</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-8 col-lg-6 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h4>Edit Profil Pengguna</h4>
                        </div>

                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible show fade">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert">
                                            <span>&times;</span>
                                        </button>
                                        <strong>Terjadi Kesalahan!</strong>
                                        <ul class="mt-2 mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif

                            <form action="{{ route('editProfile',$user->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <!-- Foto Profil -->
                                <div class="form-group">
                                    <label>Foto Profil</label>
                                    <div class="text-center mb-3">
                                        <img id="preview-image"
                                             src="@if (!empty($user->foto)) {{ asset('foto/' . $user->foto) }}
                                                  @elseif (!empty($user->foto_presensi))
                                                      {{ asset('foto_presensi/' . $user->foto_presensi) }}
                                                  @else
                                                      {{ asset('foto/default.jpg') }}
                                                  @endif"
                                             alt="Foto Profil"
                                             class="rounded-circle"
                                             style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #6777ef;">
                                    </div>

                                    <div class="custom-file">
                                        <input type="file"
                                               class="custom-file-input @error('foto_profil') is-invalid @enderror"
                                               id="foto_profil"
                                               name="foto_profil"
                                               accept="image/*"
                                               onchange="previewImage(event)">
                                        <label class="custom-file-label" for="foto_profil">Pilih foto...</label>
                                        @error('foto_profil')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">Format: JPG, PNG, JPEG. Maksimal 2MB</small>
                                </div>

                                <!-- Username -->
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text"
                                           class="form-control @error('username') is-invalid @enderror"
                                           id="username"
                                           name="username"
                                           value="{{ old('username', $user->username) }}"
                                           placeholder="Masukkan username (minimal 5 karakter)">
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Nomor Telepon -->
                                <div class="form-group">
                                    <label for="telp">Nomor Telepon</label>
                                    <input type="text"
                                           class="form-control @error('telp') is-invalid @enderror"
                                           id="telp"
                                           name="telp"
                                           value="{{ old('telp', $user->telp) }}"
                                           placeholder="Contoh: 08123456789">
                                    @error('telp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Buttons -->
                                <div class="form-group">
                                    <a href="{{ route('viewProfile') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary float-right">
                                        <i class="fas fa-save"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Preview image before upload
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('preview-image');
            const label = input.nextElementSibling;

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                }

                reader.readAsDataURL(input.files[0]);
                label.textContent = input.files[0].name;
            }
        }

        // Update custom file input label
        document.querySelector('.custom-file-input').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'Pilih foto...';
            const label = e.target.nextElementSibling;
            label.textContent = fileName;
        });
    </script>
@endsection
