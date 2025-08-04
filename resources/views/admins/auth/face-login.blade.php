@extends('layout.LandingPage')
@section('content')
@php
    $services = Entities_Customer();
@endphp

<div class="container-fluid position-relative p-0">
    <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 py-lg-0">
        <a href="{{ route('welcome.index') }}" class="navbar-brand p-0">
            <h3 class="m-0">
                <img src="{{ asset('template/assets/images/logo.png') }}" height="70px" alt="Logo">
            </h3>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="fa fa-bars"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto py-0">
                <a href="{{ route('welcome.index') }}" class="nav-item nav-link active">Accueil</a>
                <a href="{{ route('about', ['service' => $services[0]['uuid'] ?? '', 'name' => $services[0]['name'] ?? '']) }}" class="nav-item nav-link">Nous contacter</a>
            </div>
            <a href="{{ route('register', ['service' => $services[0]['uuid'] ?? '', 'name' => $services[0]['name'] ?? '']) }}" class="btn btn-primary py-2 px-4 ms-3">Mon compte</a>
        </div>
    </nav>

    <div class="container-fluid bg-primary py-3 bg-header" style="margin-bottom: 90px;">
        <div class="row py-5">
            <div class="col-12 pt-lg-5 mt-lg-5 text-center">
                <h1 class="display-6 text-white animated zoomIn">DISTRICT AUTONOME D'ABIDJAN</h1>
                <a href="#" class="h5 text-white">Plateforme digitale de délivrance de la carte de stationnement</a>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="container" style="transform: translateY(-70px);">
        <div class="login-wrapper login-new">
            <div class="row w-100">
                <div class="col-lg-6 mx-auto">
                    <div class="login-content user-login bg-success rounded d-flex p-3">
                        <div class="login-logo">
                            <img src="{{ asset('assets/img/logo.png') }}" alt="">
                            <a href="#" class="login-logo logo-white">
                                <img src="{{ asset('assets/img/logo-white.png') }}" alt="">
                            </a>
                        </div>
                        <div class="card">
                            <div class="card-body p-5">
                                <div class="login-userheading">
                                    <h3 style="font-family:debian">Espace Administrateur</h3>
                                    <h4>
                                        Accéder à votre espace d'administration en utilisant votre reconnaissance faciale 
                                    </h4>
                                </div>

                                <div class="video-wrapper-custom mx-auto text-center position-relative">
                                    <video id="video" class="video-custom" autoplay muted></video>
                                    <canvas id="overlay" class="overlay-custom position-absolute top-0 start-0" style="z-index: 10;"></canvas>
                                    <div id="loader" class="position-absolute top-50 start-50 translate-middle d-none" style="z-index: 20;">
                                        <div class="spinner-border text-primary" role="status"></div>
                                    </div>
                                </div>


                                <select id="camera-select" class="form-control mt-3"></select>
                                <select id="resolution-select" class="form-control mt-2">
                                    <option value="640x480">480p</option>
                                    <option value="1280x720" selected>720p</option>
                                    <option value="1920x1080">1080p</option>
                                </select>

                                <br><br>
                                <div class="form-login">
                                    <button type="button" id="start-scan" class="btn btn-primary w-100">Activer la caméra</button>
                                    <button type="button" id="stop-scan" class="btn btn-danger w-100 d-none mt-2">Désactiver la caméra</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('footer-script')
