@extends('layout.main')

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Halaman Data Izin</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Izin</a></div>
                <div class="breadcrumb-item">Dashboard</div>
            </div>
        </div>

        <div class="section-body">
            <!-- Filter Card -->
            <div class="card">
                <div class="card-header">
                    <div class="w-100 d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-filter mr-2"></i>Filter & Pencarian
                        </h4>
                        <button class="btn btn-outline-secondary btn-sm" onclick="resetFilters()">
                            <i class="fas fa-undo mr-1"></i>Reset
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- filter status -->
                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" id="statusFilter">
                                    <option value="">Semua Status</option>
                                    <option value="diterima">Disetujui</option>
                                    <option value="belum_diverifikasi">Menunggu</option>
                                    <option value="tidak_diterima">Ditolak</option>
                                </select>
                            </div>
                        </div>

                        <!-- filter jenis instansi -->
                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <label>Jenis Instansi</label>
                                <select class="form-control" id="schoolFilter">
                                    <option value="">Semua Instansi</option>
                                    @php
                                        $instansiTypes = $izin
                                            ->pluck('instansi.nama_instansi')
                                            ->map(function ($nama) {
                                                return substr($nama, 0, strpos($nama, ' ') ?: strlen($nama));
                                            })
                                            ->unique()
                                            ->filter()
                                            ->values();
                                    @endphp
                                    @foreach ($instansiTypes as $instansiType)
                                        <option value="{{ $instansiType }}">{{ $instansiType }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- pencarian -->
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label>Pencarian</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="searchInput"
                                        placeholder="Cari berdasarkan instansi, keterangan...">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" onclick="applyFilters()">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Loading -->
            <div class="text-center" id="loading" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="mt-2">Memuat data...</p>
            </div>

            <!-- Cards Grid -->
            <div class="row" id="cardsContainer">
                @forelse ($izin as $item)
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12 permit-card-wrapper" data-status="{{ $item->status }}"
                        data-instansi="{{ $item->instansi->nama_instansi }}" data-keterangan="{{ $item->keterangan }}"
                        data-id="{{ $item->id }}">
                        <div
                            class="card {{ $item->status == 'diterima'
                                ? 'card-sukes'
                                : ($item->status == 'belum_diverifikasi'
                                    ? 'card-wng'
                                    : 'card-dgr') }}">

                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center w-100">
                                    <div>
                                        <small class="text-muted">#{{ $loop->iteration }}</small>
                                        <h6 class="mb-0 font-weight-bold">{{ $item->instansi->nama_instansi }}</h6>
                                    </div>

                                    <!-- status pill -->
                                    <span
                                        class="badge badge-{{ $item->status == 'diterima' ? 'sukes' : ($item->status == 'belum_diverifikasi' ? 'wng' : 'danger') }}">
                                        <i
                                            class="fas {{ $item->status == 'diterima' ? 'fa-check-circle' : ($item->status == 'belum_diverifikasi' ? 'fa-clock' : 'fa-times-circle') }} mr-1"></i>
                                        {{ $item->status == 'diterima' ? 'Disetujui' : ($item->status == 'belum_diverifikasi' ? 'Menunggu' : 'Ditolak') }}
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <i class="fas fa-calendar-alt text-primary mr-2"></i>
                                    <small>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</small>
                                </div>
                                <div class="mb-2">
                                    <i class="fas fa-info-circle text-primary mr-2"></i>
                                    <small>{{ Str::limit($item->keterangan, 60) }}</small>
                                </div>

                                <!-- Link Lihat Bukti -->
                                <div class="mb-3">
                                    @php
                                        $fileExtension = strtolower(pathinfo($item->bukti_izin, PATHINFO_EXTENSION));
                                    @endphp

                                    @if ($fileExtension === 'pdf')
                                        <small>
                                            <a href="{{ asset('bukti_izin/' . $item->bukti_izin) }}" target="_blank"
                                                class="text-primary">
                                                <i class="fas fa-file-pdf mr-1"></i>Lihat Bukti PDF
                                            </a>
                                        </small>
                                    @else
                                        <small>
                                            <a href="#" class="text-primary" data-toggle="modal"
                                                data-target="#modal-bukti-{{ $item->id }}">
                                                <i class="fas fa-eye mr-1"></i>Lihat Bukti
                                            </a>
                                        </small>
                                    @endif
                                </div>

                                <!-- Tombol berdasarkan status -->
                                @if ($item->status == 'belum_diverifikasi')
                                    <!-- Jika belum diverifikasi: tampilkan tombol Edit dan Hapus -->
                                    <div class="row">
                                        <div class="col-6">
                                            <a href="{{ route('viewIzinEdit', $item->id) }}"
                                                class="btn btn-warning btn-sm btn-block">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </a>
                                        </div>
                                        <div class="col-6">
                                            <button class="btn btn-danger btn-sm btn-block"
                                                onclick="confirmDelete({{ $item->id }})">
                                                <i class="fas fa-trash mr-1"></i>Hapus
                                            </button>
                                        </div>
                                    </div>
                                @elseif ($item->status == 'diterima' || $item->status == 'tidak_diterima')
                                    <!-- Jika sudah diverifikasi (diterima/ditolak): hanya tampilkan tombol Detail -->
                                    <button class="btn btn-outline-primary btn-sm btn-block" data-toggle="modal"
                                        data-target="#modal-detail-{{ $item->id }}">
                                        <i class="fas fa-info-circle mr-1"></i>Detail Izin
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12" id="originalEmpty">
                        <div class="empty-state text-center py-5">
                            <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">Belum ada data izin</h5>
                            <p class="text-muted">Anda belum mengajukan izin apapun</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- ksoosng untuk hasil filter -->
            <div class="text-center py-5" id="emptyState" style="display: none;">
                <i class="fas fa-search text-muted" style="font-size: 3rem;"></i>
                <h5 class="mt-3">Tidak ada data yang ditemukan</h5>
                <p class="text-muted">Coba ubah filter atau kata kunci pencarian</p>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                <nav aria-label="Page navigation">
                    <ul class="pagination" id="pagination">
                        {{-- isi japaskrip --}}
                    </ul>
                </nav>
            </div>
        </div>
    </section>

    <!-- Modal Gae Gamnbar CUIO -->
    @foreach ($izin as $item)
        @php
            $fileExtension = strtolower(pathinfo($item->bukti_izin, PATHINFO_EXTENSION));
        @endphp

        @if ($fileExtension !== 'pdf')
            <div class="modal fade" id="modal-bukti-{{ $item->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-image mr-2"></i>
                                BUKTI IZIN - {{ $item->instansi->nama_instansi }}
                            </h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <div class="mb-3">
                                <span class="badge badge-primary mr-2">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                                </span>
                                <span class="badge badge-info">
                                    <i class="fas fa-building mr-1"></i>
                                    {{ $item->instansi->nama_instansi }}
                                </span>
                                <span class="badge badge-info">
                                    <i class="fas fa-user mr-1"></i>
                                    {{ $item->user->name }}
                                </span>
                            </div>
                            <img src="{{ asset('bukti_izin/' . $item->bukti_izin) }}" class="img-fluid rounded"
                                alt="Bukti Izin" style="max-height: 500px;">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="fas fa-times mr-1"></i> Tutup
                            </button>
                            <a href="{{ asset('bukti_izin/' . $item->bukti_izin) }}" target="_blank"
                                class="btn btn-primary">
                                <i class="fas fa-external-link-alt mr-1"></i> Tab Baru
                            </a>
                            <a href="{{ asset('bukti_izin/' . $item->bukti_izin) }}"
                                download="{{ $item->instansi->nama_instansi }}_{{ $item->user->name }}_{{ $item->tanggal }}.{{ $fileExtension }}"
                                class="btn btn-success">
                                <i class="fas fa-download mr-1"></i> Download
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Modal DETEL -->
        <div class="modal fade" id="modal-detail-{{ $item->id }}" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header text-white" style="background-color: #D14249">
                        <div>
                            <h5 class="modal-title mb-1 d-flex align-items-center">
                                {{-- <i class="fas {{ $item->status == 'diterima'
                                    ? 'fa-check-circle'
                                    : ($item->status == 'belum_diverifikasi'
                                        ? 'fa-clock'
                                        : 'ion-close-round') }} mr-2 text-white"
                                    style="font-size: 1em;"></i> --}}
                                <span class="text-white" style="font-size: 1em;">
                                    {{ $item->status == 'diterima'
                                        ? 'IZIN DISETUJUI'
                                        : ($item->status == 'belum_diverifikasi'
                                            ? 'MENUNGGU PERSETUJUAN'
                                            : 'IZIN DITOLAK') }}
                                </span>
                            </h5>
                        </div>
                        <button type="button" class="close text-white mb-1" data-dismiss="modal">
                            <span class="ion-close-round"></span>
                        </button>
                    </div>
                    <div class="modal-body p-0">
                        <!-- Card Utama -->
                        <div class="card m-3 border-0 shadow-sm">
                            <div class="card-body">

                                <!-- Judul -->
                                <h5 class="font-weight-bold mb-1 text-center">{{ $item->instansi->nama_instansi }}</h5>
                                <p class="text-muted text-center mb-4">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                                </p>

                                <!-- Nomor Izin -->
                                <div class="d-flex align-items-center py-2 border-bottom">
                                    <ion-icon name="pricetag-outline" class="text-primary mr-3"
                                        size="large"></ion-icon>
                                    <div>
                                        <small class="text-muted">Nomor Izin</small>
                                        <div class="font-weight-bold">{{ $item->id }}</div>
                                    </div>
                                </div>

                                <!-- Instansi -->
                                <div class="d-flex align-items-center py-2 border-bottom">
                                    <ion-icon name="business-outline" class="text-info mr-3" size="large"></ion-icon>
                                    <div>
                                        <small class="text-muted">Instansi</small>
                                        <div class="font-weight-bold">{{ $item->instansi->nama_instansi }}</div>
                                    </div>
                                </div>

                                <!-- Keterangan -->
                                <div class="d-flex align-items-start py-2 border-bottom">
                                    <ion-icon name="chatbubble-ellipses-outline" class="text-secondary mr-3 mt-1"
                                        size="large"></ion-icon>
                                    <div>
                                        <small class="text-muted">Keterangan</small>
                                        <p class="mb-0 mt-1">{{ $item->keterangan }}</p>
                                    </div>
                                </div>

                                @if ($item->status == 'tidak_diterima')
                                    @if ($item->keterangan_ditolak != null)
                                        <div class="d-flex align-items-start py-2">
                                            <ion-icon name="close-circle-outline" class="text-danger mr-3 mt-1"
                                                size="large"></ion-icon>
                                            <div>
                                                <small class="text-muted">Keterangan Ditolak</small>
                                                <p
                                                    class="mb-0 mt-1 {{ $item->keterangan_ditolak ? 'text-dark' : 'text-muted' }}">
                                                    {{ $item->keterangan_ditolak ?: 'Tidak ada keterangan penolakan izin' }}
                                                </p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="d-flex align-items-start py-2">
                                            <ion-icon name="close-circle-outline" class="text-danger mr-3 mt-1"
                                                size="large"></ion-icon>
                                            <div>
                                                <small class="text-muted">Keterangan Ditolak</small>
                                                <p class="mb-0 mt-1">Tidak ada keterangan penolakan izin
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    {{-- kosong karena tidak ditolak izinnya  --}}
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Tutup
                        </button>
                        @if ($item->status == 'belum_diverifikasi')
                            <a href="{{ route('viewIzinEdit', $item->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit mr-1"></i> Edit Izin
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('style')
    <style>
        .permit-card-wrapper {
            margin-bottom: 1rem;
        }

        .card-header button {
            margin-left: auto !important;
        }


        .card-header {
            display: flex !important;
            flex-direction: row !important;
            justify-content: space-between !important;
            align-items: center !important;
        }


        .card-header .card-header-action {
            margin-left: auto;
        }
    </style>
