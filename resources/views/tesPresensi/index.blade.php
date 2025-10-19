<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Halaman Absensi</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">

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
            width: 100vw;
            /* full layar */
            height: 100vh;
            /* full layar */
            overflow: hidden;
            margin: 0;
            padding: 0;
            background: #000;
        }

        /* elemen video & preview */
        #video,
        #preview {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* penuh tanpa space */
            object-position: center;
            display: none;
            /* default hidden */
        }

        /* aktifkan elemen */
        #video.active,
        #preview.active {
            display: block;
        }

        /* khusus mirror kamera */
        #video {
            transform: scaleX(-1);
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
            <div class="video-container text-center position-relative"
                style="width: 100%; height: 300px; overflow: hidden; border-radius: 12px;">

                <!-- placeholder awal -->
                <div id="placeholder"
                    style="position: absolute; top:0; left:0; width:100%; height:100%;
        background: #f5f5f5; display:flex; align-items:center; justify-content:center; z-index:1;">
                    <i class="fas fa-user fa-6x text-secondary"></i>
                </div>

                <!-- video -->
                <video id="video" autoplay playsinline muted
                    style="width:100%; height:100%; object-fit:cover; transform:scaleX(-1);
        display:none; position:relative; z-index:2;"></video>

                <!-- hasil selfie -->
                <img id="preview" alt="Hasil Selfie"
                    style="width:100%; height:100%; object-fit:cover; transform:scaleX(-1);
        display:none; position:relative; z-index:3;" />
            </div>



            <!-- Status Deteksi Wajah -->
            <div id="status-wajah" class="text-center text-danger fw-semibold mb-3 text-wrap"
                style="font-size: 15px; display:none;">
                Mendeteksi wajah...
            </div>

            <!-- Info Matching Wajah -->
            <div id="matching-info" class="text-center text-muted small mb-3 text-wrap" style="display:none;"></div>

            <!-- Status Utama - ELEMENT YANG HILANG -->
            {{-- <div class="alert alert-success text-center fw-semibold mb-4" role="alert" id="status"
                style="display:none;">
                Status presensi akan muncul di sini
            </div> --}}

            <!-- Tombol Kontrol -->
            <div class="row g-3 mb-4">
                <div class="col-12 col-md-4">
                    <button class="btn btn-custom btn-primary-custom w-100" id="switchCamera">
                        <i class="fas fa-sync-alt me-2"></i>Ganti Kamera
                    </button>
                </div>
                <div class="col-12 col-md-4">
                    <button class="btn btn-custom btn-success-custom w-100" id="takePhoto" onclick="ambilPresensi()">
                        <i class="fas fa-camera me-2"></i>Ambil Presensi
                    </button>
                </div>
                <div class="col-12 col-md-4">
                    @hasrole('admin_yayasan')
                        <a href="{{ route('adminYysDashboard') }}" class="btn btn-custom btn-danger-custom w-100">
                            <i class="fas fa-arrow-left me-2"></i>Kembali Dashboard
                        </a>
                    @else
                        @hasrole('operator_instansi')
                            <a href="{{ route('operatorDashboard') }}" class="btn btn-custom btn-danger-custom w-100">
                                <i class="fas fa-arrow-left me-2"></i>Kembali Dashboard
                            </a>
                        @else
                            @hasanyrole('tenaga_pendidik|tenaga_kependidikan')
                                <a href="{{ route('userDashboard') }}" class="btn btn-custom btn-danger-custom w-100">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali Dashboard
                                </a>
                            @endhasrole
                        @endhasrole
                    @endhasrole
                </div>



                {{-- Status Presensi yang Muncul Setelah Berhasil --}}
                {{-- Status Container Lengkap yang Muncul Setelah Presensi --}}
                <div class="user-info text-center" id="status" style="display:none;">
                    <div class="row">
                        <div class="col-12">
                            <!-- Status Message Alert - BARU DITAMBAHKAN -->
                            <div class="alert alert-success text-center fw-semibold mb-3" role="alert"
                                id="status-message" style="display:none;">
                                Status akan muncul di sini
                            </div>

                            <!-- nama & status -->
                            <h6 class="mb-2 fw-bold text-dark">
                                <i class="fas fa-user me-2"></i>Nama: {{ $user->name }}
                            </h6>

                            <!-- Info Presensi -->
                            <small class="text-muted" id="info-presensi" style="display:none;">

                            </small>

                            <!-- lokasi -->
                            <div class="p-3 text-center mb-2">
                                <div class="fw-bold text-secondary mb-1">
                                    <i class="fa-solid fa-location-dot me-1 text-danger"></i> Lokasi
                                </div>
                                <div id="lokasi" class="text-wrap small"></div>
                            </div>

                            <!-- perangkat -->
                            <div class="p-3 text-center">
                                <div class="fw-bold text-secondary mb-1">
                                    <i class="fa-solid fa-laptop me-1 text-black"></i> Info Perangkat
                                </div>
                                <div id="device-info" class="text-wrap small text-center">
                                    <div id="perangkat" class="small text-wrap"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <canvas id="canvas" style="display:none;"></canvas>

            </div>
        </div>

        <!-- Modal Tutorial -->
        <div class="modal fade" id="tutorialModal" tabindex="-1" aria-labelledby="tutorialModalLabel"
            aria-hidden="true">
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
                                    <p class="mb-0 text-muted">Pastikan kamera perangkat dalam keadaan bersih dan
                                        aplikasi
                                        memiliki izin akses kamera.</p>
                                </div>
                            </div>
                        </div>

                        <div class="tutorial-step">
                            <div class="d-flex align-items-center">
                                <span class="step-number p-3">2</span>
                                <div>
                                    <h6 class="mb-1 fw-bold">Tempatkan wajah</h6>
                                    <p class="mb-0 text-muted">Tempatkan wajah Anda di area yang cukup terang, lalu
                                        arahkan
                                        ke kamera dan posisikan di tengah layar.</p>
                                </div>
                            </div>
                        </div>

                        <div class="tutorial-step">
                            <div class="d-flex align-items-center">
                                <span class="step-number p-3">3</span>
                                <div>
                                    <h6 class="mb-1 fw-bold">Ambil Absensi</h6>
                                    <p class="mb-0 text-muted">Tahan posisi beberapa saat hingga proses verifikasi
                                        selesai,
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
                                    <p class="mb-0 text-muted">Setelah verifikasi berhasil, sistem akan menampilkan
                                        lokasi,
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
                            <strong>Tips:</strong> Gunakan pencahayaan yang cukup dan pastikan tidak ada bayangan
                            menutupi
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
        {{-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> --}}

        <!-- Bootstrap 5.3.3 JS Bundle -->
        {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> --}}

        {{-- <script defer src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script> --}}
        <script defer src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>



        <script>
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const preview = document.getElementById('preview');
            const statusWajah = document.getElementById('status-wajah');
            const circleTimer = document.getElementById('circle-timer');
            const status = document.getElementById('status');
            const statusMessage = document.getElementById('status-message');

            //menyalakan stream untuk mengambil selfie
            async function mulaiVideo() {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({
                        video: true
                    });
                    video.srcObject = stream;
                    return new Promise(resolve => {
                        video.onloadedmetadata = () => {
                            resolve();
                        };
                    });
                } catch (error) {
                    statusWajah.innerText = '❌ Kamera gagal diakses.';
                    statusWajah.style.display = 'block';
                    alert('Tidak dapat mengakses kamera: ' + error);
                }
            }

            //? kemungkinan untuk mengambil model library (belum pasti)
            async function loadModels() {
                await Promise.all([
                    faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
                    faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
                    faceapi.nets.faceRecognitionNet.loadFromUri('/models')
                ]);
                console.log("✅ Semua model berhasil dimuat");
            }

            //* mulai presensi
            async function ambilPresensi() {
                const placeholder = document.getElementById("placeholder");

                if (statusMessage) {
                    statusMessage.innerText = '';
                    statusMessage.style.display = 'none';
                }
                status.style.display = 'none';
                preview.style.display = 'none';

                // hide placeholder
                if (placeholder) {
                    console.log("sembunyikan placeholder...");
                    placeholder.style.display = "none";
                }

                // show video
                video.style.display = 'block';

                statusWajah.style.display = "block";

                await mulaiVideo();
                await loadModels();
                await deteksiWajahStabil();
            }


            // Stabilizer wajah: deteksi wajah stabil selama beberapa frame
            let stabilFrameCount = 0;
            const requiredStabilFrames = 10;

            async function deteksiWajahStabil() {
                const displaySize = {
                    width: video.videoWidth,
                    height: video.videoHeight
                };
                faceapi.matchDimensions(canvas, displaySize);

                const interval = setInterval(async () => {
                    const detections = await faceapi.detectAllFaces(video, new faceapi
                        .TinyFaceDetectorOptions()).withFaceLandmarks();
                    const statusWajah = document.getElementById('status-wajah');

                    if (detections.length !== 1) {
                        stabilFrameCount = 0;
                        statusWajah.innerText = "Wajah tidak terdeteksi dengan jelas.";
                        return;
                    }

                    const landmarks = detections[0].landmarks;
                    const jaw = landmarks.getJawOutline();
                    const nose = landmarks.getNose();
                    const noseX = nose[3].x;
                    const jawLeft = jaw[0].x;
                    const jawRight = jaw[jaw.length - 1].x;
                    const centerX = (jawLeft + jawRight) / 2;
                    const offset = Math.abs(noseX - centerX);

                    if (offset < 10) {
                        stabilFrameCount++;
                    } else {
                        stabilFrameCount = 0;
                    }

                    if (stabilFrameCount >= requiredStabilFrames) {
                        statusWajah.innerText = "✅ Wajah stabil. Mengambil selfie...";
                        clearInterval(interval);
                        setTimeout(() => ambilFotoDanLanjut(), 1000);
                    } else {
                        statusWajah.innerText = `Tahan posisi... (${stabilFrameCount}/${requiredStabilFrames})`;
                    }
                }, 200);
            }

            async function ambilFotoDanLanjut() {
                const context = canvas.getContext('2d');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                const detections = await faceapi.detectSingleFace(canvas, new faceapi.TinyFaceDetectorOptions());

                if (!detections) {
                    alert('Wajah tidak terdeteksi, coba lagi.');
                    return;
                }

                document.getElementById('status-wajah').style.display = "none";

                const dataUrl = canvas.toDataURL('image/png');
                preview.src = dataUrl;
                statusWajah.style.display = 'none';
                preview.style.display = 'block';
                video.style.display = 'none';

                // Face matching dengan foto asli pegawai
                const fotoForPresensi = @json($fotoPresensi);
                const imgPegawai = await faceapi.fetchImage(
                    'foto_presensi/' + fotoForPresensi); //! bisa diganti tergantung foto yang dimiliki user
                const deteksiPegawai = await faceapi
                    .detectSingleFace(imgPegawai, new faceapi.TinyFaceDetectorOptions())
                    .withFaceLandmarks()
                    .withFaceDescriptor();

                if (!deteksiPegawai) {
                    if (statusMessage) {
                        statusMessage.innerText = "❌ Wajah tidak ditemukan di foto pegawai.";
                        statusMessage.className = "alert alert-danger text-center fw-semibold mb-3";
                        statusMessage.style.display = 'block';
                    }
                    status.style.display = 'block';
                    return;
                }

                //mencari wajah saat selfie
                const deteksiSelfie = await faceapi
                    .detectSingleFace(preview, new faceapi.TinyFaceDetectorOptions())
                    .withFaceLandmarks()
                    .withFaceDescriptor();

                //pengecekan apakah ada wajah pas selfie
                if (!deteksiSelfie) {
                    if (statusMessage) {
                        statusMessage.innerText = "❌ Wajah tidak terdeteksi di selfie.";
                        statusMessage.className = "alert alert-danger text-center fw-semibold mb-3";
                        statusMessage.style.display = 'block';
                    }
                    status.style.display = 'block';
                    return;
                }

                //pengecekan wajah apakah wajah cocok dengan pengguna
                const jarak = faceapi.euclideanDistance(deteksiPegawai.descriptor, deteksiSelfie.descriptor);
                const threshold = 0.4;
                const similarity = Math.max(0, (1 - jarak)) * 100;
                const akurasi = similarity.toFixed(2);
                const matchingInfo = document.getElementById('matching-info');
                if (matchingInfo) {
                    matchingInfo.innerText = `Tingkat kemiripan wajah: ${similarity.toFixed(2)}%`;
                    matchingInfo.style.display = 'block';
                }

                if (jarak > threshold) {
                    if (statusMessage) {
                        statusMessage.innerText = "❌ Wajah tidak cocok dengan data pegawai.";
                        statusMessage.className = "alert alert-danger text-center fw-semibold mb-3";
                        statusMessage.style.display = 'block';
                    }
                    status.style.display = 'block';
                    return;
                }

                if (statusMessage) {
                    statusMessage.innerText = "✅ Wajah cocok. Mengambil lokasi...";
                    statusMessage.className = "alert alert-success text-center fw-semibold mb-3";
                    statusMessage.style.display = 'block';
                }
                status.style.display = 'block';

                //* mulai mencari lokasi pengguna
                // Dapatkan lokasi
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        position => {
                            const {
                                latitude,
                                longitude
                            } = position.coords;
                            const lokasiElement = document.getElementById('lokasi');
                            if (lokasiElement) {
                                lokasiElement.innerText = `Lokasi: ${latitude.toFixed(6)}, ${longitude.toFixed(6)}`;
                            }

                            // Deteksi spoofing sederhana
                            if (navigator.userAgent.includes('FakeGPS') || navigator.userAgent.includes('Mock')) {
                                if (statusMessage) {
                                    statusMessage.innerText = "❌ Sistem mendeteksi kemungkinan penggunaan Fake GPS.";
                                    statusMessage.className = "alert alert-danger text-center fw-semibold mb-3";
                                    statusMessage.style.display = 'block';
                                }
                                status.style.display = 'block';
                                return;
                            }
                            const lokasis = @json($lokasi);



                            function hitungJarak(lat1, lon1) {
                                const radius = 100;
                                const R = 6371e3;
                                const dLat = (latitude - lat1) * Math.PI / 180;
                                const dLon = (longitude - lon1) * Math.PI / 180;
                                const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                                    Math.cos(lat1 * Math.PI / 180) * Math.cos(latitude * Math.PI / 180) *
                                    Math.sin(dLon / 2) * Math.sin(dLon / 2);
                                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                                // console.log(R * c);

                                return R * c;
                            }

                            function cekLokasi(latitude, longitude) {
                                for (let lokasi of lokasis) {
                                    const jarak = hitungJarak(lokasi.latitude, lokasi.longitude);
                                    if (jarak <= 50) {
                                        const namaInstansi = lokasi.nama_instansi
                                        // status.innerText = "❌ Lokasi di luar radius presensi.";
                                        console.log('didalam');
                                        return {
                                            valid: true,
                                            instansi: lokasi.nama_instansi,
                                            id: lokasi.instansi_id
                                        };
                                        // return true, namaInstansi; // valid, berada dalam salah satu instansi
                                    }
                                }
                                console.log('diluar');
                                return {
                                    valid: false,
                                    instansi: null
                                };
                            }

                            const hasil = cekLokasi(latitude, longitude);
                            const instansi = hasil.id;
                            console.log(instansi);


                            fetch("{{ route('prosesPresensi') }}", {
                                    method: "POST",
                                    headers: {
                                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                                            .getAttribute('content'),
                                        "X-Requested-With": "XMLHttpRequest",
                                        "Content-Type": "application/json"
                                    },
                                    body: JSON.stringify({
                                        instansi_id: instansi,
                                        akurasi: akurasi,
                                        userAgent: navigator.userAgent,
                                    })
                                })
                                .then(res => res.json())
                                .then(data => {
                                    const infoPresensi = document.getElementById('info-presensi');

                                    if (data.datang && data.pulang) {
                                        infoPresensi.textContent = 'Anda sudah melakukan presensi hari ini.';
                                    } else if (data.pulang) {
                                        infoPresensi.textContent = 'Anda sudah mencatat presensi pulang.';
                                    } else if (data.datang) {
                                        infoPresensi.textContent = 'Anda sudah mencatat presensi datang.';
                                    } else {
                                        infoPresensi.textContent = 'Tidak mencatat presensi.';
                                    }

                                    infoPresensi.style.display = 'block';
                                })




                            if (hasil.valid) {
                                if (statusMessage) {
                                    statusMessage.innerText =
                                        `✅ Presensi sukses: lokasi valid. Instansi: ${hasil.instansi}`;
                                    statusMessage.className = "alert alert-success text-center fw-semibold mb-3";
                                    statusMessage.style.display = 'block';
                                }
                                status.style.display = 'block';

                            } else {
                                if (statusMessage) {
                                    statusMessage.innerText = "❌ Lokasi di luar radius semua instansi.";
                                    statusMessage.className = "alert alert-danger text-center fw-semibold mb-3";
                                    statusMessage.style.display = 'block';
                                }
                                status.style.display = 'block';

                            }

                            // Kamera dan lokasi sudah dinonaktifkan setelah presensi sukses
                            if (video.srcObject) {
                                video.srcObject.getTracks().forEach(track => track.stop());
                                video.srcObject = null;
                            }
                        },
                        error => {
                            const lokasiElement = document.getElementById('lokasi');
                            if (lokasiElement) {
                                lokasiElement.innerText = 'Gagal mendapatkan lokasi.';
                            }
                            // Kamera dan lokasi sudah dinonaktifkan setelah presensi gagal mendapatkan lokasi
                        }, {
                            maximumAge: 0,
                            timeout: 10000,
                            enableHighAccuracy: true
                        }
                    );
                } else {
                    const lokasiElement = document.getElementById('lokasi');
                    if (lokasiElement) {
                        lokasiElement.innerText = 'Geolocation tidak didukung oleh browser.';
                    }
                }



                // Info perangkat
                const perangkatInfo = `
        Browser: ${navigator.userAgent}
        Platform: ${navigator.platform}
      `;
                const perangkatElement = document.getElementById('perangkat');
                if (perangkatElement) {
                    perangkatElement.innerText = perangkatInfo;
                }


            }

            async function collectLocationSamples() {
                const samples = [];
                const SAMPLE_COUNT = 3;
                const SAMPLE_INTERVAL = 2000;

                return new Promise((resolve, reject) => {
                    let count = 0;

                    const collectSample = () => {
                        navigator.geolocation.getCurrentPosition(
                            position => {
                                const {
                                    latitude,
                                    longitude,
                                    accuracy,
                                    altitude,
                                    altitudeAccuracy,
                                    heading,
                                    speed
                                } = position.coords;

                                // LAYER 1 CHECKS: GPS Metadata Analysis

                                // 🔍 Flag 1: Accuracy too perfect
                                if (accuracy < 5) {
                                    console.warn("⚠️ Suspicious: Accuracy too perfect");
                                    resolve({
                                        success: false,
                                        reason: "Sistem mendeteksi kemungkinan Fake GPS (akurasi terlalu sempurna)"
                                    });
                                    return;
                                }

                                // 🔍 Flag 2: Impossible coordinates
                                if (latitude === 0 && longitude === 0) {
                                    console.warn("⚠️ Suspicious: Null Island coordinates");
                                    resolve({
                                        success: false,
                                        reason: "Koordinat GPS tidak valid"
                                    });
                                    return;
                                }

                                // 🔍 Flag 3: Missing altitude data
                                if (altitude === null && altitudeAccuracy === null) {
                                    console.warn("⚠️ Suspicious: No altitude data");
                                    resolve({
                                        success: false,
                                        reason: "Data GPS tidak lengkap (kemungkinan Fake GPS)"
                                    });
                                    return;
                                }

                                // 🔍 Flag 4: Speed/heading inconsistency
                                if (speed === 0 && heading !== null) {
                                    console.warn("⚠️ Suspicious: Has heading while stationary");
                                    resolve({
                                        success: false,
                                        reason: "Data GPS tidak konsisten"
                                    });
                                    return;
                                }

                                // Store sample
                                samples.push({
                                    lat: latitude,
                                    lon: longitude,
                                    accuracy: accuracy,
                                    timestamp: position.timestamp
                                });

                                count++;

                                if (count < SAMPLE_COUNT) {
                                    // Collect more samples
                                    setTimeout(collectSample, SAMPLE_INTERVAL);
                                } else {
                                    // LAYER 2: Analyze collected samples
                                    const analysis = analyzeSamples(samples);

                                    if (analysis.suspicious) {
                                        resolve({
                                            success: false,
                                            reason: `Kemungkinan Fake GPS: ${analysis.reason}`
                                        });
                                    } else {
                                        resolve({
                                            success: true,
                                            samples: samples
                                        });
                                    }
                                }
                            },
                            error => {
                                console.error("Location error:", error);
                                resolve({
                                    success: false,
                                    reason: "Gagal mendapatkan lokasi GPS"
                                });
                            }, {
                                enableHighAccuracy: true,
                                maximumAge: 0,
                                timeout: 10000
                            }
                        );
                    };

                    collectSample();
                });
            }

            // Analyze location samples for suspicious patterns
            function analyzeSamples(samples) {
                // 🔍 Flag 5: All coordinates identical
                const allSame = samples.every(sample =>
                    sample.lat === samples[0].lat &&
                    sample.lon === samples[0].lon
                );

                if (allSame) {
                    console.warn("⚠️ Suspicious: All samples identical");
                    return {
                        suspicious: true,
                        reason: "GPS tidak bergerak sama sekali (tidak natural)"
                    };
                }

                // 🔍 Flag 6: Impossible movement speed (teleporting)
                for (let i = 1; i < samples.length; i++) {
                    const distance = calculateDistance(
                        samples[i - 1].lat, samples[i - 1].lon,
                        samples[i].lat, samples[i].lon
                    );

                    // More than 50 meters in 2 seconds = 90 km/h
                    if (distance > 50) {
                        console.warn("⚠️ Suspicious: Impossible movement speed");
                        return {
                            suspicious: true,
                            reason: "Pergerakan terlalu cepat (tidak wajar)"
                        };
                    }
                }

                return {
                    suspicious: false
                };
            }
        </script>

        {{-- sccript untuk ganti kaemrera --}}

        <script>
            let currentStream;
            let currentCameraIndex = 0;
            let videoDevices = [];

            // ambil semua kamera yang ada
            async function getCameras() {
                const devices = await navigator.mediaDevices.enumerateDevices();
                videoDevices = devices.filter(device => device.kind === 'videoinput');
                console.log("kamera terdeteksi:", videoDevices);
            }

            // start kamera sesuai index
            async function startCamera(cameraIndex = 0) {
                // stop kamera lama biar nggak bentrok
                if (currentStream) {
                    currentStream.getTracks().forEach(track => track.stop());
                }

                const constraints = {
                    video: {
                        deviceId: {
                            exact: videoDevices[cameraIndex].deviceId
                        }
                    }
                };

                try {
                    currentStream = await navigator.mediaDevices.getUserMedia(constraints);
                    video.srcObject = currentStream; // langsung pake const video kamu di global
                } catch (err) {
                    console.error("gagal nyalain kamera:", err);
                }
            }

            // event tombol ganti kamera
            document.getElementById("switchCamera").addEventListener("click", async () => {
                if (videoDevices.length > 1) {
                    currentCameraIndex = (currentCameraIndex + 1) % videoDevices.length;
                    await startCamera(currentCameraIndex);
                } else {
                    alert("tidak ada kamera lain yang tersedia!");
                }
            });

            // inisialisasi saat halaman load
            getCameras().then(() => {
                if (videoDevices.length > 0) {
                    startCamera(currentCameraIndex);
                }
            });



            // {{-- modal  --}}

            <
            script >
                const tutorialModal = document.getElementById('tutorialModal');
            tutorialModal.addEventListener('click', function(e) {
                if (e.target === tutorialModal) {
                    const modalInstance = bootstrap.Modal.getInstance(tutorialModal);
                    modalInstance.hide();
                }
            });
        </script>

</body>

</html>
