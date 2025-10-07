@extends('layout.main')

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Halaman Management Instansi</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Instansi</a></div>
                <div class="breadcrumb-item">Dashboard</div>
            </div>
        </div>

        <div class="section-body">
            <div class="shadow pb-2">
                <a href="{{ route('instansi.create') }}" class="btn btn-primary m-2 shadow">Tambah Data Instansi</a>
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
                                            <th>Nama Instansi</th>
                                            <th>Kepala Instansi</th>
                                            <th>Alamat Instansi</th>
                                            <th>Telp Instansi</th>
                                            <th>Admin</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($instansi as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->nama_instansi }}</td>
                                                <td>{{ $item->kepala_instansi }}</td>
                                                <td>{{ $item->alamat_instansi }}</td>
                                                <td>{{ $item->telp_instansi }}</td>
                                                <td>
                                                    @foreach ($item->user as $user)
                                                        {{ $user->name }}
                                                        @if (!$loop->last)
                                                            ,
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td class="d-flex gap-2">
                                                    <a href="{{ route('instansi.edit', $item->id) }}"
                                                        class="btn btn-warning">Edit</a>
                                                    <form action="{{ route('instansi.destroy', $item->id) }}" method="POST"
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

@push('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                responsive: true
            });
        });
    </script>
@endpush
