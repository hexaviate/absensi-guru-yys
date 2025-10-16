@extends('layout.main')

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Edit Izin</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('izinIndexUser') }}">Izin</a></div>
                <div class="breadcrumb-item active">Edit Izin</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <h4>Form Edit Izin</h4>
                            <div class="card-header-action">
                                <a href="{{ route('izinIndexUser') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible show fade">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert">
                                            <span>&times;</span>
                                        </button>
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif

                            <form action="{{ route('izinEdit', $izin->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">

                                            @php
                                                $user = auth()->user();
                                            @endphp

                                            <label for="instansi_id">Instansi <span class="text-danger">*</span></label>
                                            <select name="instansi_id" class="form-control">
                                                @foreach ($instansi as $item)
                                                    @if ($user->hasRole('admin_yayasan') && $item->nama_instansi !== 'PUSPELA')
                                                        @continue
                                                    @endif
                                                    @if ($item->nama_instansi === 'SMK Salafiyah')
                                                        @continue
                                                    @endif

                                                    <option value="{{ $item->id }}"
                                                        {{ $item->id == $izin->instansi_id ? 'selected' : '' }}>
                                                        {{ $item->nama_instansi }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            {{-- <small class="form-text text-muted">Instansi tidak dapat diubah</small> --}}
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tanggal">Tanggal Izin</label>
                                            <div class="input-group">
                                                <input type="date" name="tanggal" id="tanggal" class="form-control"
                                                    value="{{ old('tanggal', $izin->tanggal) }}" max="{{ date('Y-m-d') }}">
                                                <div class="input-group-append">
                                                </div>
                                            </div>
                                            <small class="form-text text-muted">
                                                <i class="fas fa-info-circle text-info"></i>
                                                <strong>Opsional:</strong> Kosongkan jika tidak ingin mengubah tanggal izin.
                                                Tanggal saat ini:
                                                <strong>{{ \Carbon\Carbon::parse($izin->tanggal)->format('d M Y') }}</strong>
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="keterangan">Keterangan Izin <span
                                                    class="text-danger">*</span></label>
                                            <textarea name="keterangan" id="keterangan" class="form-control" rows="4"
                                                placeholder="Masukkan keterangan izin..." required>{{ old('keterangan', $izin->keterangan) }}</textarea>
                                            <small class="form-text text-muted">Jelaskan alasan izin dengan detail</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="bukti_izin">Bukti Izin Baru</label>
                                            <div class="custom-file">
                                                <input type="file" name="bukti_izin" id="bukti_izin"
                                                    class="custom-file-input" accept=".jpg,.jpeg,.png,.pdf"
                                                    value="{{ old('bukti_izin', $izin->bukti_izin) }}">
                                                <label class="custom-file-label" for="bukti_izin">Pilih file...</label>
                                            </div>
                                            <small class="form-text text-muted">
                                                <i class="fas fa-info-circle text-info"></i>
                                                <strong>Opsional:</strong> Unggah file baru jika ingin mengganti bukti izin.
                                                Format: JPG, PNG, PDF (Max: 2MB)
                                            </small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Bukti Izin Saat Ini</label>
                                            <div class="current-file-preview">
                                                @php
                                                    $currentFileExtension = strtolower(
                                                        pathinfo($izin->bukti_izin, PATHINFO_EXTENSION),
                                                    );
                                                @endphp

                                                @if ($currentFileExtension === 'pdf')
                                                    <div class="file-preview pdf-preview">
                                                        <i class="fas fa-file-pdf fa-3x text-danger mb-2"></i>
                                                        <p class="mb-2">{{ $izin->bukti_izin }}</p>
                                                        <a href="{{ asset('bukti_izin/' . $izin->bukti_izin) }}"
                                                            target="_blank" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i> Lihat PDF
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="file-preview image-preview">
                                                        <img src="{{ asset('bukti_izin/' . $izin->bukti_izin) }}"
                                                            alt="Bukti Izin" class="img-thumbnail mb-2"
                                                            style="max-width: 200px; max-height: 200px; object-fit: cover;">
                                                        <p class="mb-2">{{ $izin->bukti_izin }}</p>
                                                        <a href="{{ asset('bukti_izin/' . $izin->bukti_izin) }}"
                                                            target="_blank" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-search-plus"></i> Perbesar
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- <!-- In Status -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            <h6><i class="fas fa-info-circle"></i> Informasi Penting:</h6>
                                            <ul class="mb-0 pl-3">
                                                <li><strong>Status saat ini:</strong>
                                                    <span class="badge badge-warning">{{ ucwords(str_replace('_', ' ', $izin->status)) }}</span>
                                                </li>
                                                <li>Izin hanya dapat diedit selama status masih <strong>"Belum Diverifikasi"</strong></li>
                                                <li>Setelah diverifikasi, izin tidak dapat diubah lagi</li>
                                                <li>Tanggal dan bukti izin bersifat opsional untuk diubah</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div> --}}

                                <div class="form-group text-right">
                                    <button type="button" class="btn btn-secondary mr-2" onclick="history.back()">
                                        <i class="fas fa-times"></i> Batal
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            // Custom file input label
            $('.custom-file-input').on('change', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName);
            });

            // Preview file yang akan diupload
            $('#bukti_izin').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const fileType = file.type;
                    const fileSize = file.size;
                    const maxSize = 2 * 1024 * 1024; // 2MB

                    // Check file size
                    if (fileSize > maxSize) {
                        alert('Ukuran file terlalu besar! Maksimal 2MB.');
                        $(this).val('');
                        $('.custom-file-label').removeClass("selected").html('Pilih file...');
                        return;
                    }

                    // Check file type and show preview
                    if (fileType.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            // Create preview untuk gambar baru
                            const preview = `
                                <div class="new-file-preview mt-3 p-3 border rounded">
                                    <label class="form-label">Preview File Baru:</label>
                                    <div class="text-center">
                                        <img src="${e.target.result}"
                                             alt="Preview"
                                             class="img-thumbnail"
                                             style="max-width: 200px; max-height: 200px; object-fit: cover;">
                                        <p class="mt-2 mb-0 text-muted">${file.name}</p>
                                    </div>
                                </div>
                            `;
                            $('.current-file-preview').parent().append(preview);
                        };
                        reader.readAsDataURL(file);
                    } else if (fileType === 'application/pdf') {
                        // Preview untuk PDF
                        const preview = `
                            <div class="new-file-preview mt-3 p-3 border rounded">
                                <label class="form-label">Preview File Baru:</label>
                                <div class="text-center">
                                    <i class="fas fa-file-pdf fa-3x text-danger mb-2"></i>
                                    <p class="mb-0">${file.name}</p>
                                    <small class="text-muted">File PDF siap diupload</small>
                                </div>
                            </div>
                        `;
                        $('.current-file-preview').parent().append(preview);
                    }
                } else {
                    // Remove preview jika file dihapus
                    $('.new-file-preview').remove();
                }
            });

            // Reset tanggal ke tanggal asli
            $('#reset-date').on('click', function() {
                $('#tanggal').val('{{ $izin->tanggal }}');
            });

            // Auto hide alert
            setTimeout(function() {
                $('.alert-dismissible').fadeOut('slow');
            }, 5000);
        });

        // Konfirmasi sebelum submit
        $('form').on('submit', function(e) {
            const tanggalBaru = $('#tanggal').val();
            const tanggalLama = '{{ $izin->tanggal }}';
            const fileInput = $('#bukti_izin')[0].files[0];

            let pesan = 'Apakah Anda yakin ingin menyimpan perubahan?';

            if (tanggalBaru !== tanggalLama) {
                pesan += '\n\n• Tanggal akan diubah dari ' +
                    '{{ \Carbon\Carbon::parse($izin->tanggal)->format('d M Y') }}' +
                    ' ke ' + new Date(tanggalBaru).toLocaleDateString('id-ID');
            }

            if (fileInput) {
                pesan += '\n• Bukti izin akan diganti dengan file baru';
            }

            return confirm(pesan);
        });
    </script>
@endpush

@push('style')
    <style>
        .current-file-preview {
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border: 2px dashed #dee2e6;
        }

        .file-preview {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .pdf-preview i {
            display: block;
        }

        .image-preview img {
            border: 3px solid #e9ecef;
            border-radius: 8px;
        }

        .new-file-preview {
            background-color: #e8f5e8;
            border-color: #28a745 !important;
        }

        .custom-file-label::after {
            content: "Browse";
        }

        .alert-info {
            border-left: 4px solid #17a2b8;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 8px;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        .btn-group {
            gap: 8px;
        }

        @media (max-width: 768px) {
            .card-header-action {
                margin-top: 10px;
            }

            .current-file-preview img {
                max-width: 150px !important;
                max-height: 150px !important;
            }
        }
    </style>
@endpush
