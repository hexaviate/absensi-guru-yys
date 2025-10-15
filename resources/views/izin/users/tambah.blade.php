@extends('layout.main')
@section('main')
    <section class="section">
        <div class="section-header" style="">
            <h1>HAlaman Izin</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">User</a></div>
                <div class="breadcrumb-item">Dashboard</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card shadow-sm" style="">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Buat Izin</h5>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card-body">
                    <form enctype="multipart/form-data" action="{{ route('izinCreate') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Instansi</label>
                                <div class="selectgroup selectgroup-pills">
                                    @php
                                        $user = auth()->user();
                                    @endphp

                                    @forelse ($instansi as $item)
                                        @if ($user->hasRole('admin_yayasan') && $item->nama_instansi !== 'PUSPELA')
                                            @continue
                                        @endif
                                        @if ($item->nama_instansi === 'SMK')
                                            @continue
                                        @endif

                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="instansi_id[]" value="{{ $item->id }}"
                                                class="selectgroup-input"
                                                {{ old('instansi_id') == $item->id ? 'checked' : '' }}
                                                {{ count($instansi) == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ $item->nama_instansi }}</span>
                                        </label>
                                    @empty
                                        <p class="text-muted mb-0">Tidak ada instansi</p>
                                    @endforelse

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="keterangan" class="form-label">Keterangan</label>
                                    <textarea class="form-control" id="keterangan" name="keterangan" rows="4" placeholder="Masukkan keterangan..."></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bukti_izin" class="form-label">Upload File (Gambar / PDF)</label>
                                    <input type="file" class="form-control" id="bukti_izin" name="bukti_izin"
                                        accept="image/*,.pdf">
                                </div>

                                <div class="mb-3" id="preview-container" style="display:none;">
                                    <p>Preview:</p>
                                    <img id="img-preview" style="max-width:200px; display:none;" />
                                    <iframe id="pdf-preview" style="width:100%; height:300px; display:none;"></iframe>
                                </div>
                            </div>
                        </div>


                        <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:15px;">
                            <button type="submit" class="btn btn-primary px-4">Simpan</button>
                            <button type="reset" class="btn btn-danger">Reset</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </section>
@endsection

@push('script')
    <script>
        document.getElementById('file_upload').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const imgPreview = document.getElementById('img-preview');
            const pdfPreview = document.getElementById('pdf-preview');
            const container = document.getElementById('preview-container');

            if (!file) return;

            container.style.display = 'block';

            if (file.type.startsWith('image/')) {
                imgPreview.src = URL.createObjectURL(file);
                imgPreview.style.display = 'block';
                pdfPreview.style.display = 'none';
            } else if (file.type === 'application/pdf') {
                pdfPreview.src = URL.createObjectURL(file);
                pdfPreview.style.display = 'block';
                imgPreview.style.display = 'none';
            } else {
                imgPreview.style.display = 'none';
                pdfPreview.style.display = 'none';
            }
        });
    </script>
@endpush
