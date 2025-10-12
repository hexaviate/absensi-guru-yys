
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
                    <!-- Edit Profile Card -->
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-gradient-primary">
                            <h4 class="text-white mb-0">
                                <i class="fas fa-user-edit mr-2"></i>Edit Profil Pengguna
                            </h4>
                        </div>

                        <div class="card-body p-4">
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong><i class="fas fa-exclamation-triangle mr-2"></i>Terjadi Kesalahan!</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <form action="{{ route('editProfile') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <!-- Foto Profil -->
                                <div class="form-group">
                                    <label class="form-label font-weight-bold">
                                        <i class="fas fa-camera mr-1"></i>Foto Profil
                                    </label>

                                    <div class="text-center mb-3">
                                        <div class="profile-image-wrapper">
                                            <img id="preview-image"
                                                 src="@if (!empty($user->foto)) {{ asset('foto/' . $user->foto) }}
                                                      @elseif (!empty($user->foto_presensi))
                                                          {{ asset('foto_presensi/' . $user->foto_presensi) }}
                                                      @else
                                                          {{ asset('foto/default.jpg') }}
                                                      @endif"
                                                 alt="Foto Profil"
                                                 class="profile-preview">
                                        </div>
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
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>Format: JPG, PNG, JPEG. Maksimal 2MB
                                    </small>
                                </div>

                                <!-- Username -->
                                <div class="form-group">
                                    <label for="username" class="form-label font-weight-bold">
                                        <i class="fas fa-user mr-1"></i>Username
                                    </label>
                                    <input type="text"
                                           class="form-control @error('username') is-invalid @enderror"
                                           id="username"
                                           name="username"
                                           value="{{ old('username', $user->username) }}"
                                           placeholder="Masukkan username (minimal 5 karakter)">
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>Username minimal 5 karakter
                                    </small>
                                </div>

                                <!-- Nomor Telepon -->
                                <div class="form-group">
                                    <label for="telp" class="form-label font-weight-bold">
                                        <i class="fas fa-phone mr-1"></i>Nomor Telepon
                                    </label>
                                    <input type="text"
                                           class="form-control @error('telp') is-invalid @enderror"
                                           id="telp"
                                           name="telp"
                                           value="{{ old('telp', $user->telp) }}"
                                           placeholder="Contoh: 08123456789">
                                    @error('telp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>Masukkan nomor telepon yang valid
                                    </small>
                                </div>

                                <!-- Buttons -->
                                <div class="form-group mb-0 mt-4">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('viewProfile') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left mr-1"></i>Kembali
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save mr-1"></i>Simpan Perubahan
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Card Styling */
        .card {
            border-radius: 15px;
            overflow: hidden;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #6777ef 0%, #4d63d5 100%);
            padding: 20px;
        }

        /* Profile Image Preview */
        .profile-image-wrapper {
            position: relative;
            display: inline-block;
            margin-bottom: 15px;
        }

        .profile-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #6777ef;
            box-shadow: 0 4px 15px rgba(103, 119, 239, 0.3);
            transition: all 0.3s ease;
        }

        .profile-preview:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(103, 119, 239, 0.4);
        }

        /* Form Styling */
        .form-label {
            color: #2c3e50;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .form-control {
            border-radius: 10px;
            border: 2px solid #e3e8ef;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #6777ef;
            box-shadow: 0 0 0 0.2rem rgba(103, 119, 239, 0.15);
        }

        .custom-file-label {
            border-radius: 10px;
            border: 2px solid #e3e8ef;
            padding: 12px 15px;
            height: auto;
        }

        .custom-file-input:focus ~ .custom-file-label {
            border-color: #6777ef;
            box-shadow: 0 0 0 0.2rem rgba(103, 119, 239, 0.15);
        }

        /* Button Styling */
        .btn {
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #6777ef 0%, #4d63d5 100%);
            box-shadow: 0 4px 12px rgba(103, 119, 239, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(103, 119, 239, 0.4);
            background: linear-gradient(135deg, #5668e8 0%, #3d54c5 100%);
        }

        .btn-secondary {
            background: #95a5a6;
            box-shadow: 0 4px 12px rgba(149, 165, 166, 0.3);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(149, 165, 166, 0.4);
            background: #7f8c8d;
        }

        /* Alert Styling */
        .alert {
            border-radius: 10px;
            border: none;
        }

        .alert-danger {
            background: #fff5f5;
            color: #e74c3c;
            border-left: 4px solid #e74c3c;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .card-body {
                padding: 20px !important;
            }

            .profile-preview {
                width: 120px;
                height: 120px;
            }

            .btn {
                padding: 10px 20px;
                font-size: 13px;
            }

            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 10px;
            }

            .d-flex.justify-content-between .btn {
                width: 100%;
            }
        }
    </style>

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
