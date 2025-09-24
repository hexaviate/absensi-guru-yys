@extends('layout.main')
@section('main')
    <section class="section">
        <div class="section-header" style="">
            <h1>Tambah User</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">User</a></div>
                <div class="breadcrumb-item">Dashboard</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card shadow-sm" style="">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Tambah User</h5>
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
                                    placeholder="Masukkan nama">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="telp" class="form-label">No Telepon</label>
                                <input type="text" class="form-control" id="telp" name="telp"
                                    placeholder="08xxxxxxxxxx">
                            </div>
                        </div>

                        <!-- username & password -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" id="username"
                                    placeholder="Masukkan username">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Masukkan password">
                            </div>
                        </div>

                        <!-- uid & foto -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="foto_presensi" class="form-label">Foto Presensi</label>
                                <input type="file" class="form-control" id="foto_presensi" name="foto_presensi">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="foto_profil" class="form-label">Foto</label>
                                <input type="file" class="form-control" id="foto_profil" name="foto_profil">
                            </div>
                        </div>

                        <!-- role & instansi -->
                        <!-- role -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Role</label>
                                <div class="selectgroup selectgroup-pills">
                                    @forelse ($role as $item)
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="role_id[]" value="{{ $item->id }}"
                                                class="selectgroup-input"
                                                {{ collect(old('role_id'))->contains($item->id) ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ $item->name }}</span>
                                        </label>
                                    @empty
                                        <p class="text-muted mb-0">Tidak ada role</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- instansi -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Instansi</label>
                                <div class="selectgroup selectgroup-pills">
                                    @forelse ($instansi as $item)
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="instansi_id[]" value="{{ $item->id }}"
                                                class="selectgroup-input"
                                                {{ collect(old('instansi_id'))->contains($item->id) ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ $item->nama_instansi }}</span>
                                        </label>
                                    @empty
                                        <p class="text-muted mb-0">Tidak ada instansi</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-4">Simpan</button>
                            <button class="btn btn-danger" type="reset">Reset</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </section>
@endsection

