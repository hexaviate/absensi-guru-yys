@extends('layout.main')
@section('main')
    <section class="section">
        <div class="section-header" style="">
            <h1>Tambah Instansi</h1>
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
            <div class="card shadow-sm" style="">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Tambah Instansi</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('instansi.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama Instansi</label>
                                <input type="text" class="form-control" id="name" name="nama_instansi"
                                    placeholder="Masukkan Nama Instansi">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="kepalaInstansi" class="form-label">Kepala Instansi</label>
                                <input type="text" class="form-control" id="kepalaInstansi" name="kepala_instansi"
                                    placeholder="Masukkan Kepala Instansi">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="alamatInstansi" class="form-label">Alamat Instansi</label>
                                <input type="text" class="form-control" id="alamatInstansi" name="alamat_instansi"
                                    placeholder="Masukkan Nama Instansi">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="TelpInstansi" class="form-label">Telp Instansi</label>
                                <input type="text" class="form-control" id="TelpInstansi" name="telp_instansi"
                                    placeholder="Masukkan Kepala Instansi">
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="latitude" class="form-label">Latitude Instansi</label>
                                <input type="text" class="form-control" id="latitude" name="latitude"
                                    placeholder="Masukkan Latitude">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="longitude" class="form-label">Longtitude Instansi</label>
                                <input type="text" class="form-control" id="longitude" name="longitude"
                                    placeholder="Masukkan Longtitude">
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

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
@endpush

@push('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endpush
