@extends('layout.main')
@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Tambah Izin</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Izin</a></div>
                <div class="breadcrumb-item">Tambah Izin</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Form Tambah Izin</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('izinCreateOperator') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <!-- Instansi (Auto-selected & Disabled untuk Operator) -->
                            <div class="col-md-6 mb-3">
                                <label for="instansi" class="form-label">Instansi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('instansi_id') is-invalid @enderror"
                                    value="{{ $instansi->nama_instansi ?? 'Tidak ada instansi' }}"
                                    readonly disabled>
                                <input type="hidden" name="instansi_id" value="{{ $instansi->id ?? '' }}">
                                @error('instansi_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- User Search -->
                            <div class="col-md-6 mb-3">
                                <label for="userSearch" class="form-label">Cari User <span class="text-danger">*</span></label>
                                <input type="text" id="userSearch" class="form-control @error('user_id') is-invalid @enderror"
                                    placeholder="Ketik nama user (min. 2 karakter)..."
                                    autocomplete="off"
                                    value="{{ old('user_name') }}">
                                <input type="hidden" id="user_id" name="user_id" value="{{ old('user_id') }}">

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
                                @error('user_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Tanggal -->
                            <div class="col-md-6 mb-3">
                                <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" id="tanggal" name="tanggal"
                                    class="form-control @error('tanggal') is-invalid @enderror"
                                    value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                @error('tanggal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Bukti Izin (Optional) -->
                            <div class="col-md-6 mb-3">
                                <label for="bukti_izin" class="form-label">Bukti Izin <small class="text-muted">(Opsional)</small></label>
                                <input type="file" id="bukti_izin" name="bukti_izin"
                                    class="form-control @error('bukti_izin') is-invalid @enderror"
                                    accept="image/*,.pdf">
                                <small class="form-text text-muted">
                                    Format: JPG, PNG, PDF. Maksimal 2MB
                                </small>
                                @error('bukti_izin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- Preview untuk gambar -->
                                <div id="previewContainer" class="mt-2" style="display:none;">
                                    <img id="imagePreview" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                                    <button type="button" class="btn btn-sm btn-danger mt-2" id="removePreview">
                                        <i class="fas fa-times"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Keterangan -->
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan <span class="text-danger">*</span></label>
                            <textarea id="keterangan" name="keterangan" rows="4"
                                class="form-control @error('keterangan') is-invalid @enderror"
                                placeholder="Masukkan keterangan izin..." required>{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end" style="gap: 10px;">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                            <a href="{{ route('izinIndexOperator') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
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
            const instansiId = document.querySelector('input[name="instansi_id"]').value;

            // File upload preview
            const buktiIzinInput = document.getElementById("bukti_izin");
            const previewContainer = document.getElementById("previewContainer");
            const imagePreview = document.getElementById("imagePreview");
            const removePreview = document.getElementById("removePreview");

            // Date validation
            const tanggal = document.getElementById("tanggal");

            let searchTimer;
            let currentIndex = -1;

            // === USER SEARCH FUNCTIONALITY ===

            // Event listener untuk pencarian dengan debouncing
            userSearch.addEventListener("input", function(e) {
                clearTimeout(searchTimer);
                const searchTerm = this.value.trim();

                if (searchTerm.length >= 2) {
                    searchTimer = setTimeout(() => {
                        searchUsers(searchTerm);
                    }, 500);
                } else {
                    hideResults();
                }
            });

            // Keyboard navigation
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
                if (!instansiId) {
                    alert('Instansi tidak ditemukan');
                    return;
                }

                showLoading();

                const url = `/izin/search-users?instansi=${instansiId}&search=${encodeURIComponent(searchTerm)}`;

                fetch(url, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
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
                    li.setAttribute('data-user-id', user.id);
                    li.textContent = user.display || user.name;

                    li.addEventListener('click', function() {
                        selectUser(this);
                    });

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
                items[currentIndex].scrollIntoView({ block: 'nearest' });
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
                userList.innerHTML = '';
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

            // Hide results ketika klik di luar
            document.addEventListener('click', function(e) {
                if (!userSearch.contains(e.target) && !userResults.contains(e.target)) {
                    hideResults();
                }
            });

            // === FILE UPLOAD PREVIEW ===

            buktiIzinInput.addEventListener('change', function(e) {
                const file = e.target.files[0];

                if (file) {
                    // Validasi ukuran file (2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('Ukuran file maksimal 2MB');
                        buktiIzinInput.value = '';
                        return;
                    }

                    // Preview untuk gambar
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreview.src = e.target.result;
                            imagePreview.style.display = 'block';
                            previewContainer.style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                    } else if (file.type === 'application/pdf') {
                        // Untuk PDF, tampilkan nama file
                        imagePreview.style.display = 'none';
                        previewContainer.innerHTML = `
                            <div class="alert alert-info">
                                <i class="fas fa-file-pdf"></i> ${file.name}
                            </div>
                            <button type="button" class="btn btn-sm btn-danger" id="removePdfPreview">
                                <i class="fas fa-times"></i> Hapus
                            </button>
                        `;
                        previewContainer.style.display = 'block';

                        // Re-attach event listener untuk tombol hapus
                        document.getElementById('removePdfPreview').addEventListener('click', removeBuktiIzin);
                    }
                }
            });

            // Remove preview
            removePreview.addEventListener('click', removeBuktiIzin);

            function removeBuktiIzin() {
                buktiIzinInput.value = '';
                previewContainer.style.display = 'none';
                imagePreview.src = '';
                imagePreview.style.display = 'block';
                // Reset preview container to original state
                previewContainer.innerHTML = `
                    <img id="imagePreview" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                    <button type="button" class="btn btn-sm btn-danger mt-2" id="removePreview">
                        <i class="fas fa-times"></i> Hapus
                    </button>
                `;
                // Re-attach event listener
                document.getElementById('removePreview').addEventListener('click', removeBuktiIzin);
            }

            // Auto dismiss alert after 5 seconds
            const alertMessage = document.getElementById('alertMessage');
            if (alertMessage) {
                setTimeout(() => {
                    alertMessage.style.display = 'none';
                }, 5000);
            }
        });
    </script>
@endpush
