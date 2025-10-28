@extends('layout.main')
@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Edit Acara</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Acara</a></div>
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
                    <h5 class="mb-0">Form Edit Acara</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('updateEventOperator', $event->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            {{-- Tahun Pelajaran --}}
                            <div class="col-md-6 mb-3">
                                <label for="tapel" class="form-label">Tahun Pelajaran <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control"
                                    value="{{ $event->tapel ? $event->tapel->kode : 'Tidak ada tahun pelajaran' }}"
                                    readonly>
                                <input type="hidden" name="tapel_id" value="{{ $event->tapel_id }}">
                            </div>

                            {{-- Instansi --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Instansi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control"
                                    value="{{ $event->instansi ? $event->instansi->nama_instansi : 'Tidak ada instansi' }}"
                                    readonly>
                                <input type="hidden" name="instansi_id" value="{{ $event->instansi_id }}">
                            </div>
                        </div>

                        <div class="row">
                            {{-- Nama Acara --}}
                            <div class="col-md-6 mb-3">
                                <label for="nama_event" class="form-label">Nama Acara <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_event') is-invalid @enderror"
                                    id="nama_event" name="nama_event" placeholder="Contoh: Peringatan Hari Kartini"
                                    value="{{ old('nama_event', $event->nama_event) }}" required>
                                @error('nama_event')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Tipe Acara (Jika Admin Yayasan) --}}
                            @if (auth()->user()->hasRole('admin_yayasan'))
                                <div class="col-md-6 mb-3">
                                    <label for="tipe" class="form-label">Tipe Acara</label>
                                    <select class="form-control @error('tipe') is-invalid @enderror" id="tipe"
                                        name="tipe">
                                        <option value="internal" {{ old('tipe', $event->tipe) == 'internal' ? 'selected' : '' }}>
                                            Internal
                                        </option>
                                        <option value="yayasan" {{ old('tipe', $event->tipe) == 'yayasan' ? 'selected' : '' }}>
                                            Yayasan
                                        </option>
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
                                    rows="3" placeholder="Masukkan keterangan acara..." required>{{ old('keterangan', $event->keterangan) }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Acara Sehari --}}
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="acara_sehari" name="acara_sehari"
                                        value="1"
                                        {{ old('acara_sehari', $event->tanggal_mulai == $event->tanggal_selesai ? '1' : '0') == '1' ? 'checked' : '' }}
                                        onchange="toggleAcaraSehari()">
                                    <label class="custom-control-label" for="acara_sehari">
                                        <strong>Acara Sehari</strong> (Jika dicentang, cukup isi tanggal acara saja)
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Tanggal Acara Sehari --}}
                        <div class="row" id="tanggal_sehari_wrapper" style="display: none;">
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_acara_sehari" class="form-label">Tanggal Acara <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_acara_sehari') is-invalid @enderror"
                                    id="tanggal_acara_sehari" name="tanggal_acara_sehari"
                                    value="{{ old('tanggal_acara_sehari', $event->tanggal_mulai == $event->tanggal_selesai ? $event->tanggal_mulai : '') }}">
                                @error('tanggal_acara_sehari')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Tanggal Mulai & Selesai (Multi Hari) --}}
                        <div id="tanggal_range_wrapper">
                            <div class="row">
                                {{-- Tanggal Mulai --}}
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                        id="tanggal_mulai" name="tanggal_mulai"
                                        value="{{ old('tanggal_mulai', $event->tanggal_mulai) }}" required>
                                    @error('tanggal_mulai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Tanggal Selesai --}}
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_selesai" class="form-label">Tanggal Selesai <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                        id="tanggal_selesai" name="tanggal_selesai"
                                        value="{{ old('tanggal_selesai', $event->tanggal_selesai) }}"
                                        required>
                                    @error('tanggal_selesai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <div class="d-flex justify-content-end" style="gap: 10px;">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save"></i> Update
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

@push('script')
    <script>
        function toggleAcaraSehari() {
            var checkbox = document.getElementById('acara_sehari');
            var tanggalSehariWrapper = document.getElementById('tanggal_sehari_wrapper');
            var tanggalRangeWrapper = document.getElementById('tanggal_range_wrapper');
            var tanggalAcaraSehari = document.getElementById('tanggal_acara_sehari');
            var tanggalMulai = document.getElementById('tanggal_mulai');
            var tanggalSelesai = document.getElementById('tanggal_selesai');

            if (checkbox.checked) {
                // Tampilkan input tanggal sehari
                tanggalSehariWrapper.style.display = 'block';
                tanggalRangeWrapper.style.display = 'none';

                // Set required pada tanggal sehari
                tanggalAcaraSehari.required = true;
                tanggalMulai.required = false;
                tanggalSelesai.required = false;

                // JANGAN disable, hanya sembunyikan
                tanggalMulai.disabled = false;
                tanggalSelesai.disabled = false;

                // Jika tanggal acara sehari belum ada nilai, ambil dari tanggal mulai
                if (!tanggalAcaraSehari.value && tanggalMulai.value) {
                    tanggalAcaraSehari.value = tanggalMulai.value;
                }
            } else {
                // Tampilkan input tanggal range
                tanggalSehariWrapper.style.display = 'none';
                tanggalRangeWrapper.style.display = 'block';

                // Set required pada tanggal range
                tanggalAcaraSehari.required = false;
                tanggalMulai.required = true;
                tanggalSelesai.required = true;

                // Enable tanggal mulai dan selesai
                tanggalMulai.disabled = false;
                tanggalSelesai.disabled = false;
            }
        }

        // Sinkronisasi tanggal sehari ke tanggal mulai dan selesai secara real-time
        document.addEventListener('DOMContentLoaded', function() {
            var tanggalAcaraSehari = document.getElementById('tanggal_acara_sehari');
            var tanggalMulai = document.getElementById('tanggal_mulai');
            var tanggalSelesai = document.getElementById('tanggal_selesai');

            // Update tanggal mulai dan selesai saat tanggal acara sehari berubah
            tanggalAcaraSehari.addEventListener('change', function() {
                if (this.value) {
                    tanggalMulai.value = this.value;
                    tanggalSelesai.value = this.value;
                }
            });

            // Validasi tanggal selesai tidak boleh kurang dari tanggal mulai
            tanggalMulai.addEventListener('change', function() {
                tanggalSelesai.min = this.value;
            });

            tanggalSelesai.addEventListener('change', function() {
                var mulaiValue = tanggalMulai.value;
                if (mulaiValue && this.value < mulaiValue) {
                    alert('Tanggal selesai tidak boleh kurang dari tanggal mulai!');
                    this.value = mulaiValue;
                }
            });

            // Pastikan saat submit, tanggal terisi
            document.querySelector('form').addEventListener('submit', function(e) {
                var checkbox = document.getElementById('acara_sehari');
                var tanggalAcaraSehariVal = tanggalAcaraSehari.value;

                if (checkbox.checked && tanggalAcaraSehariVal) {
                    // Pastikan tanggal mulai dan selesai terisi
                    tanggalMulai.value = tanggalAcaraSehariVal;
                    tanggalSelesai.value = tanggalAcaraSehariVal;

                    console.log('Acara Sehari - Tanggal Mulai:', tanggalMulai.value);
                    console.log('Acara Sehari - Tanggal Selesai:', tanggalSelesai.value);
                }
            });

            // Inisialisasi
            toggleAcaraSehari();
        });
    </script>
@endpush
