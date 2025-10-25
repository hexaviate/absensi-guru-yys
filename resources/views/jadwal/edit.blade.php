@extends('layout.main')
@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Edit Absensi</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Jadwal</a></div>
                <div class="breadcrumb-item">Dashboard</div>
            </div>
        </div>

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" id="alertMessage">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="alertMessage">
                {{ session('success') }}
            </div>
        @endif

        <div class="section-body">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Form Edit Jadwal</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('jadwal.update', $jadwal->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- tapel_id dropdown -->
                            <div class="col-md-6 mb-3">
                                <label for="tapel" class="form-label">Tahun Pelajaran</label>
                                <input type="text" class="form-control"
                                    value="{{ $tapel ? $tapel->kode : 'Tidak ada tapel aktif' }}" readonly>
                                <input type="hidden" name="tapel_id" value="{{ $tapel ? $tapel->id : '' }}">
                            </div>

                            <!-- instansi radio -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Instansi</label>
                                <div class="selectgroup selectgroup-pills">
                                    @forelse ($instansi as $item)
                                        <label class="selectgroup-item">
                                            <input type="radio" name="instansi_id" value="{{ $item->id }}"
                                                class="selectgroup-input"
                                                {{ $jadwal->instansi_id == $item->id || old('instansi_id') == $item->id ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ $item->nama_instansi }}</span>
                                        </label>
                                    @empty
                                        <p class="text-muted mb-0">Tidak ada instansi</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- user_id pencarian -->
                            <div class="col-md-6 mb-3">
                                <label for="userSearch" class="form-label">Cari User</label>
                                <input type="text" id="userSearch" class="form-control"
                                    placeholder="Pilih instansi terlebih dahulu..." autocomplete="off" disabled
                                    @if (isset($jadwal->user)) value="{{ $jadwal->user->name }} ({{ $jadwal->user->username }})" @endif>
                                <input type="hidden" id="user_id" name="user_id" value="{{ $jadwal->user_id ?? '' }}">
                                <div class="mt-2" id="userResults" style="display:none;">
                                    <!-- Loading Spinner -->
                                    <div id="loadingSpinner" class="text-center py-2" style="display:none;">
                                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <small class="text-muted ms-2">Mencari...</small>
                                    </div>
                                    <!-- Results List -->
                                    <ul class="list-group" id="userList">
                                        <!-- Results akan diisi via AJAX -->
                                    </ul>
                                    <!-- No Results Message -->
                                    <div id="noResults" class="alert alert-info mt-2" style="display:none;">
                                        <small>Tidak ada user yang ditemukan</small>
                                    </div>
                                </div>
                            </div>

                            <!-- hari -->
                            <div class="col-md-6 mb-3">
                                <label for="hari" class="form-label">Hari</label>
                                <select class="form-control" id="hari" name="hari">
                                    <option value="senin" {{ $jadwal->hari == 'senin' ? 'selected' : '' }}>Senin</option>
                                    <option value="selasa" {{ $jadwal->hari == 'selasa' ? 'selected' : '' }}>Selasa</option>
                                    <option value="rabu" {{ $jadwal->hari == 'rabu' ? 'selected' : '' }}>Rabu</option>
                                    <option value="kamis" {{ $jadwal->hari == 'kamis' ? 'selected' : '' }}>Kamis</option>
                                    <option value="sabtu" {{ $jadwal->hari == 'sabtu' ? 'selected' : '' }}>Sabtu</option>
                                    <option value="ahad" {{ $jadwal->hari == 'ahad' ? 'selected' : '' }}>Ahad</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <!-- datang -->
                            <div class="col-md-6 mb-3">
                                <label for="datang" class="form-label">Jam Datang</label>
                                <input type="time" id="datang" name="datang" class="form-control"
                                    value="{{ $jadwal->datang ? \Carbon\Carbon::parse($jadwal->datang)->format('H:i') : '' }}">
                            </div>

                            <!-- pulang -->
                            <div class="col-md-6 mb-3">
                                <label for="pulang" class="form-label">Jam Pulang</label>
                                <input type="time" id="pulang" name="pulang" class="form-control"
                                    value="{{ $jadwal->pulang ? \Carbon\Carbon::parse($jadwal->pulang)->format('H:i') : '' }}">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end" style="gap: 10px;">
                            <button type="submit" class="btn btn-primary px-4">Update</button>
                            <a href="{{ route('jadwal.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const userSearch = document.getElementById("userSearch");
            const userResults = document.getElementById("userResults");
            const userList = document.getElementById("userList");
            const loadingSpinner = document.getElementById("loadingSpinner");
            const noResults = document.getElementById("noResults");
            const userIdInput = document.getElementById("user_id");
            const instansiRadios = document.querySelectorAll('input[name="instansi_id"]');

            let searchTimer;
            let currentIndex = -1;

            // Auto-enable user search jika hanya ada 1 instansi
            if (instansiRadios.length === 1) {
                const singleInstansi = instansiRadios[0];
                if (singleInstansi.checked) {
                    userSearch.disabled = false;
                    userSearch.placeholder = "Ketik nama user (min. 2 karakter)...";

                    // Auto-focus hanya jika field user masih kosong (mode create)
                    // Tidak auto-focus jika sudah ada data user (mode edit)
                    if (!userIdInput.value || userIdInput.value === '') {
                        userSearch.focus();
                    }
                }
            }

            // Enable/Disable user search berdasarkan instansi yang dipilih
            instansiRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.checked) {
                        userSearch.disabled = false;
                        userSearch.placeholder = "Ketik nama user (min. 2 karakter)...";
                        // userSearch.focus();

                        // Reset pencarian sebelumnya
                        resetSearch();
                    }
                });
            });

            // Event listener untuk pencarian dengan debouncing
            userSearch.addEventListener("input", function(e) {
                clearTimeout(searchTimer);
                const searchTerm = this.value.trim();

                if (searchTerm.length >= 2) {
                    searchTimer = setTimeout(() => {
                        searchUsers(searchTerm);
                    }, 500); // Debounce 500ms
                } else {
                    hideResults();
                }
            });

            // Keyboard navigation hanya untuk dropdown
            userSearch.addEventListener("keydown", function(e) {
                if (userResults.style.display === "none") return;

                const visibleItems = userList.querySelectorAll("li:not([style*='display: none'])");

                if (e.key === "ArrowDown") {
                    e.preventDefault();
                    moveSelection(1, visibleItems);
                } else if (e.key === "ArrowUp") {
                    e.preventDefault();
                    moveSelection(-1, visibleItems);
                } else if (e.key === "Enter") {
                    e.preventDefault();
                    if (currentIndex >= 0 && visibleItems[currentIndex]) {
                        selectUser(visibleItems[currentIndex]);
                    }
                } else if (e.key === "Escape") {
                    hideResults();
                }
            });

            // Function untuk melakukan pencarian via AJAX
            function searchUsers(searchTerm) {
                const selectedInstansi = document.querySelector('input[name="instansi_id"]:checked');

                if (!selectedInstansi) {
                    alert('Pilih instansi terlebih dahulu');
                    return;
                }

                // Show loading
                showLoading();

                // Buat URL untuk AJAX request
                const url =
                    `/search-users?instansi=${selectedInstansi.value}&search=${encodeURIComponent(searchTerm)}`;

                fetch(url, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                            // Hapus CSRF token karena belum ada auth
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            // Log response untuk debugging
                            console.error('Response status:', response.status);
                            console.error('Response statusText:', response.statusText);
                            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        hideLoading();
                        if (data.success) {
                            displayResults(data.users || []);
                        } else {
                            showError(data.message || 'Terjadi kesalahan');
                        }
                    })
                    .catch(error => {
                        hideLoading();
                        console.error('Error:', error);
                        showError('Terjadi kesalahan saat mencari user: ' + error.message);
                    });
            }

            // Function untuk menampilkan hasil pencarian
            function displayResults(users) {
                userList.innerHTML = '';
                currentIndex = -1;

                if (users.length === 0) {
                    showNoResults();
                    return;
                }

                hideNoResults();

                users.forEach((user, index) => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item list-group-item-action';
                    li.style.cursor = 'pointer';
                    li.setAttribute('data-user-id', user.id); // untuk AJAX
                    li.setAttribute('value', user.id); // untuk kompatibilitas script lama
                    li.textContent = user.name;

                    // Event listener untuk klik
                    li.addEventListener('click', function() {
                        selectUser(this);
                    });

                    // Hover effect
                    li.addEventListener('mouseenter', function() {
                        removeAllHighlights();
                        this.classList.add('active');
                        currentIndex = index;
                    });

                    userList.appendChild(li);
                });

                showResults();
            }

            // Function untuk memilih user
            function selectUser(element) {
                const userId = element.getAttribute('data-user-id');
                const userName = element.textContent;

                userSearch.value = userName;
                userIdInput.value = userId;
                hideResults();
                currentIndex = -1;
            }

            // Navigation functions
            function moveSelection(step, items) {
                if (items.length === 0) return;

                removeAllHighlights();

                currentIndex += step;

                if (currentIndex < 0) currentIndex = items.length - 1;
                if (currentIndex >= items.length) currentIndex = 0;

                items[currentIndex].classList.add('active');
            }

            function removeAllHighlights() {
                userList.querySelectorAll('li').forEach(item => {
                    item.classList.remove('active');
                });
            }

            // Display control functions
            function showResults() {
                userResults.style.display = 'block';
            }

            function hideResults() {
                userResults.style.display = 'none';
            }

            function showLoading() {
                loadingSpinner.style.display = 'block';
                noResults.style.display = 'none';
                showResults();
            }

            function hideLoading() {
                loadingSpinner.style.display = 'none';
            }

            function showNoResults() {
                noResults.style.display = 'block';
                showResults();
            }

            function hideNoResults() {
                noResults.style.display = 'none';
            }

            function showError(message) {
                userList.innerHTML = `<li class="list-group-item text-danger">${message}</li>`;
                showResults();
            }

            function resetSearch() {
                userSearch.value = '';
                userIdInput.value = '';
                hideResults();
                userList.innerHTML = '';
                currentIndex = -1;
            }

            // Hide results ketika klik di luar
            document.addEventListener('click', function(e) {
                if (!userSearch.contains(e.target) && !userResults.contains(e.target)) {
                    hideResults();
                }
            });
        });
    </script>
@endpush
