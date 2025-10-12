@extends('layout.main')
@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Halaman Management User</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">User</a></div>
                <div class="breadcrumb-item">Dashboard</div>
            </div>
        </div>

        {{-- DATA USER UNTUK ADMIN YAYASAN --}}

        @hasanyrole('admin_yayasan')

            <div class="section-body">
                <div class="shadow pb-2">
                    <a href="{{ route('user.create') }}" class="btn btn-primary m-2 shadow">Tambah Data User</a>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header">
                                <h4>Data User</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-md" id="example">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Name</th>
                                                <th>No Telephone</th>
                                                <th>Username</th>
                                                <th>Foto Presensi</th>
                                                <th>Foto</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @forelse ($user as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->name }}</td>
                                                    <td>{{ $item->telp }}</td>
                                                    <td>{{ $item->username }}</td>
                                                    <td>{{ $item->foto_presensi }}</td>
                                                    <td>{{ $item->foto }}</td>
                                                    <td class="d-flex">
                                                        <a class="btn btn-warning mx-1"
                                                            href="{{ route('user.edit', $item->id) }}">Edit</a>
                                                        <form action="{{ route('user.destroy', $item->id) }}" method="POST">
                                                            @csrf
                                                            @method('delete')
                                                            <button class="btn btn-danger mx-1">Hapus</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <p>Data Users Kosong, Perlu di Isi</p>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @endhasrole

        {{-- END UNTUK ADMIN YAYASAN --}}

        @hasanyrole('operator_instansi')

        <h1>ini operator uinsat</h1>
        @endhasrole
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
