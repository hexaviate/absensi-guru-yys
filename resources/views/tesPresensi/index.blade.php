<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Halaman Absensi</title>

    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome 6.6.0 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: linear-gradient(135deg, #6777EF 0%, #6777EF 100%) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .main-container {
            margin-top: 100px;
            padding: 20px;
        }

        .attendance-card {
            background: white;
            border-radius: 25px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: 1px solid #e0e0e0;
            padding: 30px;
            max-width: 500px;
            margin: 0 auto;
        }

        .video-container {
            position: relative;
            margin-bottom: 25px;
        }

        #video {
            width: 100%;
            height: 350px;
            border-radius: 20px;
            object-fit: cover;
            background: #f8f9fa;
            border: 3px solid #e0e0e0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            transform: scaleX(-1);
        }

        #preview {
            width: 100%;
            height: 350px;
            border-radius: 20px;
            object-fit: cover;
            border: 3px solid #e0e0e0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .btn-custom {
            border-radius: 15px;
            padding: 12px 20px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #6777EF 0%, #6777EF 100%);
            color: white;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.4);
        }

        .btn-success-custom {
            background: linear-gradient(135deg, #6777EF 0%, #6777EF 100%);
            color: white;
        }

        .btn-success-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.4);
        }

        .btn-danger-custom {
            background: linear-gradient(135deg, #6777EF 0%, #6777EF 100%);
            color: white;
        }

        .btn-danger-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.4);
        }

        .user-info {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 15px;
            padding: 20px;
            margin-top: 25px;
            text-align: center;
        }

        .status-badge {
            background: linear-gradient(135deg, #6777EF 0%, #6777EF 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            display: inline-block;
            margin-top: 10px;
        }

        .modal-content {
            border: none;
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(135deg, #6777EF 0%, #6777EF 100%);
            color: white;
            border: none;
        }

        .tutorial-step {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid #6777EF;
        }

        .step-number {
            background: #6777EF;
            color: white;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 15px;
        }

        .navbar-toggler {
            border: none;
            color: white;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        @media (max-width: 576px) {
            .main-container {
                margin-top: 80px;
                padding: 15px;
            }

            .attendance-card {
                padding: 20px;
            }

            #video,
            #preview {
                height: 280px;
            }
        }
    </style>
</head>

<body>
    <!-- Header dengan gradien modern -->
    <nav class="navbar navbar-expand-lg fixed-top shadow p-2">
        <div class="container-fluid">
            <div class="row w-100 text-center align-items-center">

                <!-- kiri -->
                <div class="col-4 text-start">
                    {{-- <i class="fas fa-camera fa-lg text-white"></i> --}}
                </div>

                <!-- tengah -->
                <div class="col-4">
                    <h5 class="text-white mb-0 fw-bold">Presensi Wajah</h5>
                </div>

                <!-- kanan -->
                <div class="col-4 text-end">
                    <i class="fas fa-circle-info fa-lg text-white" style="cursor: pointer;" data-bs-toggle="modal"
                        data-bs-target="#tutorialModal"></i>
                </div>
            </div>
        </div>
    </nav>

    <!-- Konten Utama -->
    <div class="main-container">
        <div class="attendance-card">

            <!-- Video Preview -->
            <div class="video-container text-center">
                <video id="video" autoplay playsinline muted style="object-fit: cover; display: none;"></video>
                <img id="preview" alt="Hasil Selfie" style="display: none; object-fit: cover;" />
            </div>

            <!-- Tombol Kontrol -->
            <div class="row g-3 mb-4">
                <div class="col-12 col-md-4">
                    <button class="btn btn-custom btn-primary-custom w-100" id="switchCamera">
                        <i class="fas fa-sync-alt me-2"></i>Ganti Kamera
                    </button>
                </div>
                <div class="col-12 col-md-4">
                    <button class="btn btn-custom btn-success-custom w-100" id="takePhoto">
                        <i class="fas fa-camera me-2"></i>Ambil Presensi
                    </button>
                </div>
                <div class="col-12 col-md-4">
                    <button class="btn btn-custom btn-danger-custom w-100" id="backButton">
                        <i class="fas fa-arrow-left me-2"></i>Kembali Dashboard
                    </button>
                </div>
            </div>

            {{-- Ini Muncul Ketika Absensi Sudah Berhasil --}}
            <div class="user-info text-center">
                <div class="row">
                    <div class="col-12">
                        <!-- nama & status -->
                        <h6 class="mb-2 fw-bold text-dark">
                            <i class="fas fa-user me-2"></i>Nama: Aziz Rahman
                        </h6>
                        <div class="status-badge">
                            <i class="fas fa-clock me-1"></i>Status: Hadir (07:55 WIB)
                        </div>

                        <!-- info tambahan -->
                        <div class="mt-3 mb-3">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Anda Sudah Absensi (datang / pulang) Hari Ini
                            </small>
                        </div>

                        <!-- lokasi -->
                        <div class=" p-3 text-center mb-2">
                            <div class="fw-bold text-secondary mb-1"><i
                                    class="fa-solid fa-location-dot me-1 text-danger"></i> Lokasi</div>
                            <div id="lokasi" class="text-warp small"></div>
                        </div>

                        <!-- perangkat -->
                        <div class=" p-3 text-center">
                            <div class="fw-bold text-secondary mb-1"><i class="fa-solid fa-laptop me-1 text-black"></i>
                                Info Perangkat</div>
                            <div id="device-info" class="text-wrap small text-center">
                                <div id="perangkat" class="small text-wrap"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal Tutorial -->
    <div class="modal fade" id="tutorialModal" tabindex="-1" aria-labelledby="tutorialModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="tutorialModalLabel">
                        <i class="fas fa-graduation-cap me-2"></i>Petunjuk Absensi Wajah
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="tutorial-step">
                        <div class="d-flex align-items-center">
                            <span class="step-number p-3">1</span>
                            <div>
                                <h6 class="mb-1 fw-bold">Posisikan kamera</h6>
                                <p class="mb-0 text-muted">Pastikan kamera perangkat dalam keadaan bersih dan aplikasi
                                    memiliki izin akses kamera.</p>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-step">
                        <div class="d-flex align-items-center">
                            <span class="step-number p-3">2</span>
                            <div>
                                <h6 class="mb-1 fw-bold">Tempatkan wajah</h6>
                                <p class="mb-0 text-muted">Tempatkan wajah Anda di area yang cukup terang, lalu arahkan
                                    ke kamera dan posisikan di tengah layar.</p>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-step">
                        <div class="d-flex align-items-center">
                            <span class="step-number p-3">3</span>
                            <div>
                                <h6 class="mb-1 fw-bold">Ambil Absensi</h6>
                                <p class="mb-0 text-muted">Tahan posisi beberapa saat hingga proses verifikasi selesai,
                                    jangan menggerakkan perangkat terlalu banyak.</p>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-step">
                        <div class="d-flex align-items-center">
                            <span class="step-number p-3">4</span>
                            <div>
                                <h6 class="mb-1 fw-bold">Lapor</h6>
                                <p class="mb-0 text-muted">Jika wajah belum berhasil terdeteksi, coba ulangi dengan
                                    pencahayaan lebih baik atau posisi wajah yang jelas. Jika tetap gagal, silakan
                                    hubungi operator instansi Anda.</p>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-step">
                        <div class="d-flex align-items-center">
                            <span class="step-number p-3">5</span>
                            <div>
                                <h6 class="mb-1 fw-bold">Berhasil</h6>
                                <p class="mb-0 text-muted">Setelah verifikasi berhasil, sistem akan menampilkan lokasi,
                                    perangkat, waktu, serta status absensi Anda.</p>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-step">
                        <div class="d-flex align-items-center">
                            <span class="step-number p-3">6</span>
                            <div>
                                <h6 class="mb-1 fw-bold">Catatan</h6>
                                <p class="mb-0 text-muted">Data wajah hanya digunakan untuk kebutuhan verifikasi
                                    absensi
                                    dan tidak disalahgunakan.</p>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mt-4">
                        <i class="fas fa-lightbulb me-2"></i>
                        <strong>Tips:</strong> Gunakan pencahayaan yang cukup dan pastikan tidak ada bayangan menutupi
                        wajah untuk hasil terbaik.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-custom btn-primary-custom" data-bs-dismiss="modal">
                        <i class="fas fa-check me-2"></i>Mengerti
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JQuery terbaru -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap 5.3.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let stream = null;
        let currentCamera = 0;
        let cameras = [];

        // Inisialisasi kamera saat halaman dimuat
        $(document).ready(function() {
            initCamera();
        });

        // Fungsi untuk menginisialisasi kamera
        async function initCamera() {
            try {
                // Dapatkan daftar kamera yang tersedia
                const devices = await navigator.mediaDevices.enumerateDevices();
                cameras = devices.filter(device => device.kind === 'videoinput');

                if (cameras.length === 0) {
                    alert('Tidak ada kamera yang tersedia!');
                    return;
                }

                // Mulai dengan kamera pertama
                await startCamera(cameras[currentCamera].deviceId);
            } catch (error) {
                console.error('Error accessing camera:', error);
                alert('Error mengakses kamera: ' + error.message);
            }
        }

        // Fungsi untuk memulai kamera
        async function startCamera(deviceId) {
            try {
                // Hentikan stream sebelumnya jika ada
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }

                // Mulai stream baru
                stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        deviceId: deviceId ? {
                            exact: deviceId
                        } : undefined
                    }
                });

                document.getElementById('video').srcObject = stream;
            } catch (error) {
                console.error('Error starting camera:', error);
                alert('Error memulai kamera: ' + error.message);
            }
        }

        // Event listener untuk ganti kamera
        $('#switchCamera').click(async function() {
            if (cameras.length <= 1) {
                alert('Hanya ada satu kamera yang tersedia');
                return;
            }

            currentCamera = (currentCamera + 1) % cameras.length;
            await startCamera(cameras[currentCamera].deviceId);
        });

        // Event listener untuk ambil foto
        $('#takePhoto').click(function() {
            const video = document.getElementById('video');
            const preview = document.getElementById('preview');

            // Buat canvas untuk mengambil screenshot
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            const ctx = canvas.getContext('2d');
            ctx.scale(-1, 1); // Flip horizontal untuk mirror effect
            ctx.drawImage(video, -canvas.width, 0);

            // Konversi ke data URL
            const dataURL = canvas.toDataURL('image/png');

            // Tampilkan preview
            preview.src = dataURL;
            preview.style.display = 'block';
            video.style.display = 'none';

            // Simulasi proses absensi
            setTimeout(() => {
                alert('✅ Absensi berhasil! Foto telah tersimpan dan data absensi telah diupdate.');

                // Kembali ke tampilan kamera setelah 2 detik
                setTimeout(() => {
                    preview.style.display = 'none';
                    video.style.display = 'block';
                }, 2000);
            }, 1000);
        });

        // Event listener untuk tombol kembali
        $('#backButton').click(function() {
            if (confirm('Yakin ingin kembali ke dashboard?')) {
                // Hentikan stream kamera
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }
                alert('Kembali ke dashboard...');
                // Di sini bisa redirect ke halaman dashboard
                // window.location.href = 'dashboard.html';
            }
        });

        // Cleanup saat halaman ditutup
        window.addEventListener('beforeunload', () => {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
        });
    </script>
</body>

</html>
