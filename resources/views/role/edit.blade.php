@extends('layout.main')
@section('main')
    <section class="section">
        <div class="section-header" style="">
            <h1>Tambah Role</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Peran</a></div>
                <div class="breadcrumb-item">Dashboard</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card shadow-sm" style="">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Tambah Peran</h5>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="card-body">
                    <form action="{{ route('role.update', $role->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ old('name', $role->name) }}" placeholder="Masukkan nama">
                        </div>
                        @foreach ($permission as $item)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="permissions[]"
                                    value="{{ $item->id }}"
                                    {{ in_array($item->id, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                                <label class="form-check-label">{{ $item->name }}</label>
                            </div>
                        @endforeach

                        {{-- @foreach ($permission as $item)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="permissions[]"
                                    value="{{ $item->name }}"
                                    {{ in_array($item->id, old('permissions')) ? 'checked' : '' }}>
                                <label class="form-check-label">{{ $item->name }}</label>
                            </div>
                        @endforeach --}}
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-4">Simpan</button>
                            <button class="btn btn-danger" type="reset">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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
