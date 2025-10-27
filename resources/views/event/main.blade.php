@extends('layout.main')

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Halaman Management Acara</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Acara</a></div>
                <div class="breadcrumb-item">Dashboard</div>
            </div>
        </div>

        <div class="section-body">
            <div class="shadow pb-2">
                <a href="{{ route('createEventOperator') }}" class="btn btn-primary m-2 shadow">Tambah Data Event </a>
            </div>

            @hasanyrole('admin_yayasan')..
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header">
                                <h4>Data Acara</h4>
                            </div>
                            <div class="card-body">
                                @if ($semuaEvent->isEmpty())
                                    <div class="d-flex justify-content-center align-items-center" style="height: 50px;">
                                        <span class="font-weight.-bold">Data Acara Kosong</span>
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table id="example" class="table table-striped table-bordered table-md">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Tahun Pelajaran</th>
                                                    <th>Nama Instansi</th>
                                                    <th>Acara</th>
                                                    <th>Keterangan</th>
                                                    <th>Mulai</th>
                                                    <th>Selesai</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($semuaEvent as $item)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $item->tapel->kode }}</td>
                                                        <td>{{ $item->instansi->nama_instansi }}</td>
                                                        <td>{{ $item->nama_event }}</td>
                                                        <td>{{ $item->keterangan }}</td>
                                                        <td>{{ $item->tanggal_mulai }}</td>
                                                        <td>{{ $item->tanggal_selesai }}</td>
                                                        <td class="d-flex">
                                                            <a href="{{ route('editEventOperator', $item->id) }}"
                                                                class="btn btn-warning mx-2">Edit</a>
                                                            <form action="{{ route('deleteEventOperator', $item->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('delete')
                                                                <button type="submit"
                                                                    class="btn btn-danger delete-btn">Hapus</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center">Data Hari Libur Kosong</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endhasanyrole

            @hasanyrole('operator_instansi')
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header">
                                <h4>Data Hari Libur</h4>
                            </div>
                            <div class="card-body">
                                @if ($event->isEmpty())
                                    <div class="d-flex justify-content-center align-items-center" style="height: 50px;">
                                        <span class="font-weight-bold">Data Jadwal Kosong</span>
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table id="example" class="table table-striped table-bordered table-md">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Tahun Pelajaran</th>
                                                    <th>Nama Instansi</th>
                                                    <th>Acara</th>
                                                    <th>Keterangan</th>
                                                    <th>Mulai</th>
                                                    <th>Selesai</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($event as $item)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $item->tapel->kode }}</td>
                                                        <td>{{ $item->instansi->nama_instansi }}</td>
                                                        <td>{{ $item->nama_event }}</td>
                                                        <td>{{ $item->keterangan }}</td>
                                                        <td>{{ $item->tanggal_mulai }}</td>
                                                        <td>{{ $item->tanggal_selesai }}</td>
                                                        <td class="d-flex">
                                                            <a href="{{ route('editEventOperator', $item->id) }}"
                                                                class="btn btn-warning mx-2">Edit</a>
                                                            <form action="{{ route('deleteEventOperator', $item->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('delete')
                                                                <button type="submit"
                                                                    class="btn btn-danger delete-btn">Hapus</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center">Data Hari Libur Kosong</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endhasanyrole
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
