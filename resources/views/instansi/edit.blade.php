@extends('layout.main')
@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Edit Instansi</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Instansi</a></div>
                <div class="breadcrumb-item">Dashboard</div>
            </div>
        </div>

        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>

        <div class="section-body">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Form Edit Instansi</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('instansi.update', $instansi->id) }}" method="POST">
                        @csrf
                        @method('put')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama Instansi</label>
                                <input type="text" class="form-control" id="name" name="nama_instansi"
                                    value="{{ old('nama_instansi', $instansi->nama_instansi) }}"
                                    placeholder="Masukkan Nama Instansi">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="kepalaInstansi" class="form-label">Kepala Instansi</label>
                                <input type="text" class="form-control" id="kepalaInstansi" name="kepala_instansi"
                                    value="{{ old('kepala_instansi', $instansi->kepala_instansi) }}"
                                    placeholder="Masukkan Kepala Instansi">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="alamatInstansi" class="form-label">Alamat Instansi</label>
                                <input type="text" class="form-control" id="alamatInstansi" name="alamat_instansi"
                                    value="{{ old('alamat_instansi', $instansi->alamat_instansi) }}"
                                    placeholder="Masukkan Alamat Instansi">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="TelpInstansi" class="form-label">Telp Instansi</label>
                                <input type="text" class="form-control" id="TelpInstansi" name="telp_instansi"
                                    value="{{ old('telp_instansi', $instansi->telp_instansi) }}"
                                    placeholder="Masukkan Telp Instansi">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="selectgroup selectgroup-pills">
                                        <div class="form-group">
                                            <label>Pilih Admin</label>
                                            <select class="form-control form-control-lg" name="user_id">
                                                @forelse ($user as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ old('user_id', $instansi->user_id) == $item->id ? 'selected' : '' }}>
                                                        {{ $item->name }}
                                                    </option>
                                                @empty
                                                    <p class="text-muted mb-0">Tidak ada user</p>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end" style="gap: 10px;">
                            <button type="submit" class="btn btn-primary px-4">Update</button>
                            <a href="{{ route('instansi.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
@endpush

@push('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endpush
