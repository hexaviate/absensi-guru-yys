<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    {{-- Perlu ditambahkan !penting! --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Perlu ditambahkan !penting! --}}
    <title>Presensi - Selfie, Lokasi, Perangkat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-teal {
            background-color: #009688;
            color: white;
        }

        .btn-teal:hover {
            background-color: #00796b;
        }

        .text-teal {
            color: #00695c;
        }

        #preview,
        #video {
            border-radius: 50%;
            border: 4px solid #009688;
            object-fit: cover;
            background: #f0f0f0;
            display: block;
            margin: 0 auto 1rem auto;
            width: 100%;
            max-width: 200px;
            aspect-ratio: 1/1;
        }

        #status-wajah.ready {
            color: #009688;
        }
    </style>
</head>

<body>
    <div class="container py-4">
        <div class="card shadow-sm rounded-4 p-4">

            <div class="text-center mb-3">
                <img id="preview" alt="Hasil Selfie" class="rounded-circle border border-success shadow"
                    style="width: 180px; height: 180px; object-fit: cover; margin-bottom: 1rem; display: none;" />
                <video id="video" autoplay playsinline muted class="rounded-circle border border-info shadow"
                    style="width: 180px; height: 180px; object-fit: cover; transform: scaleX(-1); background-color: #f8f9fa; display: none;">
                </video>
            </div>

            <div id="status-wajah" class="text-center text-danger fw-semibold mb-3 text-wrap"
                style="font-size: 15px; display:none;">Mendeteksi wajah...</div>
            <div id="matching-info" class="text-center text-muted small mb-3 text-wrap" style="display:none;"></div>
            <canvas id="canvas" style="display:none;"></canvas>

            <button class="btn btn-teal w-100 fw-semibold mb-4" onclick="ambilPresensi()">Ambil Presensi</button>

            <div class="alert alert-success text-center fw-semibold mb-4" role="alert" id="status"
                style="display:none;">
                ✅ Presensi sukses: <br> wajah cocok dan lokasi valid.
            </div>
            <div class="mb-3">
                <div class="card p-3 text-center">
                    <div class="fw-bold text-secondary mb-1">📍 Lokasi</div>
                    <div id="lokasi" class="text-wrap small"></div>
                </div>
            </div>
            <div class="mb-3">
                <div class="card p-3">
                    <div class="fw-bold text-secondary mb-1">💻 Info Perangkat</div>
                    <div id="perangkat" class="small text-wrap"></div>
                </div>
            </div>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const preview = document.getElementById('preview');
        const statusWajah = document.getElementById('status-wajah');
        const circleTimer = document.getElementById('circle-timer');
        const status = document.getElementById('status');

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
            status.innerText = '';
            status.style.display = 'none';
            preview.style.display = 'none';
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
                'foto/' + fotoForPresensi); //! bisa diganti tergantung foto yang dimiliki user
            const deteksiPegawai = await faceapi
                .detectSingleFace(imgPegawai, new faceapi.TinyFaceDetectorOptions())
                .withFaceLandmarks()
                .withFaceDescriptor();

            if (!deteksiPegawai) {
                status.innerText = "❌ Wajah tidak ditemukan di foto pegawai.";
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
                status.innerText = "❌ Wajah tidak terdeteksi di selfie.";
                status.style.display = 'block';
                return;
            }

            //pengecekan wajah apakah wajah cocok dengan pengguna
            const jarak = faceapi.euclideanDistance(deteksiPegawai.descriptor, deteksiSelfie.descriptor);
            const threshold = 0.4;
            const similarity = Math.max(0, (1 - jarak)) * 100;
            const akurasi = similarity.toFixed(2);
            const matchingInfo = document.getElementById('matching-info');
            matchingInfo.innerText = `Tingkat kemiripan wajah: ${similarity.toFixed(2)}%`;
            matchingInfo.style.display = 'block';

            if (jarak > threshold) {
                status.innerText = "❌ Wajah tidak cocok dengan data pegawai.";
                status.style.display = 'block';
                return;
            }

            status.innerText = "✅ Wajah cocok. Mengambil lokasi...";
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
                        document.getElementById('lokasi').innerText =
                            `Lokasi: ${latitude.toFixed(6)}, ${longitude.toFixed(6)}`;

                        // Deteksi spoofing sederhana
                        if (navigator.userAgent.includes('FakeGPS') || navigator.userAgent.includes('Mock')) {
                            status.innerText = "❌ Sistem mendeteksi kemungkinan penggunaan Fake GPS.";
                            return;
                        }

                        // Validasi lokasi radius
                        // const radius = 100;
                        // const pusatLat = -6.592996353118405;
                        // const pusatLon = 111.06748580403307;
                        // const R = 6371e3;
                        // const dLat = (latitude - pusatLat) * Math.PI / 180;
                        // const dLon = (longitude - pusatLon) * Math.PI / 180;
                        // const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                        //     Math.cos(pusatLat * Math.PI / 180) * Math.cos(latitude * Math.PI / 180) *
                        //     Math.sin(dLon / 2) * Math.sin(dLon / 2);
                        // const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                        // const jarak = R * c;
                        // if (jarak > radius) {
                        //     status.innerText = "❌ Lokasi di luar radius presensi.";
                        //     return;
                        // }

                        // status.innerText = "✅ Presensi sukses: wajah cocok dan lokasi valid.";
                        // status.style.display = 'block';

                        ///////////////////////////////////////////////////////////////////////////////////////
                        //! ini AI
                        const lokasis = @json($lokasi);

                        // function hitungJarak(lat1, lon1, lat2, lon2) {
                        //     const R = 6371e3; // jari-jari bumi
                        //     const dLat = (lat2 - lat1) * Math.PI / 180;
                        //     const dLon = (lon2 - lon1) * Math.PI / 180;

                        //     const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                        //         Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                        //         Math.sin(dLon / 2) * Math.sin(dLon / 2);

                        //     const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                        //     return R * c; // meter
                        // }

                        // function cekLokasi(latitude, longitude) {
                        //     for (let lokasi of lokasis) {
                        //         const jarak = hitungJarak(lokasi.latitude, lokasi.longitude, latitude,
                        //             longitude);
                        //         if (jarak <= instansi.radius) {
                        //             return true; // valid, berada dalam salah satu instansi
                        //         }
                        //     }
                        //     return false; // tidak ada yang cocok
                        // }

                        // if (cekLokasi(latitude, longitude)) {
                        //     status.innerText = "✅ Presensi sukses: lokasi valid.";
                        // } else {
                        //     status.innerText = "❌ Lokasi di luar radius semua instansi.";
                        // }
                        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                        function hitungJarak(lat1, lon1) {
                            const radius = 100;
                            const R = 6371e3;
                            const dLat = (latitude - lat1) * Math.PI / 180;
                            const dLon = (longitude - lon1) * Math.PI / 180;
                            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                                Math.cos(lat1 * Math.PI / 180) * Math.cos(latitude * Math.PI / 180) *
                                Math.sin(dLon / 2) * Math.sin(dLon / 2);
                            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                            console.log(R * c);

                            return R * c;
                        }

                        function cekLokasi(latitude, longitude) {
                            for (let lokasi of lokasis) {
                                const jarak = hitungJarak(lokasi.latitude, lokasi.longitude);
                                if (jarak <= 100) {
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
                                console.log("Response Laravel:", data);
                            });

                        if (hasil.valid) {
                            status.innerText = `✅ Presensi sukses: lokasi valid.
                                                    Instansi: ${hasil.instansi}`;
                            status.style.display = 'block';

                        } else {
                            status.innerText = "❌ Lokasi di luar radius semua instansi.";
                            status.style.display = 'block';

                        }

                        // Kamera dan lokasi sudah dinonaktifkan setelah presensi sukses
                        if (video.srcObject) {
                            video.srcObject.getTracks().forEach(track => track.stop());
                            video.srcObject = null;
                        }
                    },
                    error => {
                        document.getElementById('lokasi').innerText = 'Gagal mendapatkan lokasi.';
                        // Kamera dan lokasi sudah dinonaktifkan setelah presensi gagal mendapatkan lokasi
                    }, {
                        maximumAge: 0,
                        timeout: 10000,
                        enableHighAccuracy: true
                    }
                );
            } else {
                document.getElementById('lokasi').innerText = 'Geolocation tidak didukung oleh browser.';
            }

            // Info perangkat
            const perangkatInfo = `
        Browser: ${navigator.userAgent}
        Platform: ${navigator.platform}
      `;
            document.getElementById('perangkat').innerText = perangkatInfo;

            status.style.display = 'block';


        }
    </script>
</body>

</html>
