@extends('layout.main')

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Halaman Management Jadwal</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Jadwal</a></div>
                <div class="breadcrumb-item">Dashboard</div>
            </div>
        </div>

        <div class="section-body">
            <div class="shadow pb-2">
                <a href="{{ route('jadwal.create') }}" class="btn btn-primary m-2 shadow">Tambah Data Instansi</a>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <h4>Data Instansi</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="table table-striped table-bordered table-md">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tapel</th>
                                            <th>Instansi</th>
                                            <th>User</th>
                                            <th>Hari</th>
                                            <th>Datang</th>
                                            <th>Pulang</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($jadwal as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->tapel->kode }}</td>
                                                <td>{{ $item->instansi->nama_instansi }}</td>
                                                <td>{{ $item->user->name }}</td>
                                                <td>{{ $item->hari }}</td>
                                                <td>{{ $item->datang }}</td>
                                                <td>{{ $item->pulang }}</td>
                                                <td class="d-flex">
                                                    <a href="{{ route('jadwal.edit', $item->id) }}"
                                                        class="btn btn-warning mx-2">Edit</a>
                                                    <form action="{{ route('jadwal.destroy', $item->id) }}" method="POST"
                                                        onsubmit="return confirm('Yakin ingin menghapus?')">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Data Instansi Kosong</td>
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
@endsection

@push('script')
    {{-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> --}}
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                "pagingType": "full_numbers", // biar ada prev, next, first, last
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