@endpush

@push('script')
    <script>
        // Global variables
        let currentPage = 1;
        const itemsPerPage = 6;
        let allCards = [];
        let filteredCards = [];

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            initializeCards();
            setupEventListeners();
            renderPagination();
        });

        function initializeCards() {
            allCards = Array.from(document.querySelectorAll('.permit-card-wrapper[data-id]'));
            filteredCards = [...allCards];
            showPage(1);
        }

        function setupEventListeners() {
            document.getElementById('statusFilter').addEventListener('change', applyFilters);
            document.getElementById('schoolFilter').addEventListener('change', applyFilters);
            document.getElementById('searchInput').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    applyFilters();
                }
            });

            // Auto hide alerts
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    if (alert && alert.parentNode) {
                        $(alert).fadeOut();
                    }
                }, 5000);
            });
        }

        function applyFilters() {
            const statusFilter = document.getElementById('statusFilter').value;
            const schoolFilter = document.getElementById('schoolFilter').value;
            const searchInput = document.getElementById('searchInput').value.toLowerCase();

            showLoading();

            setTimeout(() => {
                filteredCards = allCards.filter(card => {
                    const status = card.dataset.status;
                    const instansi = card.dataset.instansi;
                    const keterangan = card.dataset.keterangan;

                    const matchStatus = !statusFilter || status === statusFilter;
                    const matchSchool = !schoolFilter || instansi.includes(schoolFilter);
                    const matchSearch = !searchInput ||
                        instansi.toLowerCase().includes(searchInput) ||
                        keterangan.toLowerCase().includes(searchInput) ||
                        card.dataset.id.toLowerCase().includes(searchInput);

                    return matchStatus && matchSchool && matchSearch;
                });

                currentPage = 1;
                showPage(currentPage);
                renderPagination();
                hideLoading();
            }, 300);
        }

        function resetFilters() {
            document.getElementById('statusFilter').value = '';
            document.getElementById('schoolFilter').value = '';
            document.getElementById('searchInput').value = '';

            filteredCards = [...allCards];
            currentPage = 1;
            showPage(currentPage);
            renderPagination();
        }

        function showPage(page) {
            const startIndex = (page - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const pageCards = filteredCards.slice(startIndex, endIndex);

            // Hide all cards
            allCards.forEach(card => {
                card.style.display = 'none';
            });

            const emptyState = document.getElementById('emptyState');
            const originalEmpty = document.getElementById('originalEmpty');

            if (filteredCards.length === 0) {
                if (originalEmpty) originalEmpty.style.display = 'none';
                emptyState.style.display = 'block';
            } else {
                emptyState.style.display = 'none';
                if (originalEmpty && allCards.length === 0) {
                    originalEmpty.style.display = 'block';
                } else if (originalEmpty) {
                    originalEmpty.style.display = 'none';
                }

                // Show current page cards
                pageCards.forEach(card => {
                    card.style.display = 'block';
                });
            }
        }

        function renderPagination() {
            const totalPages = Math.ceil(filteredCards.length / itemsPerPage);
            const pagination = document.getElementById('pagination');

            if (totalPages <= 1) {
                pagination.innerHTML = '';
                return;
            }

            let paginationHTML = `
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="javascript:void(0)" onclick="changePage(${currentPage - 1})">
                <i class="fas fa-chevron-left"></i>
            </a>
        </li>
    `;

            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                    paginationHTML += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="javascript:void(0)" onclick="changePage(${i})">${i}</a>
                </li>
            `;
                } else if (i === currentPage - 2 || i === currentPage + 2) {
                    paginationHTML += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
            }

            paginationHTML += `
        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="javascript:void(0)" onclick="changePage(${currentPage + 1})">
                <i class="fas fa-chevron-right"></i>
            </a>
        </li>
    `;

            pagination.innerHTML = paginationHTML;
        }

        function changePage(page) {
            const totalPages = Math.ceil(filteredCards.length / itemsPerPage);
            if (page < 1 || page > totalPages) return;

            currentPage = page;
            showLoading();

            setTimeout(() => {
                showPage(currentPage);
                renderPagination();
                hideLoading();
                $('html, body').animate({
                    scrollTop: 0
                }, 300);
            }, 200);
        }

        function showLoading() {
            document.getElementById('loading').style.display = 'block';
            document.getElementById('cardsContainer').style.opacity = '0.5';
        }

        function hideLoading() {
            document.getElementById('loading').style.display = 'none';
            document.getElementById('cardsContainer').style.opacity = '1';
        }
    </script>
@endpush
