@extends('layout.main')
@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Edit Libur</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Libur</a></div>
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
                    <h5 class="mb-0">Edit Libur</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('hariLibur.update', $hariLibur->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            {{-- Tahun Pelajaran --}}
                            <div class="col-md-6 mb-3">
                                <label for="tapel" class="form-label">Tahun Pelajaran</label>
                                <select class="form-control" id="tapel" name="tapel_id">
                                    @forelse ($tapel as $item)
                                        <option value="{{ $item->id }}"
                                            {{ old('tapel_id', $hariLibur->tapel_id) == $item->id ? 'selected' : '' }}>
                                            {{ $item->kode }}
                                        </option>
                                    @empty
                                        <option disabled>Tidak ada Tahun Pelajaran</option>
                                    @endforelse
                                </select>
                            </div>

                            {{-- Instansi --}}
                            <div class="col-md-6 mb-3">
                                <label for="instansi" class="form-label">Instansi</label>
                                <select class="form-control" id="instansi" name="instansi_id">
                                    @forelse ($instansi as $item)
                                        <option value="{{ $item->id }}"
                                            {{ old('instansi_id', $hariLibur->instansi_id) == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama_instansi }}
                                        </option>
                                    @empty
                                        <option disabled>Tidak ada Instansi</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            {{-- Keterangan --}}
                            <div class="col-md-6 mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <input type="text" class="form-control" id="keterangan" name="keterangan"
                                    value="{{ old('keterangan', $hariLibur->keterangan) }}"
                                    placeholder="Contoh: Libur Nasional">
                            </div>

                            {{-- Tanggal Libur --}}
                            <div class="col-md-6 mb-3">
                                <label for="tanggal" class="form-label">Tanggal Libur</label>
                                <input type="date" class="form-control" id="tanggal" name="tanggal"
                                    value="{{ old('tanggal', $hariLibur->tanggal) }}">
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-4">Update</button>
                            <a href="{{ route('hariLibur.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
