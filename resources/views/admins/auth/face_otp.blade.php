@extends('layout.LandingPage')
@section('content')
@php
    $services = Entities_Customer();
@endphp
    <!-- Navbar & Carousel Start -->
    <div class="container-fluid position-relative p-0">
        <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 py-lg-0">
            <a href="{{ route('welcome.index') }}" class="navbar-brand p-0">
                <h3 class="m-0">
                    <img src="{{ asset('template/assets/images/logo.png') }}"  height="70px" alt="Logo">
                </h3>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0">
                    <a href="{{ route('welcome.index') }}" class="nav-item nav-link active">Accueil</a>
                    <a href="{{ route('about', ['service' => $services[0]['uuid'] ?? '', 'name' => $services[0]['name'] ?? '']) }}" class="nav-item nav-link">Nous contacter</a>

                    <div class="nav-item dropdown"></div>
                    <div class="nav-item dropdown"></div>
                </div>
 
                <a href="{{ route('register', ['service' => $services[0]['uuid'] ?? '', 'name' => $services[0]['name'] ?? '']) }}" class="btn btn-primary py-2 px-4 ms-3">Mon compte</a>

            </div>
        </nav>

        <div class="container-fluid bg-primary py-3 bg-header" style="margin-bottom: 90px;">
            <div class="row py-5">
                <div class="col-12 pt-lg-5 mt-lg-5 text-center">
                    <h1 class="display-6 text-white animated zoomIn">DISTRICT AUTONOME D'ABIDJAN</h1>
                    <a href="" class="h5 text-white">Plateforme digitale de délivrance de la carte de stationnement</a>
                    {{-- <i class="far fa-circle text-white px-2"></i>
                    <a href="" class="h5 text-white">About</a> --}}
                </div>
            </div>
        </div>
    </div>
    <!-- Navbar & Carousel End -->

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
                                        <h3 class="text-black">FORMULAIRE DE CONNEXION BACKOFFICE</h3>
                                        <h5>AUTHENTIFICATION</h5>
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

                                    <br>
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

    <script src="{{ asset('models/face-api.min.js') }}"></script>
    
