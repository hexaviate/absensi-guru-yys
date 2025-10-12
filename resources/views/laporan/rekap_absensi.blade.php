@extends('layout.main')
@section('main')
    <section class="section">
        <div class="section-header">
            <h1>REKAP ABSENSI GURU & KARYAWAN</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">User</a></div>
                <div class="breadcrumb-item">Dashboard</div>
            </div>
        </div>

        <div class="section-body">
            <div class="shadow pb-1">
                <!-- REKAP HARIAN -->
                <h4 class="p-2 m-2">Rekap Harian</h4>
                <div class="border p-2 m-2">
                    <form id="laporan-harian-form" method="GET" class="p-3">
                        <div class="row mb-3">
                            <div class="col-md-4 col-lg-3 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="">-- Semua Status --</option>
                                    <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir
                                    </option>
                                    <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                                </select>
                            </div>

                            @hasrole('admin_yayasan')
                                <div class="col-md-4 col-lg-3 mb-3">
                                    <label class="form-label">Instansi</label>
                                    <select name="instansi" class="form-control">
                                        <option value="">-- Semua Instansi --</option>
                                        @foreach ($instansi as $i)
                                            <option value="{{ $i->id }}"
                                                {{ request('instansi') == $i->id ? 'selected' : '' }}>
                                                {{ $i->nama_instansi }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endhasrole

                            @hasrole('operator_instansi')
                                <input type="hidden" name="instansi" value="{{ $user->instansi()->first()->id ?? '' }}">
                            @endhasrole


                            <div class="col-md-4 col-lg-3 mb-3">
                                <label class="form-label">Tanggal hari ini</label>
                                <input type="date" name="tanggal" class="form-control"
                                    value="{{ request('tanggal', now()->toDateString()) }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="btn-group-responsive d-flex flex-wrap gap-2">
                                    <button type="submit" class="btn btn-primary mb-2 mb-sm-0">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                    <a href="{{ route('rekap_absensi.index') }}" class="btn btn-secondary mb-2 mb-sm-0">
                                        <i class="fas fa-redo"></i> Reset
                                    </a>
                                    <button type="button" class="btn btn-danger mb-2 mb-sm-0"
                                        onclick="submitForm('{{ route('rekap_absensi.exportPDF') }}')">
                                        <i class="fas fa-file-pdf"></i> Export PDF
                                    </button>
                                    <button type="button" class="btn btn-success mb-2 mb-sm-0"
                                        onclick="submitForm('{{ route('rekap_absensi.exportExcel') }}')">
                                        <i class="fas fa-file-excel"></i> Export Excel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- REKAP BULANAN -->
                <h4 class="p-2 m-2">Rekap Bulanan</h4>
                <div class="border p-2 m-2">
                    <form id="laporan-bulanan-form" method="GET" class="p-3">
                        <div class="row mb-3">
                            <div class="col-md-6 col-lg-3 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="">-- Semua Status --</option>
                                    <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir
                                    </option>
                                    <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin
                                    </option>
                                </select>
                            </div>

                            @hasrole('admin_yayasan')
                                <div class="col-md-4 col-lg-3 mb-3">
                                    <label class="form-label">Instansi</label>
                                    <select name="instansi" class="form-control">
                                        <option value="">-- Semua Instansi --</option>
                                        @foreach ($instansi as $i)
                                            <option value="{{ $i->id }}"
                                                {{ request('instansi') == $i->id ? 'selected' : '' }}>
                                                {{ $i->nama_instansi }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endhasrole

                            @hasrole('operator_instansi')
                                <input type="hidden" name="instansi" value="{{ $user->instansi()->first()->id ?? '' }}">
                            @endhasrole

                            <div class="col-md-6 col-lg-3 mb-3">
                                <label class="form-label">Dari Tanggal</label>
                                <input type="date" name="dari_tanggal" class="form-control"
                                    value="{{ request('dari_tanggal') }}">
                            </div>

                            <div class="col-md-6 col-lg-3 mb-3">
                                <label class="form-label">Sampai Tanggal</label>
                                <input type="date" name="sampai_tanggal" class="form-control"
                                    value="{{ request('sampai_tanggal') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="btn-group-responsive d-flex flex-wrap gap-2">
                                    <button type="submit" class="btn btn-primary mb-2 mb-sm-0">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                    <a href="{{ route('rekap_absensi.index') }}" class="btn btn-secondary mb-2 mb-sm-0">
                                        <i class="fas fa-redo"></i> Reset
                                    </a>
                                    <button type="button" class="btn btn-danger mb-2 mb-sm-0"
                                        onclick="submitFormBulanan('{{ route('rekap_absensi.exportPDFBulanan') }}')">
                                        <i class="fas fa-file-pdf"></i> Export PDF
                                    </button>
                                    <button type="button" class="btn btn-success mb-2 mb-sm-0"
                                        onclick="submitFormBulanan('{{ route('rekap_absensi.exportExcelBulanan') }}')">
                                        <i class="fas fa-file-excel"></i> Export Excel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- REKAP TAHUNAN -->
                <h4 class="p-2 m-2">Rekap Tahunan</h4>
                <div class="border p-2 m-2">
                    <form id="laporan-tahunan-form" method="GET" class="p-3">
                        <div class="row mb-3">
                            <div class="col-md-4 col-lg-3 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="">-- Semua Status --</option>
                                    <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir
                                    </option>
                                    <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin
                                    </option>
                                </select>
                            </div>

                            @hasrole('admin_yayasan')
                                <div class="col-md-4 col-lg-3 mb-3">
                                    <label class="form-label">Instansi</label>
                                    <select name="instansi" class="form-control">
                                        <option value="">-- Semua Instansi --</option>
                                        @foreach ($instansi as $i)
                                            <option value="{{ $i->id }}"
                                                {{ request('instansi') == $i->id ? 'selected' : '' }}>
                                                {{ $i->nama_instansi }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endhasrole

                            @hasrole('operator_instansi')
                                <input type="hidden" name="instansi" value="{{ $user->instansi()->first()->id ?? '' }}">
                            @endhasrole

                            <div class="col-md-4 col-lg-3 mb-3">
                                <label class="form-label">Tahun Ajaran</label>
                                <select name="tahun_ajaran" class="form-control">
                                    <option value="">-- Semua Tahun Ajaran --</option>
                                    @foreach ($tapels as $tapel)
                                        <option value="{{ $tapel->id }}"
                                            {{ request('tahun_ajaran') == $tapel->id ? 'selected' : '' }}>
                                            {{ $tapel->kode }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="btn-group-responsive d-flex flex-wrap gap-2">
                                    <button type="submit" class="btn btn-primary mb-2 mb-sm-0">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                    <a href="{{ route('rekap_absensi.index') }}" class="btn btn-secondary mb-2 mb-sm-0">
                                        <i class="fas fa-redo"></i> Reset
                                    </a>
                                    <button type="button" class="btn btn-danger mb-2 mb-sm-0"
                                        onclick="submitFormTahunan('{{ route('rekap_absensi.exportPDFTahunan') }}')">
                                        <i class="fas fa-file-pdf"></i> Export PDF
                                    </button>
                                    <button type="button" class="btn btn-success mb-2 mb-sm-0"
                                        onclick="submitFormTahunan('{{ route('rekap_absensi.exportExcelTahunan') }}')">
                                        <i class="fas fa-file-excel"></i> Export Excel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TABEL DATA -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <h3>Data Presensi Guru & Karyawan</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-md" id="example">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Nama Guru/Karyawan</th>
                                            <th>Nama Instansi</th>
                                            <th>Datang</th>
                                            <th>Pulang</th>
                                            <th>Status</th>
                                            <th>Bukti Izin</th>
                                            <th>Akurasi</th>
                                            <th>Device</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($presensi->count() > 0)
                                            @foreach ($presensi as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-M-Y') }}</td>
                                                    <td>{{ $item->user->name ?? '-' }}</td>
                                                    <td>{{ $item->instansi->nama_instansi ?? '-' }}</td>
                                                    <td>{{ $item->datang ?? '-' }}</td>
                                                    <td>{{ $item->pulang ?? '-' }}</td>
                                                    <td>
                                                        <span
                                                            class="badge @if ($item->status == 'hadir') bg-success @elseif($item->status == 'izin') bg-warning text-dark @else bg-danger @endif">
                                                            {{ ucfirst($item->status) }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($item->status == 'hadir')
                                                            <i class="fas fa-times text-danger"></i>
                                                        @else
                                                            <i class="fas fa-check text-success"></i>
                                                        @endif
                                                    </td>
                                                    <td>{{ $item->akurasi ?? '0' }}%</td>
                                                    <td>{{ $item->userAgent ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="10" class="text-center">Data Kosong</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        .btn-group-responsive {
            gap: 0.5rem;
        }

        @media (max-width: 576px) {
            .btn-group-responsive .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }
        }

        @media (min-width: 577px) and (max-width: 768px) {
            .btn-group-responsive .btn {
                flex: 1 1 calc(50% - 0.5rem);
            }
        }
    </style>
@endpush

@push('script')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        function submitForm(action) {
            const form = document.getElementById('laporan-harian-form');
            if (!form) return;

            const exportForm = document.createElement('form');
            exportForm.method = 'GET';
            exportForm.action = action;

            const inputs = form.querySelectorAll('input, select');
            inputs.forEach(input => {
                if (input.name && input.value) {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = input.name;
                    hiddenInput.value = input.value;
                    exportForm.appendChild(hiddenInput);
                }
            });

            document.body.appendChild(exportForm);
            exportForm.submit();
            setTimeout(() => document.body.removeChild(exportForm), 1000);
        }

        function submitFormBulanan(action) {
            const form = document.getElementById('laporan-bulanan-form');
            if (!form) return;

            const exportForm = document.createElement('form');
            exportForm.method = 'GET';
            exportForm.action = action;

            const inputs = form.querySelectorAll('input, select');
            inputs.forEach(input => {
                if (input.name && input.value) {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = input.name;
                    hiddenInput.value = input.value;
                    exportForm.appendChild(hiddenInput);
                }
            });

            document.body.appendChild(exportForm);
            exportForm.submit();
            setTimeout(() => document.body.removeChild(exportForm), 1000);
        }

        function submitFormTahunan(action) {
            const form = document.getElementById('laporan-tahunan-form');
            if (!form) return;

            const exportForm = document.createElement('form');
            exportForm.method = 'GET';
            exportForm.action = action;

            const inputs = form.querySelectorAll('input, select');
            inputs.forEach(input => {
                if (input.name && input.value) {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = input.name;
                    hiddenInput.value = input.value;
                    exportForm.appendChild(hiddenInput);
                }
            });

            document.body.appendChild(exportForm);
            exportForm.submit();
            setTimeout(() => document.body.removeChild(exportForm), 1000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Handler untuk tombol export
            const exportButtons = document.querySelectorAll(
                '#laporan-harian-form .btn-danger, #laporan-harian-form .btn-success, ' +
                '#laporan-bulanan-form .btn-danger, #laporan-bulanan-form .btn-success, ' +
                '#laporan-tahunan-form .btn-danger, #laporan-tahunan-form .btn-success'
            );

            exportButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const originalText = this.innerHTML;
                    this.disabled = true;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengunduh...';

                    setTimeout(() => {
                        this.disabled = false;
                        this.innerHTML = originalText;
                    }, 5000);
                });
            });

            // Initialize DataTable dengan error handling
            try {
                // Cek apakah tabel sudah diinisialisasi
                if ($.fn.DataTable.isDataTable('#example')) {
                    $('#example').DataTable().destroy();
                }

                // Hitung jumlah kolom yang sebenarnya ada di thead
                const columnCount = $('#example thead tr th').length;
                console.log('Column count:', columnCount);

                // Inisialisasi DataTable
                $('#example').DataTable({
                    "pagingType": "full_numbers",
                    "language": {
                        "paginate": {
                            "first": "<i class='fas fa-angle-double-left'></i>",
                            "last": "<i class='fas fa-angle-double-right'></i>",
                            "next": "<i class='fas fa-chevron-right'></i>",
                            "previous": "<i class='fas fa-chevron-left'></i>"
                        },
                        "emptyTable": "Data Kosong",
                        "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                        "infoFiltered": "(disaring dari _MAX_ total data)",
                        "lengthMenu": "Tampilkan _MENU_ data",
                        "search": "Cari:",
                        "zeroRecords": "Data tidak ditemukan"
                    },
                    "order": [
                        [1, "desc"]
                    ], // Sort by tanggal (kolom ke-2)
                    "columnDefs": [{
                            "orderable": false,
                            "targets": [7]
                        } // Bukti Izin tidak bisa disort
                    ]
                });

                console.log('DataTable initialized successfully');
            } catch (error) {
                console.error('Error initializing DataTable:', error);
            }
        });
    </script>
@endpush
