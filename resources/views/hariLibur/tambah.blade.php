@extends('layout.main')
@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Tambah Libur</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Hari Libur</a></div>
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
                    <h5 class="mb-0">Tambah Hari Libur</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('hariLibur.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            {{-- Tahun Pelajaran --}}
                           <div class="col-md-6 mb-3">
                                <label for="tapel" class="form-label">Tahun Pelajaran</label>
                                <input type="text" class="form-control"
                                    value="{{ $tapel ? $tapel->kode : 'Tidak ada tapel aktif' }}" readonly>
                                <input type="hidden" name="tapel_id" value="{{ $tapel ? $tapel->id : '' }}">
                            </div>

                            {{-- Instansi --}}
                             <div class="col-md-6 mb-3">
                                <label class="form-label">Instansi</label>
                                <div class="selectgroup selectgroup-pills">
                                    @forelse ($instansi as $item)
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="instansi_id" value="{{ $item->id }}"
                                                class="selectgroup-input"
                                                {{ old('instansi_id') == $item->id ? 'checked' : '' }}
                                                {{ count(value: $instansi) == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ $item->nama_instansi }}</span>
                                        </label>
                                    @empty
                                        <p class="text-muted mb-0">Tidak ada instansi</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            {{-- Keterangan --}}
                            <div class="col-md-6 mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <input type="text" class="form-control" id="keterangan" name="keterangan"
                                    placeholder="Contoh: Libur Nasional" value="{{ old('keterangan') }}">
                            </div>

                            {{-- Tanggal Libur --}}
                            <div class="col-md-6 mb-3">
                                <label for="tanggal" class="form-label">Tanggal Libur</label>
                                <input type="date" class="form-control" id="tanggal" name="tanggal"
                                    value="{{ old('tanggal') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="waktu" class="form-label">Waktu</label>
                                <input type="time" id="waktu" name="waktu" class="form-control">
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