<script>
    document.addEventListener('DOMContentLoaded', async () => {
        function cosineSimilarity(vecA, vecB) {
            const dot = vecA.reduce((sum, val, i) => sum + val * vecB[i], 0);
            const normA = Math.sqrt(vecA.reduce((sum, val) => sum + val * val, 0));
            const normB = Math.sqrt(vecB.reduce((sum, val) => sum + val * val, 0));
            return dot / (normA * normB);
        }

        const video = document.getElementById('video');
        const startBtn = document.getElementById('start-scan');
        const stopBtn = document.getElementById('stop-scan');
        const canvas = document.getElementById('overlay');
        const loader = document.getElementById('loader');
        const cameraSelect = document.getElementById('camera-select');
        const resolutionSelect = document.getElementById('resolution-select');

        if (!video || !startBtn || !stopBtn || !canvas || !loader || !cameraSelect || !resolutionSelect) {
            console.error("Certains éléments DOM requis sont introuvables.");
            return;
        }

        let stream = null;
        let scanInProgress = false;
        let attemptCount = 0;
        const MAX_ATTEMPTS = 5;
        let hasStarted = false;

        try {
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
                faceapi.nets.faceRecognitionNet.loadFromUri('/models'),
                faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
                faceapi.nets.ageGenderNet.loadFromUri('/models'),
                faceapi.nets.faceExpressionNet.loadFromUri('/models'),
            ]);
        } catch (e) {
            console.error("Erreur lors du chargement des modèles FaceAPI", e);
            return;
        }

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
                videoDevices.forEach((device, index) => {
                    const option = document.createElement('option');
                    option.value = device.deviceId;
                    option.text = device.label || `Caméra ${index + 1}`;
                    cameraSelect.appendChild(option);
                });
                cameraSelect.value = videoDevices[0].deviceId;
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
                loader.classList.remove('d-none');

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

            let antiSpoofAttempts = 0;
            let antiSpoofEmbedding = null;

            
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

                // Si on n'a pas encore validé de défi anti-spoofing
                if (!antiSpoofEmbedding) {
                    while (antiSpoofAttempts < 3) {
                        antiSpoofAttempts++;
                        const antiSpoofResult = await runRandomAntiSpoofingChallenge(video, displaySize);

                        if (antiSpoofResult.success) {
                            antiSpoofEmbedding = antiSpoofResult.descriptor;
                            break; // défi réussi, sortir de la boucle
                        } else {
                            showToast('error', `Détection anti-spoofing échouée (tentative ${antiSpoofAttempts}/3).`, 'Refusé', position = 'toast-top-center')

                            //toastr.error(`Détection anti-spoofing échouée (tentative ${antiSpoofAttempts}/3).`, "Refusé");
                            await delay(2000);
                        }
                    }
                    if (!antiSpoofEmbedding) {
                        // Défi échoué deux fois, on arrête le scan
                        showToast('error', "Échec du défi anti-spoofing après 3 tentatives. Processus arrêté.", 'Refusé', position = 'toast-top-center')

                        toastr.error("Échec du défi anti-spoofing après 3 tentatives. Processus arrêté.", "Refusé");
                        loader.classList.add('d-none');
                        stopCamera();
                        scanInProgress = false;
                        return;
                    }
                }

                //console.log('antiSpoofEmbedding');
                //console.log(antiSpoofEmbedding);

                const resized = faceapi.resizeResults(detection, displaySize);
                faceapi.draw.drawDetections(canvas, resized);
                const drawBox = new faceapi.draw.DrawBox(resized.detection.box, { label: "" });
                drawBox.draw(canvas);

                const { age, gender, genderProbability, expressions } = detection;
                const topEmotion = Object.entries(expressions).sort((a, b) => b[1] - a[1])[0];
                const embedding = Array.from(detection.descriptor);

                /* toastr.info(`
                    <div style="text-align: left;">
                        <strong>Âge estimé :</strong> ${Math.round(age)} ans<br>
                        <strong>Sexe :</strong> ${gender} (${(genderProbability * 100).toFixed(1)}%)<br>
                        <strong>Émotion dominante :</strong> ${topEmotion[0]} (${(topEmotion[1] * 100).toFixed(1)}%)
                    </div>`, '🧠 Données faciales détectées'); */

                try {
    
                    const controller = new AbortController();
                    const timeoutId = setTimeout(() => controller.abort(), 10000);
                    
                    showToast('info', "Vérification Visage entre défi et vérification.", 'Sécurité', position = 'toast-top-right')
                    //toastr.info("Vérification Visage entre défi et vérification.", "Sécurité");

                    const similarity = cosineSimilarity(embedding, antiSpoofEmbedding);
                    console.log(similarity)
                    await delay(2000);
                    if (similarity < 0.92) {
                        showToast('error', "Vérification Visage entre défi et vérification.", 'Sécurité', position = 'toast-top-right')

                        toastr.error("Visage incohérent entre défi et vérification. Processus arrêté.", "Sécurité");
                        loader.classList.add('d-none');
                        stopCamera();
                        scanInProgress = false;
                        return;
                    }

                    const res = await fetch('/panel/face-auth/verify/otp', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ 'embedding': embedding,'gender':gender,'topEmotion':topEmotion }),
                        signal: controller.signal
                    });

                    clearTimeout(timeoutId);
                    const data = await res.json();

                    if (data.type === "success") {
                        stopCamera();
                        startBtn.disabled = true;
                        stopBtn.disabled = true;
                        scanInProgress = false;
                        sendSuccess(data.message, data.urlback);
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
            toastr.error(`Nombre maximal de tentatives (${MAX_ATTEMPTS}) atteint. Veuillez réessayer.`, 'Échec');
            scanInProgress = false;
        }


        function delay(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }


            async function runRandomAntiSpoofingChallenge(video, displaySize) {
                const challenges = [
                    runBlinkChallenge,
                    runFacialExpressionSmileChallenge,
                   // runFacialExpressionAngryChallenge,
                    //runFacialExpressionSurprisedChallenge,
                   // runFacialExpressionDisgustedChallenge,
                    runTurnRightChallenge,
                    runTurnLeftChallenge,
                    runLookUpChallenge,
                    runLookDownChallenge
                ];

                const randomIndex = Math.floor(Math.random() * challenges.length);
                const challengeFn = challenges[randomIndex];

                const result = await challengeFn(video, displaySize);
                return result;
            }

            async function runFacialExpressionSmileChallenge(video) {
                showToast('info', "Veuillez sourire 😊 pour le défi de sécurité.", 'Défi', position = 'toast-top-center')

                //toastr.info("Veuillez sourire 😊 pour le défi de sécurité.", "Défi");
                return await detectExpression(video, 'happy', 0.8);
            }

            async function runFacialExpressionAngryChallenge(video) {
                showToast('info', "Montrez une expression en colère 😠.", 'Défi', position = 'toast-top-center')

                //toastr.info("Montrez une expression en colère 😠.", "Défi");
                return await detectExpression(video, 'angry', 0.7);
            }

            async function runFacialExpressionSurprisedChallenge(video) {
                showToast('info', "Affichez une expression surprise 😮.", 'Défi', position = 'toast-top-center')

                //toastr.info("Affichez une expression surprise 😮.", "Défi");
                return await detectExpression(video, 'surprised', 0.8);
            }

            async function runFacialExpressionDisgustedChallenge(video) {
                showToast('info', "Affichez une expression surprise 😮.", 'Défi', position = 'toast-top-center')

                //toastr.info( "Affichez une expression surprise 😮.", "Défi");
                return await detectExpression(video, 'disgusted', 0.7);
            }

            async function detectExpression(video, targetExpression, threshold) {
                //alert("******")
                for (let i = 0; i < 5; i++) {
                    const result = await faceapi
                        .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                        .withFaceLandmarks()
                        .withFaceDescriptor()
                        .withFaceExpressions();
                        console.log(targetExpression)

                    if (result && result.expressions[targetExpression] > threshold) {

                        toastr.success("✅ Défi réussi", "Défi");

                        return { success: true, descriptor: result.descriptor };
                    }
                    await delay(1000);
                }
                return { success: false };
            }

            async function runBlinkChallenge(video) {

                toastr.info("Veuillez cligner des yeux 👀.", "Défi");

                for (let i = 0; i < 15; i++) { // Plus de tentatives + fréquence plus haute
                    const detection = await faceapi
                        .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                        .withFaceLandmarks()
                        .withFaceDescriptor();

                    if (!detection) continue;

                    const landmarks = detection.landmarks;
                    const leftEAR = computeEAR(landmarks.getLeftEye());
                    const rightEAR = computeEAR(landmarks.getRightEye());
                    const avgEAR = (leftEAR + rightEAR) / 2;

                    console.log("EAR moyen :", avgEAR.toFixed(3)); // Pour voir les valeurs

                    if (avgEAR < 0.40) {
                        console.log("✅ Clignement détecté.");

                        toastr.success("✅ Défi réussi", "Défi");

                        return { success: true, descriptor: detection.descriptor };
                    }

                    await delay(250); // plus rapide
                }

                return { success: false };
            }

            function computeEAR(eye) {
                const p2p6 = distance(eye[1], eye[5]);
                const p3p5 = distance(eye[2], eye[4]);
                const p1p4 = distance(eye[0], eye[3]);
                return (p2p6 + p3p5) / (2.0 * p1p4);
            }

            function distance(p1, p2) {
                const dx = p1.x - p2.x;
                const dy = p1.y - p2.y;
                return Math.sqrt(dx * dx + dy * dy);
            }

            async function runTurnRightChallenge(video) {
                toastr.options.positionClass = 'toast-top-center';
                toastr.info("Tournez la tête vers la droite 👉", "Défi");

                return await detectHeadMovement(video, 'right');
            }

            async function runTurnLeftChallenge(video) {
                toastr.options.positionClass = 'toast-top-center';
                toastr.info("Tournez la tête vers la gauche 👈", "Défi");

                return await detectHeadMovement(video, 'left');
            }

            async function runLookUpChallenge(video) {
                toastr.options.positionClass = 'toast-top-center';
                toastr.info("Levez les yeux 👆", "Défi");

                return await detectHeadMovement(video, 'up');
            }

            async function runLookDownChallenge(video) {
                toastr.options.positionClass = 'toast-top-center';
                toastr.info("Baissez les yeux 👇", "Défi");

                return await detectHeadMovement(video, 'down');
            }


            async function detectHeadMovement(video, direction) {
                const initialDetection = await faceapi
                    .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                    .withFaceLandmarks()
                    .withFaceDescriptor();

                if (!initialDetection) return { success: false };

                const initialNose = initialDetection.landmarks.getNose()[3];
                const initialJaw = initialDetection.landmarks.getJawOutline()[8];

                for (let i = 0; i < 15; i++) {
                    const detection = await faceapi
                        .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                        .withFaceLandmarks()
                        .withFaceDescriptor();

                    if (!detection) continue;

                    const currentNose = detection.landmarks.getNose()[3];
                    const currentJaw = detection.landmarks.getJawOutline()[8];

                    const dx = currentNose.x - initialNose.x;
                    const dy = currentNose.y - initialNose.y;
                    const jawDy = currentJaw.y - initialJaw.y;

                    const TOLERANCE = 10;

                    if (
                        (direction === 'right' && dx > TOLERANCE) ||
                        (direction === 'left' && dx < -TOLERANCE) ||
                        (direction === 'up' && jawDy < -TOLERANCE) ||
                        (direction === 'down' && jawDy > TOLERANCE)
                    ) {
                        toastr.success("✅ Défi réussi", "Défi");
                        return { success: true, descriptor: detection.descriptor };
                    }

                    await delay(300);
                }

                return { success: false };
            }


                function showToast(type, message, title, position = 'toast-top-right') {
                    toastr.options = {
                        positionClass: 'toast-top-right',//position,
                        closeButton: true,
                        progressBar: true,
                        timeOut: "5000",
                        extendedTimeOut: "1000",
                        showDuration: "300",
                        hideDuration: "1000",
                        showMethod: "fadeIn",
                        hideMethod: "fadeOut"
                    };
                    toastr[type](message, title);
                }
        // Événements
        cameraSelect.addEventListener('change', startCamera);
        startBtn.addEventListener('click', startCamera);
        stopBtn.addEventListener('click', stopCamera);

        await listCameras();
    });
</script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const resendButton = document.getElementById('resend');

            if (resendButton) {
                resendButton.addEventListener('click', function () {
                    // Récupère l'URL à partir de l'attribut data-href
                    const url = this.getAttribute('data-href');
                    if (!url) {
                        toastr.error('L\'URL n\'est pas spécifiée.', 'Echec');
                        return;
                    }

                    // Affiche le loader
                    loader();

                    // Envoie une requête à l'URL
                    fetch(url, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest', // Pour signaler une requête AJAX
                        }
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('La requête a échoué avec le statut ' + response.status);
                            }
                            return response.json(); // Parse la réponse en JSON
                        })
                        .then(data => {
                            if (data.type === "success") {
                                toastr.success(data.message, 'Succès');
                            } else {
                                toastr.error(data.message, 'Echec');
                            }
                        })
                        .catch(error => {
                            toastr.error('Une erreur est survenue : ' + error.message, 'Echec');
                        })
                        .finally(() => {
                            // Cache le loader
                            loader('hide');
                        });
                });
            } else {
                console.warn('Le bouton avec l\'ID "resend" est introuvable dans le DOM.');
            }
        });
    </script>

@endsection