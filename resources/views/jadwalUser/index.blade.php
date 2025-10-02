@extends('layout.main')

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Jadwal Minggu Ini</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item">Jadwal Saya</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <h4>Jadwal Praktik Minggu Ini</h4>
                        </div>
                        <div class="card-body">
                            @if($jadwal->isEmpty())
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Anda belum memiliki jadwal praktik minggu ini.
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped table-bordered table-md">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Instansi</th>
                                                <th>Hari</th>
                                                <th>Jam Datang</th>
                                                <th>Jam Pulang</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($jadwal as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->instansi->nama_instansi }}</td>
                                                    <td>{{ $item->hari }}</td>
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
