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
                                                 style="object-fit: cover; height: 120px; width: 120px;" class="rounded border mr-4">
                                        </div>
                                    @endif

                                    <div>
                                        <small class="d-block">Foto Baru:</small>
                                        <img id="preview_foto" src="#" alt="Preview Foto"
                                            class="rounded border d-none"  style="object-fit: cover; height: 120px; width: 120px;">
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
                                <label class="form-label">Role</label>
                                <div class="selectgroup selectgroup-pills">
                                    @forelse ($role as $item)
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="role_id[]" value="{{ $item->id }}"
                                                class="selectgroup-input"
                                                {{ in_array($item->id, old('role_id', $userRoles ?? [])) ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ $item->name }}</span>
                                        </label>
                                    @empty
                                        <p class="text-muted mb-0">Tidak ada role</p>
                                    @endforelse
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Instansi</label>
                                <div class="selectgroup selectgroup-pills">
                                    @forelse ($instansi as $item)
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="instansi_id[]" value="{{ $item->id }}"
                                                class="selectgroup-input"
                                                {{ in_array($item->id, old('instansi_id', $userInstansi ?? [])) ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ $item->nama_instansi }}</span>
                                        </label>
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
@endpush
