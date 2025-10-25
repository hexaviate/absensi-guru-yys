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
                    <a href="{{ Auth::user()->hasRole('admin_yayasan') ? route('userImportViewAdmin') : route('userImportViewOperator') }}" class="btn btn-primary m-2 shadow">Import Data User</a>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header">
                                <h4>Data User</h4>
                            </div>
                            <div class="card-body">
                                @if ($semuaUser->isEmpty())
                                    <div class="d-flex justify-content-center align-items-center" style="height: 50px;">
                                        <span class="font-weight-bold">Data Jadwal Kosong</span>
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-md" id="example">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nomor Induk</th>
                                                    <th>Name</th>
                                                    <th>Peran</th>
                                                    <th>No Telephone</th>
                                                    <th>Username</th>
                                                    <th>instansi</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @forelse ($semuaUser as $item)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $item->nomor_induk_yayasan }}</td>
                                                        <td>{{ $item->name }}</td>
                                                        <td>
                                                            @forelse ($item->roles as $role)
                                                                {{ Str::of($role->name)->replace('_', ' ')->title() }}{{ !$loop->last ? ', ' : '' }}
                                                            @empty
                                                                Tidak Punya Role
                                                            @endforelse
                                                        </td>

                                                        <td>{{ $item->telp }}</td>
                                                        <td>{{ $item->username }}</td>
                                                        <td>
                                                            @if ($item->roles->contains('name', 'admin_yayasan'))
                                                                Terdaftar di semua instansi
                                                            @else
                                                                @forelse ($item->instansi as $ins)
                                                                    {{ $ins->nama_instansi }}{{ !$loop->last ? ', ' : '' }}
                                                                @empty
                                                                    Belum Ada Instansi
                                                                @endforelse
                                                            @endif
                                                        </td>
                                                        <td class="d-flex">
                                                            <a class="btn btn-warning mx-1"
                                                                href="{{ route('user.edit', $item->id) }}">Edit</a>
                                                            <form action="{{ route('user.destroy', $item->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('delete')
                                                                <button class="btn btn-danger mx-1 delete-btn">Hapus</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <p>Data Users Kosong, Perlu di Isi</p>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @endhasrole

        {{-- END UNTUK ADMIN YAYASAN --}}

        @hasanyrole('operator_instansi')
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="shadow pb-2">
                            <a href="{{ route('user.create') }}" class="btn btn-primary m-2 shadow">Tambah Data User</a>
                          <a href="{{ Auth::user()->hasRole('admin_yayasan') ? route('userImportViewAdmin') : route('userImportViewOperator') }}" class="btn btn-primary m-2 shadow">Import Data User</a>
                        </div>
                        <div class="card shadow">
                            <div class="card-header">
                                <h4>Data User</h4>
                            </div>
                            <div class="card-body">
                                @if ($userInstansi->isEmpty())
                                    <div class="d-flex justify-content-center align-items-center" style="height: 50px;">
                                        <span class="font-weight-bold">Data Jadwal Kosong</span>
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-md" id="example">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nomor Induk</th>
                                                    <th>Name</th>
                                                    <th>Peran</th>
                                                    <th>No Telephone</th>
                                                    <th>Username</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @forelse ($userInstansi as $item)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $item->nomor_induk_yayasan }}</td>
                                                        <td>{{ $item->name }}</td>
                                                        <td>
                                                            @forelse ($item->roles as $role)
                                                                {{ Str::of($role->name)->replace('_', ' ')->title() }}{{ !$loop->last ? ', ' : '' }}
                                                            @empty
                                                                Tidak Punya Role
                                                            @endforelse
                                                        </td>
                                                        <td>{{ $item->telp }}</td>
                                                        <td>{{ $item->username }}</td>
                                                        <td class="d-flex">
                                                            <a class="btn btn-warning mx-1"
                                                                href="{{ route('user.edit', $item->id) }}">Edit</a>
                                                            <form action="{{ route('user.destroy', $item->id) }}"
                                                                method="POST" class="delete-form d-inline">
                                                                @csrf
                                                                @method('delete')
                                                                <button type="button"
                                                                    class="btn btn-danger mx-1 delete-btn">Hapus</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <p>Data Users Kosong, Perlu di Isi</p>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.delete-btn').on('click', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');

                swal({
                    title: 'Yakin ingin menghapus?',
                    text: 'Data yang dihapus tidak bisa dikembalikan!',
                    icon: 'warning',
                    buttons: {
                        cancel: {
                            text: 'Batal',
                            visible: true,
                            className: 'btn btn-danger',
                            closeModal: true,
                        },
                        confirm: {
                            text: 'Ya, hapus!',
                            visible: true,
                            className: 'btn btn-primary',
                            closeModal: true,
                        }
                    },
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush

@push('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endpush