<script src="{{ asset('models/face-api.min.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const video = document.getElementById('video');
        const startBtn = document.getElementById('start-scan');
        const stopBtn = document.getElementById('stop-scan');
        const canvas = document.getElementById('overlay');
        const loader = document.getElementById('loader');
        const cameraSelect = document.getElementById('camera-select');
        const resolutionSelect = document.getElementById('resolution-select');

        let stream = null;
        let scanInProgress = false;
        let attemptCount = 0;
        const MAX_ATTEMPTS = 5;
        let hasStarted = false;

        await Promise.all([
            faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
            faceapi.nets.faceRecognitionNet.loadFromUri('/models'),
            faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
            
            faceapi.nets.ageGenderNet.loadFromUri('/models'),
            faceapi.nets.faceExpressionNet.loadFromUri('/models'),
        ]);

        async function listCameras() {
            try {
                const tempStream = await navigator.mediaDevices.getUserMedia({ video: true });
                tempStream.getTracks().forEach(track => track.stop());

                const devices = await navigator.mediaDevices.enumerateDevices();
                const videoDevices = devices.filter(device => device.kind === 'videoinput');

                if (videoDevices.length === 0) {
                    alert("Aucune caméra détectée.");
                    return;
                }

                cameraSelect.innerHTML = '';
                let defaultDeviceId = videoDevices[0].deviceId;

                videoDevices.forEach((device, index) => {
                    const option = document.createElement('option');
                    option.value = device.deviceId;
                    option.text = device.label || `Caméra ${index + 1}`;
                    cameraSelect.appendChild(option);
                });

                cameraSelect.value = defaultDeviceId;
                //await startCamera();
            } catch (error) {
                console.error("Erreur lors de la détection des caméras :", error);
            }
        }

        async function startCamera() {
            if (stream) stopCamera();

            const [width, height] = resolutionSelect.value.split('x').map(Number);
            const constraints = {
                video: {
                    deviceId: cameraSelect.value ? { exact: cameraSelect.value } : undefined,
                    width: { ideal: width },
                    height: { ideal: height },
                    facingMode: 'user'
                },
                audio: false
            };

            try {
                stream = await navigator.mediaDevices.getUserMedia(constraints);
                video.srcObject = stream;

                await new Promise(resolve => {
                    if (video.readyState >= 2) resolve();
                    else video.onloadedmetadata = () => resolve();
                });

                const size = { width: video.videoWidth, height: video.videoHeight };
                canvas.width = size.width;
                canvas.height = size.height;
                faceapi.matchDimensions(canvas, size);

                startBtn.classList.add('d-none');
                stopBtn.classList.remove('d-none');

                // Lancement du défi anti-spoofing AVANT la boucle principale
                loader.classList.remove('d-none');
                const antiSpoofPassed = await runAntiSpoofingChallenge(video, size);
                loader.classList.add('d-none');

                if (!antiSpoofPassed) {
                    toastr.error("Défi anti-spoofing échoué. La caméra va s'arrêter.", "Erreur");
                    stopCamera();
                    return; // On arrête ici, ne pas démarrer la boucle
                }

                // Si défi réussi, on démarre la boucle principale
                attemptCount = 0;
                hasStarted = true;
                scanFaceLoop(size);

            } catch (err) {
                console.error("Erreur d'accès à la caméra :", err);
                toastr.error("Impossible d'accéder à la caméra.", "Erreur");
            }
        }

        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
            video.srcObject = null;
            loader.classList.add('d-none');
            startBtn.classList.remove('d-none');
            stopBtn.classList.add('d-none');
            attemptCount = 0;
            scanInProgress = false;
            hasStarted = false;
        }

            async function scanFaceLoop(displaySize) {
                if (scanInProgress) return;
                scanInProgress = true;

                while (attemptCount < MAX_ATTEMPTS) {
                    attemptCount++;
                    loader.classList.remove('d-none');

                    const detection = await faceapi
                        .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                        .withFaceLandmarks()
                        .withFaceDescriptor()
                        .withAgeAndGender()
                        .withFaceExpressions();

                    const context = canvas.getContext('2d');
                    context.clearRect(0, 0, canvas.width, canvas.height);

                    if (!detection) {
                        toastr.warning(`Tentative ${attemptCount}/${MAX_ATTEMPTS} : Aucun visage détecté.`, 'Attention');
                        loader.classList.add('d-none');
                        await delay(2000);
                        continue;
                    }

                   /*  const antiSpoofPassed = await runAntiSpoofingChallenge(video, displaySize);
                    if (!antiSpoofPassed) {
                        toastr.error("Détection anti-spoofing échouée. Veuillez réessayer.", "Refusé");
                        loader.classList.add('d-none');
                        await delay(2000);
                        continue;
                    } */

                    const resized = faceapi.resizeResults(detection, displaySize);
                    faceapi.draw.drawDetections(canvas, resized);
                    const drawBox = new faceapi.draw.DrawBox(resized.detection.box, { label: "" });
                    drawBox.draw(canvas);

                    const { age, gender, genderProbability, expressions } = detection;
                    const topEmotion = Object.entries(expressions).sort((a, b) => b[1] - a[1])[0];

                    context.fillStyle = 'white';
                    context.font = '16px Arial';
                    context.fillText(`Âge: ${Math.round(age)} | Sexe: ${gender} (${(genderProbability * 100).toFixed(0)}%)`, 10, 20);
                    context.fillText(`Émotion: ${topEmotion[0]} (${(topEmotion[1] * 100).toFixed(0)}%)`, 10, 40);

                    const embedding = Array.from(detection.descriptor);

                    toastr.info(`
                        <div style="text-align: left;">
                            <strong>Âge estimé :</strong> ${Math.round(age)} ans<br>
                            <strong>Sexe :</strong> ${gender} (${(genderProbability * 100).toFixed(1)}%)<br>
                            <strong>Émotion dominante :</strong> ${topEmotion[0]} (${(topEmotion[1] * 100).toFixed(1)}%)
                        </div>`, '🧠 Données faciales détectées');

                    try {
                        const controller = new AbortController();
                        const timeoutId = setTimeout(() => controller.abort(), 10000);

                        const res = await fetch('/panel/face-auth/verify', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ embedding }),
                            signal: controller.signal
                        });

                        clearTimeout(timeoutId);
                        const data = await res.json();

                        if (data.type === "success") {
                            stopCamera();
                            startBtn.disabled = true;
                            stopBtn.disabled = true;
                            scanInProgress = false;
                            sendSuccess(data.message,data.urlback);
                            return;
                        } else {
                            toastr.warning(`Tentative ${attemptCount}/${MAX_ATTEMPTS} : ${data.message}`, 'Échec');
                        }
                    } catch (err) {
                        const msg = err.name === 'AbortError' ? "Temps de réponse dépassé (10s)." : "Erreur réseau.";
                        toastr.error(msg, 'Erreur');
                    }

                    loader.classList.add('d-none');
                    await delay(2000);
                }

                stopCamera();
                toastr.error("5 tentatives échouées. Veuillez réessayer.", 'Échec');
                scanInProgress = false;
            }

            function hasHeadMoved(initialNose, currentNose, threshold = 20) {
                const dx = Math.abs(currentNose.x - initialNose.x);
                const dy = Math.abs(currentNose.y - initialNose.y);
                return dx > threshold || dy > threshold;
            }

            async function runAntiSpoofingChallenge(video, displaySize, debug = false) {
                const CHALLENGE_DURATION = 6000;
                const CHECK_INTERVAL = 100;
                const LOCAL_MAX_ATTEMPTS = 3;

                const detectFace = async () =>
                    await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks();

                async function runSingleChallenge(actionType) {
                    const messages = {
                        move: 'Tournez la tête ↔️',
                    };
                    toastr.info(messages[actionType] || 'Action requise', 'Défi anti-spoofing', { timeOut: CHALLENGE_DURATION });

                    const initialDetection = await detectFace();
                    if (!initialDetection) return false;

                    const initialNose = initialDetection.landmarks.getNose()[0];

                    return new Promise(resolve => {
                        const timeout = setTimeout(() => {
                            clearInterval(interval);
                            resolve(false);
                        }, CHALLENGE_DURATION);

                        const interval = setInterval(async () => {
                            const detection = await detectFace();
                            if (!detection) return;

                            const landmarks = detection.landmarks;
                            const currentNose = landmarks.getNose()[0];

                            if (actionType === 'move' && hasHeadMoved(initialNose, currentNose)) {
                                clearTimeout(timeout);
                                clearInterval(interval);
                                resolve(true);
                            }
                        }, CHECK_INTERVAL);
                    });
                }

                let attempts = 0;
                while (attempts < LOCAL_MAX_ATTEMPTS) {
                    const success = await runSingleChallenge('move');
                    if (success) return true;
                    attempts++;
                    if (attempts < LOCAL_MAX_ATTEMPTS) {
                        toastr.warning('Défi échoué. Nouveau défi...', 'Réessaie', { timeOut: 2000 });
                    }
                }
                return false;
            }

        function delay(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }

        cameraSelect.addEventListener('change', startCamera);
        startBtn.addEventListener('click', startCamera);
        stopBtn.addEventListener('click', stopCamera);

        await listCameras();
    });
</script>
@endpush

@endsection
