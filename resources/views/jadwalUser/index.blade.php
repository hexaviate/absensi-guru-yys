@extends('layout.main')

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Jadwal Anda</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item">Jadwal</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <h4>Jadwal Mengajar</h4>
                        </div>
                        <div class="card-body">
                            <!-- Filter Section -->
                            <form method="GET" action="{{ route('jadwalUser') }}" class="mb-4">
                                @csrf
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="filter_instansi">Filter Instansi</label>
                                            <select name="filter_instansi" id="filter_instansi" class="form-control">
                                                <option value="">-- Semua Instansi --</option>
                                                @foreach ($instansiList as $instansi)
                                                    <option value="{{ $instansi->id }}">
                                                        {{ request('filter_instansi') == $instansi->id ? 'selected' : '' }}
                                                        {{ $instansi->nama_instansi }}

                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="filter_hari">Filter Hari</label>
                                            <select name="filter_hari" id="filter_hari" class="form-control">
                                                <option value="">-- Semua Hari --</option>
                                                <option value="Senin"
                                                    {{ request('filter_hari') == 'Senin' ? 'selected' : '' }}>Senin
                                                </option>
                                                <option value="Selasa"
                                                    {{ request('filter_hari') == 'Selasa' ? 'selected' : '' }}>Selasa
                                                </option>
                                                <option value="Rabu"
                                                    {{ request('filter_hari') == 'Rabu' ? 'selected' : '' }}>Rabu
                                                </option>
                                                <option value="Kamis"
                                                    {{ request('filter_hari') == 'Kamis' ? 'selected' : '' }}>Kamis
                                                </option>
                                                <option value="Jumat"
                                                    {{ request('filter_hari') == 'Jumat' ? 'selected' : '' }}>Jumat
                                                </option>
                                                <option value="Sabtu"
                                                    {{ request('filter_hari') == 'Sabtu' ? 'selected' : '' }}>Sabtu
                                                </option>
                                                <option value="Minggu"
                                                    {{ request('filter_hari') == 'Minggu' ? 'selected' : '' }}>Minggu
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <button type="submit" class="btn btn-primary btn-block">
                                                <i class="fas fa-filter"></i> Filter
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @if (request('filter_instansi') || request('filter_hari'))
                                    <div class="row">
                                        <div class="col-12">
                                            <a href="{{ url()->current() }}" class="btn btn-sm btn-secondary">
                                                <i class="fas fa-times"></i> Reset Filter
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </form>

                            @if ($jadwal->isEmpty())
                                <div class="alert alert-info">
                                     Tidak ada jadwal praktik ditemukan
                                    @if (request('filter_instansi') || request('filter_hari'))
                                        dengan filter yang dipilih.
                                    @else
                                        untuk minggu ini.
                                    @endif
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped table-bordered table-md">
                                        <thead>
                                            <tr>
                                                <th>Hari</th>
                                                <th>Instansi</th>
                                                <th>Jam Datang</th>
                                                <th>Jam Pulang</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($jadwal as $item)
                                                <tr>
                                                    <td>{{ $item->hari }}</td>
                                                    <td>{{ $item->instansi->nama_instansi }}</td>
                                                    <td>{{ $item->datang }}</td>
                                                    <td>{{ $item->pulang }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                "pagingType": "full_numbers",
                dom: 'frtip',
                lengthChange: false,
                "language": {
                    "paginate": {
                        "first": "<i class='fas fa-angle-double-left'></i>",
                        "last": "<i class='fas fa-angle-double-right'></i>",
                        "next": "<i class='fas fa-chevron-right'></i>",
                        "previous": "<i class='fas fa-chevron-left'></i>"
                    }
                }
            });
        });
    </script>
@endpush

@push('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endpush
