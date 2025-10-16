@extends('layout.main')
@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Halaman Management Peran</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Peran</a></div>
                <div class="breadcrumb-item">Dashboard</div>
            </div>
        </div>

        <div class="section-body">
            <div class="shadow pb-2">
                <a href="{{ route('role.create') }}" class="btn btn-primary m-2 shadow">Tambah Data peran</a>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <h4>Data Peran</h4>
                        </div>
                        <div class="card-body">
                            @if ($role->isEmpty())
                                <div class="d-flex justify-content-center align-items-center" style="height: 50px;">
                                    <span class="font-weight-bold">Data Jadwal Kosong</span>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-bordered table-md" id="example">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @forelse ($role as $item)
                                                <tr>
                                                    <td class="">{{ $loop->iteration }}</td>
                                                    <td>{{ Str::of($item->name)->replace('_', ' ')->title() }}</td>
                                                    <td class="d-flex">
                                                        <a href="{{ route('role.edit', $item->id) }}"
                                                            class="btn btn-warning">Edit</a>
                                                        <form action="{{ route('role.destroy', $item->id) }}" method="POST"
                                                            class="delete-form d-inline">
                                                            @csrf
                                                            @method('delete')
                                                            <button type="button"
                                                                class="btn btn-danger mx-2 delete-btn">Hapus</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <p>Data Peran Kosong, Perlu di Isi</p>
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
