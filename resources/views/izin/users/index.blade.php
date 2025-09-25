@extends('layout.main')

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Halaman Izin</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Izin</a></div>
                <div class="breadcrumb-item">Dashboard</div>
            </div>
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

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <h4>Data Izin</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="table table-striped table-bordered table-md">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Instansi</th>
                                            <th>Tanggal</th>
                                            <th>keterangan Izin</th>
                                            <th>Bukti</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($izin as $item)
                                            <tr>
                                                <td class="align-middle">{{ $loop->iteration }}</td>
                                                <td class="align-middle">{{ $item->instansi->nama_instansi }}</td>
                                                <td class="align-middle">{{ $item->tanggal }}</td>
                                                <td class="align-middle">{{ $item->keterangan }}</td>
                                                <td class="align-middle">
                                                    <div class="d-flex flex-column gap-2 align-items-center">
                                                        @php
                                                            $fileExtension = strtolower(
                                                                pathinfo($item->bukti_izin, PATHINFO_EXTENSION),
                                                            );
                                                        @endphp

                                                        @if ($fileExtension === 'pdf')
                                                            <a href="{{ asset('bukti_izin/' . $item->bukti_izin) }}"
                                                                target="_blank" class="text text-sm text-primary w-100 m-1">
                                                                <i class="fas fa-file-pdf"></i> Lihat PDF
                                                            </a>
                                                        @else
                                                            <a href="#" data-toggle="modal"
                                                                data-target="#modal-bukti-{{ $item->id }}"
                                                                class="text text-sm text-primary w-100 m-1">
                                                                <i class="fas fa-eye"></i> Lihat Gambar
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="align-middle">
                                                    <span
                                                        class="badge badge-{{ $item->status == 'belum_diverifikasi' ? 'warning' : ($item->status == 'diterima' ? 'success' : 'danger') }} w-100 text-center m-1">
                                                        {{ ucwords(str_replace('_', ' ', $item->status)) }}
                                                    </span>
                                                </td>
                                                <td class="align-middle">
                                                    <div class="d-flex flex-column gap-2 align-items-center w-100">
                                                        @if ($item->status == 'belum_diverifikasi')
                                                            <a href="{{ route('viewIzinEdit', $item->id) }}"
                                                                class="text text-warning btn-sm w-100 m-1">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="align-middle text-center">Anda Belum Memiliki Izin
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal untuk gambar - Dipindah ke dalam section main -->
    @foreach ($izin as $item)
        @php
            $fileExtension = strtolower(pathinfo($item->bukti_izin, PATHINFO_EXTENSION));
        @endphp

        @if ($fileExtension !== 'pdf')
            <div class="modal fade" id="modal-bukti-{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="max-width: 800px;">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-image mr-2"></i>
                                Bukti Izin - {{ $item->user->name }}
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center p-3" style="min-height: 200px;">
                            <div class="mb-3">
                                <small class="text-muted d-block">
                                    <strong>Tanggal:</strong> {{ $item->tanggal }}
                                </small>
                                <small class="text-muted d-block">
                                    <strong>Keterangan:</strong> {{ $item->keterangan }}
                                </small>
                            </div>
                            <div class="image-container">
                                <img src="{{ asset('bukti_izin/' . $item->bukti_izin) }}"
                                    class="img-fluid rounded shadow-sm modal-image"
                                    style="max-height: 400px; max-width: 100%; cursor: pointer;" alt="Bukti Izin">
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-between flex-wrap">
                            <button type="button" class="btn btn-secondary mb-1" data-dismiss="modal">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </button>
                            <div class="btn-group-actions">
                                <a href="{{ asset('bukti_izin/' . $item->bukti_izin) }}" target="_blank"
                                    class="btn btn-primary mr-2 mb-1">
                                    <i class="fas fa-external-link-alt mr-1"></i> Tab Baru
                                </a>
                                <a href="{{ asset('bukti_izin/' . $item->bukti_izin) }}"
                                    download="{{ $item->user->name }}_{{ $item->tanggal }}.{{ $fileExtension }}"
                                    class="btn btn-primary mb-1">
                                    <i class="fas fa-download mr-1"></i> Download
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endsection

@push('script')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                "pagingType": "full_numbers",
                "language": {
                    "paginate": {
                        "first": "<i class='fas fa-angle-double-left'></i>",
                        "last": "<i class='fas fa-angle-double-right'></i>",
                        "next": "<i class='fas fa-chevron-right'></i>",
                        "previous": "<i class='fas fa-chevron-left'></i>"
                    },
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Tidak ada data yang tersedia",
                    "emptyTable": "Tidak ada data dalam tabel",
                    "zeroRecords": "Tidak ada data yang cocok"
                }
            });

            // Auto hide alert
            const alert = $("#alertMessage");
            if (alert.length) {
                setTimeout(() => {
                    alert.alert('close');
                }, 3000);
            }
        });

        // Function untuk menangani download dengan nama file yang lebih baik
        function downloadFile(url, filename) {
            const link = document.createElement('a');
            link.href = url;
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
@endpush

@push('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        .modal {
            z-index: 9999 !important;
        }

        .modal-backdrop {
            z-index: 9998 !important;
        }

        .modal-header {
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
            border-bottom: 1px solid #dee2e6;
        }

        .modal-footer {
            border-bottom-left-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
            border-top: 1px solid #dee2e6;
            background-color: #f8f9fa;
        }

        /* Ukuran modal yang tepat */
        .modal-lg {
            max-width: 800px !important;
        }

        .image-container {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin: 10px 0;
        }

        .btn-group-actions {
            display: flex;
            gap: 8px;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .modal-lg {
                max-width: 95% !important;
                margin: 10px auto;
            }

            .modal-footer {
                flex-direction: column;
                align-items: stretch;
            }

            .btn-group-actions {
                width: 100%;
                justify-content: center;
                margin-top: 10px;
            }

            .modal-footer .btn {
                margin-bottom: 5px;
            }
        }

        @media (max-width: 576px) {
            .btn-sm {
                font-size: 0.75rem;
                padding: 0.25rem 0.5rem;
            }

            .modal-image {
                max-height: 300px !important;
            }

            .btn-group-actions {
                flex-direction: column;
            }

            .btn-group-actions .btn {
                margin-right: 0 !important;
            }
        }

        /* Loading state untuk gambar */
        .modal-image[src=""] {
            background: #f8f9fa url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiBzdHJva2U9IiMwMDc2ZmYiPjxnIGZpbGw9Im5vbmUiIGZpbGwtcnVsZT0iZXZlbm9kZCI+PGcgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMSAxKSIgc3Ryb2tlLXdpZHRoPSIyIj48Y2lyY2xlIHN0cm9rZS1vcGFjaXR5PSIuNSIgY3g9IjE4IiBjeT0iMTgiIHI9IjE4Ii8+PHBhdGggZD0ibTM5IDM5UTM5IDM5IDMzIDMzIj48YW5pbWF0ZVRyYW5zZm9ybSBhdHRyaWJ1dGVOYW1lPSJ0cmFuc2Zvcm0iIHR5cGU9InJvdGF0ZSIgZnJvbT0iMCAxOCAxOCIgdG89IjM2MCAxOCAxOCIgZHVyPSIxcyIgcmVwZWF0Q291bnQ9ImluZGVmaW5pdGUiLz48L3BhdGg+PC9nPjwvZz48L3N2Zz4=') center no-repeat;
            min-height: 200px;
        }

        .modal-xl .modal-body {
            padding: 20px;
        }

        .modal-xl img {
            max-width: 100%;
            max-height: 85vh;
            object-fit: contain;
        }
    </style>
@endpush
