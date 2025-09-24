@extends('layout.main')

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Manajemen Izin</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">Izin</div>
                <div class="breadcrumb-item">Dashboard</div>
            </div>
        </div>

        <div class="section-body">
            <!-- Filter Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-filter"></i> Filter Data Izin</h4>
                            <div class="card-header-action">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary" id="toggleFilter">
                                        <i class="fas fa-search"></i> Filter
                                    </button>
                                    <button type="button" class="btn btn-secondary" id="resetFilter">
                                        <i class="fas fa-undo"></i> Reset
                                    </button>
                                    <button type="button" class="btn btn-success" id="exportData">
                                        <i class="fas fa-download"></i> Export
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" id="filterSection" style="display: none;">
                            <form method="GET" action="{{ route('izinIndexAdmin') }}" id="filterForm">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="instansi_id">Instansi</label>
                                            <select name="instansi_id" id="instansi_id" class="form-control select2">
                                                <option value="">Semua Instansi</option>
                                                @foreach($instansi as $inst)
                                                    <option value="{{ $inst->id }}"
                                                        {{ request('instansi_id') == $inst->id ? 'selected' : '' }}>
                                                        {{ $inst->nama_instansi }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="">Semua Status</option>
                                                <option value="belum_diverifikasi"
                                                    {{ request('status') == 'belum_diverifikasi' ? 'selected' : '' }}>
                                                    Belum Diverifikasi
                                                </option>
                                                <option value="diterima"
                                                    {{ request('status') == 'diterima' ? 'selected' : '' }}>
                                                    Diterima
                                                </option>
                                                <option value="ditolak"
                                                    {{ request('status') == 'ditolak' ? 'selected' : '' }}>
                                                    Ditolak
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>
                                                <input type="checkbox" id="enableDateFilter" name="date_filter"
                                                    {{ request('tanggal_dari') || request('tanggal_sampai') ? 'checked' : '' }}>
                                                Filter Berdasarkan Tanggal
                                            </label>
                                            <div class="row" id="dateFilterInputs" style="{{ request('tanggal_dari') || request('tanggal_sampai') ? '' : 'display: none;' }}">
                                                <div class="col-6">
                                                    <input type="date" name="tanggal_dari" id="tanggal_dari"
                                                           class="form-control form-control-sm"
                                                           placeholder="Tanggal Dari"
                                                           value="{{ request('tanggal_dari') }}">
                                                    <small class="text-muted">Dari</small>
                                                </div>
                                                <div class="col-6">
                                                    <input type="date" name="tanggal_sampai" id="tanggal_sampai"
                                                           class="form-control form-control-sm"
                                                           placeholder="Tanggal Sampai"
                                                           value="{{ request('tanggal_sampai') }}">
                                                    <small class="text-muted">Sampai</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 text-right">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Terapkan Filter
                                        </button>
                                        <a href="{{ route('izinIndexAdmin') }}" class="btn btn-secondary ml-2">
                                            <i class="fas fa-times"></i> Clear
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="far fa-file-alt"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Izin</h4>
                            </div>
                            <div class="card-body">
                                {{ $statistics['total'] }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="far fa-clock"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Belum Diverifikasi</h4>
                            </div>
                            <div class="card-body">
                                {{ $statistics['belum_diverifikasi'] }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Diterima</h4>
                            </div>
                            <div class="card-body">
                                {{ $statistics['diterima'] }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Ditolak</h4>
                            </div>
                            <div class="card-body">
                                {{ $statistics['ditolak'] }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <h4>Data Izin
                                @if(request()->hasAny(['instansi_id', 'status', 'tanggal_dari', 'tanggal_sampai']))
                                    <span class="badge badge-info">Terfilter</span>
                                @endif
                            </h4>
                            <div class="card-header-action">
                                <div class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                        Aksi Bulk
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#" id="bulkApprove">
                                            <i class="fas fa-check text-success"></i> Setujui Terpilih
                                        </a>
                                        <a class="dropdown-item" href="#" id="bulkReject">
                                            <i class="fas fa-times text-danger"></i> Tolak Terpilih
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="izinTable" class="table table-striped table-bordered table-md">
                                    <thead>
                                        <tr>
                                            <th width="20px">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="selectAll">
                                                    <label class="custom-control-label" for="selectAll"></label>
                                                </div>
                                            </th>
                                            <th>No</th>
                                            <th>Nama User</th>
                                            <th>Instansi</th>
                                            <th>Tanggal</th>
                                            <th>Keterangan</th>
                                            <th>Bukti</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($izin as $item)
                                            <tr>
                                                <td>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input row-checkbox"
                                                               id="check{{ $item->id }}" value="{{ $item->id }}">
                                                        <label class="custom-control-label" for="check{{ $item->id }}"></label>
                                                    </div>
                                                </td>
                                                <td class="align-middle">{{ $loop->iteration }}</td>
                                                <td class="align-middle">
                                                    <div class="d-flex align-items-center">
                                                        <img class="rounded-circle mr-2"
                                                             src="{{ $item->user->foto_profil ? asset('foto_profil/' . $item->user->foto_profil) : asset('assets/img/avatar/avatar-1.png') }}"
                                                             width="35" height="35">
                                                        <div>
                                                            <strong>{{ $item->user->name }}</strong>
                                                            <br><small class="text-muted">{{ $item->user->email }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="align-middle">{{ $item->instansi->nama_instansi }}</td>
                                                <td class="align-middle">
                                                    <span class="badge badge-light">
                                                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                                    </span>
                                                </td>
                                                <td class="align-middle">
                                                    <span class="text-truncate" style="max-width: 150px; display: inline-block;"
                                                          title="{{ $item->keterangan }}">
                                                        {{ $item->keterangan }}
                                                    </span>
                                                </td>
                                                <td class="align-middle">
                                                    @php
                                                        $fileExtension = strtolower(pathinfo($item->bukti_izin, PATHINFO_EXTENSION));
                                                    @endphp

                                                    @if ($fileExtension === 'pdf')
                                                        <a href="{{ asset('bukti_izin/' . $item->bukti_izin) }}"
                                                            target="_blank" class="btn btn-sm btn-outline-danger">
                                                            <i class="fas fa-file-pdf"></i> PDF
                                                        </a>
                                                    @else
                                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                                                data-toggle="modal" data-target="#modal-bukti-{{ $item->id }}">
                                                            <i class="fas fa-image"></i> Gambar
                                                        </button>
                                                    @endif
                                                </td>
                                                <td class="align-middle">
                                                    <span class="badge badge-{{ $item->status == 'belum_diverifikasi' ? 'warning' : ($item->status == 'diterima' ? 'success' : 'danger') }}">
                                                        {{ ucwords(str_replace('_', ' ', $item->status)) }}
                                                    </span>
                                                </td>
                                                <td class="align-middle">
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle"
                                                                type="button" data-toggle="dropdown">
                                                            Aksi
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            @if($item->status == 'belum_diverifikasi')
                                                                <a class="dropdown-item text-success"
                                                                   href="{{ route('izinApprove', $item->id) }}"
                                                                   onclick="return confirm('Setujui izin ini?')">
                                                                    <i class="fas fa-check"></i> Setujui
                                                                </a>
                                                                <a class="dropdown-item text-danger"
                                                                   href="{{ route('izinReject', $item->id) }}"
                                                                   onclick="return confirm('Tolak izin ini?')">
                                                                    <i class="fas fa-times"></i> Tolak
                                                                </a>
                                                                <div class="dropdown-divider"></div>
                                                            @endif
                                                            <a class="dropdown-item"
                                                               href="{{ route('izinDetail', $item->id) }}">
                                                                <i class="fas fa-eye"></i> Detail
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center py-4">
                                                    <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                                    <p class="text-muted">Tidak ada data izin yang ditemukan</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    <small class="text-muted">
                                        Menampilkan {{ $izin->firstItem() ?? 0 }} sampai {{ $izin->lastItem() ?? 0 }}
                                        dari {{ $izin->total() }} data
                                    </small>
                                </div>
                                <div>
                                    {{ $izin->appends(request()->query())->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal untuk gambar -->
    @foreach ($izin as $item)
        @php
            $fileExtension = strtolower(pathinfo($item->bukti_izin, PATHINFO_EXTENSION));
        @endphp

        @if ($fileExtension !== 'pdf')
            <div class="modal fade" id="modal-bukti-{{ $item->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-image mr-2"></i>
                                Bukti Izin - {{ $item->user->name }}
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center p-3">
                            <div class="mb-3">
                                <small class="text-muted d-block">
                                    <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                </small>
                                <small class="text-muted d-block">
                                    <strong>Keterangan:</strong> {{ $item->keterangan }}
                                </small>
                            </div>
                            <div class="image-container">
                                <img src="{{ asset('bukti_izin/' . $item->bukti_izin) }}"
                                    class="img-fluid rounded shadow-sm modal-image"
                                    style="max-height: 400px; max-width: 100%;" alt="Bukti Izin">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <a href="{{ asset('bukti_izin/' . $item->bukti_izin) }}" target="_blank" class="btn btn-primary">
                                <i class="fas fa-external-link-alt"></i> Buka di Tab Baru
                            </a>
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
            // Initialize DataTables
            $('#izinTable').DataTable({
                "paging": false,
                "searching": false,
                "ordering": true,
                "info": false,
                "language": {
                    "emptyTable": "Tidak ada data izin yang tersedia"
                }
            });

            // Toggle filter section
            $('#toggleFilter').click(function() {
                $('#filterSection').slideToggle();
                const icon = $(this).find('i');
                icon.toggleClass('fa-search fa-search-minus');
            });

            // Show filter if there are active filters
            @if(request()->hasAny(['instansi_id', 'status', 'tanggal_dari', 'tanggal_sampai']))
                $('#filterSection').show();
                $('#toggleFilter i').removeClass('fa-search').addClass('fa-search-minus');
            @endif

            // Enable/Disable date filter inputs
            $('#enableDateFilter').change(function() {
                if ($(this).is(':checked')) {
                    $('#dateFilterInputs').slideDown();
                } else {
                    $('#dateFilterInputs').slideUp();
                    $('#tanggal_dari, #tanggal_sampai').val('');
                }
            });

            // Date range validation
            $('#tanggal_dari').change(function() {
                $('#tanggal_sampai').attr('min', $(this).val());
            });

            $('#tanggal_sampai').change(function() {
                $('#tanggal_dari').attr('max', $(this).val());
            });

            // Select all checkbox
            $('#selectAll').change(function() {
                $('.row-checkbox').prop('checked', $(this).is(':checked'));
                updateBulkActions();
            });

            // Individual checkbox
            $('.row-checkbox').change(function() {
                if (!$(this).is(':checked')) {
                    $('#selectAll').prop('checked', false);
                } else if ($('.row-checkbox:checked').length === $('.row-checkbox').length) {
                    $('#selectAll').prop('checked', true);
                }
                updateBulkActions();
            });

            // Update bulk action visibility
            function updateBulkActions() {
                const selectedCount = $('.row-checkbox:checked').length;
                if (selectedCount > 0) {
                    $('#bulkApprove, #bulkReject').removeClass('disabled');
                } else {
                    $('#bulkApprove, #bulkReject').addClass('disabled');
                }
            }

            // Bulk approve
            $('#bulkApprove').click(function(e) {
                e.preventDefault();
                if ($(this).hasClass('disabled')) return;

                const selected = $('.row-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (confirm(`Setujui ${selected.length} izin terpilih?`)) {
                    $.post('{{ route("izinBulkApprove") }}', {
                        _token: '{{ csrf_token() }}',
                        ids: selected
                    }).done(function() {
                        location.reload();
                    });
                }
            });

            // Bulk reject
            $('#bulkReject').click(function(e) {
                e.preventDefault();
                if ($(this).hasClass('disabled')) return;

                const selected = $('.row-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (confirm(`Tolak ${selected.length} izin terpilih?`)) {
                    $.post('{{ route("izinBulkReject") }}', {
                        _token: '{{ csrf_token() }}',
                        ids: selected
                    }).done(function() {
                        location.reload();
                    });
                }
            });

            // Reset filter
            $('#resetFilter').click(function() {
                window.location.href = '{{ route("izinIndexAdmin") }}';
            });

            // Export data
            $('#exportData').click(function() {
                const params = new URLSearchParams(window.location.search);
                params.set('export', 'excel');
                window.location.href = '{{ route("izinIndexAdmin") }}?' + params.toString();
            });

            // Initialize select2
            $('.select2').select2({
                placeholder: "Pilih instansi...",
                allowClear: true
            });
        });
    </script>
@endpush

@push('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        .card-statistic-1 {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-left: 4px solid #007bff;
        }

        .select2-container .select2-selection--single {
            height: 38px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
        }

        .text-truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .image-container {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin: 10px 0;
        }

        .disabled {
            opacity: 0.5;
            pointer-events: none;
        }
    </style>
@endpush
