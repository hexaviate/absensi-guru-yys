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
                                    <li>Peran hanya bisa: <strong>tenaga_pendidik</strong> atau <strong>tenaga_kependidikan</strong></li>
                                    <li>Semua kolom wajib diisi sesuai ketentuan</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6 class="alert-heading"><i class="fas fa-times-circle"></i> <strong>Error Validasi</strong></h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6 class="alert-heading"><i class="fas fa-times-circle"></i> <strong>{{ session('error') }}</strong></h6>
                            @if (session('errors'))
                                <ul class="mb-0">
                                    @foreach (session('errors') as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            @endif
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> <strong>{{ session('success') }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- FORM IMPORT -->
                    <form action="{{ route('operator.instansi.users.import') }}" method="POST" enctype="multipart/form-data">
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
                                            <i class="fas fa-check text-success"></i> peran
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end" style="gap: 10px;">
                            <a href="{{ route('operator.instansi.users.download-template') }}" class="btn btn-success px-4">
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
    </section>
@endsection
