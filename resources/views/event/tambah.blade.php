@extends('layout.main')
@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Tambah Event</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Event</a></div>
                <div class="breadcrumb-item">Dashboard</div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-title">Error!</div>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="section-body">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Form Tambah Event</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('storeEventOperator') }}" method="POST">
                        @csrf
                        <div class="row">
                            {{-- Tahun Pelajaran --}}
                            <div class="col-md-6 mb-3">
                                <label for="tapel" class="form-label">Tahun Pelajaran <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control"
                                    value="{{ $tapelAktif ? $tapelAktif->kode : 'Tidak ada tahun pelajaran aktif' }}"
                                    readonly>
                                <input type="hidden" name="tapel_id" value="{{ $tapelAktif ? $tapelAktif->id : '' }}">
                            </div>

                            {{-- Instansi --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Instansi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control"
                                    value="{{ $instansiOperator ? $instansiOperator->nama_instansi : 'Tidak ada instansi' }}"
                                    readonly>
                                <input type="hidden" name="instansi_id"
                                    value="{{ $instansiOperator ? $instansiOperator->id : '' }}">
                            </div>
                        </div>

                        <div class="row">
                            {{-- Nama Event --}}
                            <div class="col-md-6 mb-3">
                                <label for="nama_event" class="form-label">Nama Event <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_event') is-invalid @enderror"
                                    id="nama_event" name="nama_event" placeholder="Contoh: Peringatan Hari Kartini"
                                    value="{{ old('nama_event') }}" required>
                                @error('nama_event')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Tipe Event (Jika Admin Yayasan) --}}
                            @if (auth()->user()->hasRole('admin_yayasan'))
                                <div class="col-md-6 mb-3">
                                    <label for="tipe" class="form-label">Tipe Event</label>
                                    <select class="form-control @error('tipe') is-invalid @enderror" id="tipe"
                                        name="tipe">
                                        <option value="internal" {{ old('tipe') == 'internal' ? 'selected' : '' }}>Internal
                                        </option>
                                        <option value="yayasan" {{ old('tipe') == 'yayasan' ? 'selected' : '' }}>
                                            Eksternal</option>
                                    </select>
                                    @error('tipe')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif
                        </div>

                        <div class="row">
                            {{-- Keterangan --}}
                            <div class="col-md-12 mb-3">
                                <label for="keterangan" class="form-label">Keterangan <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan"
                                    rows="3" placeholder="Masukkan keterangan event..." required>{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            {{-- Tanggal Mulai --}}
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_mulai" class="form-label">Tanggal Mulai <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                    id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required>
                                @error('tanggal_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Tanggal Selesai --}}
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_selesai" class="form-label">Tanggal Selesai <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                    id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}"
                                    required>
                                @error('tanggal_selesai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <div class="d-flex justify-content-end" style="gap: 10px;">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                                <a href="{{ route('indexEventOperator') }}" class="btn btn-secondary px-4">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Validasi tanggal selesai tidak boleh kurang dari tanggal mulai
        document.getElementById('tanggal_mulai').addEventListener('change', function() {
            document.getElementById('tanggal_selesai').min = this.value;
        });

        document.getElementById('tanggal_selesai').addEventListener('change', function() {
            const tanggalMulai = document.getElementById('tanggal_mulai').value;
            const tanggals = document.getElementById('tanggal_selesai').value;
            if (tanggalMulai && this.value < tanggals) {
                alert('Tanggal selesai tidak boleh kurang dari tanggal mulai!');
                this.value = tanggalMulai;
            }
        });
    </script>
@endpush
