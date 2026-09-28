@extends('layout.adminApp')

@section('page-title')
    <span class="v2-breadcrumb">Sécurité <span>&rsaquo;</span> <strong>Mon compte</strong></span>
@endsection

@section('page-subtitle', '')

@section('content')

    <h1 class="v2-page-title">Paramètres du compte</h1>

    <div class="v2-grid v2-grid--2col">
        {{-- ############# Information personnelle ############# --}}
        <div class="v2-card">
            <div class="v2-card__header">
                <p class="v2-card__title">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18" style="margin-right:6px;vertical-align:-3px;"><circle cx="12" cy="8" r="4"></circle><path d="M20 21a8 8 0 0 0-16 0"></path></svg>
                    Information personnelle
                </p>
            </div>

            <form class="sendForm" method="POST" action="{{ route('panel.securite.compte.update.data') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="uuid" value="{{ $collaborator['uuid'] ?? '' }}" required>

                <div class="v2-avatar-upload">
                    <img
                        @isset($collaborator['avatar'])
                            src="{{ \Illuminate\Support\Facades\Storage::url('users/avatar/'.$collaborator['uuid'].'/'.$collaborator['avatar']) }}"
                        @else
                            src="{{ asset((($collaborator['civility'] ?? '') == 'm') ? 'backoffice/man.png' : 'backoffice/woman.png') }}"
                        @endisset
                        alt="avatar" id="avatarPreview">
                    <input type="file" id="uplfile" name="avatar" class="d-none" accept="image/*">
                    <label for="uplfile">Modifier l'avatar</label>
                </div>

                <div class="v2-form-group">
                    <label class="v2-form-label">Civilité</label>
                    <select name="civility" class="v2-form-select">
                        <option value="m" @isset($collaborator['civility']) @if($collaborator['civility'] =="m") selected @endif @endisset>Monsieur</option>
                        <option value="mme" @isset($collaborator['civility']) @if($collaborator['civility'] =="mme") selected @endif @endisset>Madame</option>
                        <option value="mlle" @isset($collaborator['civility']) @if($collaborator['civility'] =="mlle") selected @endif @endisset>Demoiselle</option>
                    </select>
                </div>

                <div class="v2-form-group--row">
                    <div class="v2-form-group">
                        <label class="v2-form-label">Nom</label>
                        <input type="text" class="v2-form-input" name="firstname" value="{{ $collaborator['firstname'] ?? '' }}" required>
                    </div>
                    <div class="v2-form-group">
                        <label class="v2-form-label">Prénoms</label>
                        <input type="text" class="v2-form-input" name="lastname" value="{{ $collaborator['lastname'] ?? '' }}" required>
                    </div>
                </div>

                <div class="v2-form-group--row">
                    <div class="v2-form-group">
                        <label class="v2-form-label">Email</label>
                        <input type="email" class="v2-form-input" name="email" value="{{ $collaborator['email'] ?? '' }}" readonly>
                    </div>
                    <div class="v2-form-group">
                        <label class="v2-form-label">Téléphone</label>
                        <input type="tel" class="v2-form-input" name="phone" value="{{ $collaborator['phone'] ?? '' }}" required>
                    </div>
                </div>

                <div class="v2-card__footer">
                    <button type="submit" class="v2-btn v2-btn--primary">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                        Sauvegarder
                    </button>
                </div>
            </form>
        </div>

        {{-- ############# Sécurité du compte ############# --}}
        <div class="v2-card">
            <div class="v2-card__header">
                <p class="v2-card__title">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18" style="margin-right:6px;vertical-align:-3px;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    Sécurité du compte
                </p>
            </div>

            <form class="sendForm" method="POST" action="{{ route('panel.securite.compte.update.password') }}">
                @csrf

                <div class="v2-form-group v2-form-field">
                    <label class="v2-form-label">Ancien mot de passe</label>
                    <input type="password" class="v2-form-input v2-form-input--password" name="old_password" minlength="6" placeholder="Ancien mot de passe" required>
                    <button type="button" class="v2-form-field__toggle" data-toggle-password aria-label="Afficher/masquer le mot de passe">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </button>
                </div>

                <div class="v2-form-group v2-form-field">
                    <label class="v2-form-label">Nouveau mot de passe</label>
                    <input type="password" class="v2-form-input v2-form-input--password" name="password" minlength="6" placeholder="Nouveau mot de passe" required>
                    <button type="button" class="v2-form-field__toggle" data-toggle-password aria-label="Afficher/masquer le mot de passe">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </button>
                </div>

                <div class="v2-form-group v2-form-field">
                    <label class="v2-form-label">Confirmation</label>
                    <input type="password" class="v2-form-input v2-form-input--password" name="confirm" minlength="6" placeholder="Confirmer le mot de passe" required>
                    <button type="button" class="v2-form-field__toggle" data-toggle-password aria-label="Afficher/masquer le mot de passe">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </button>
                </div>

                <button type="submit" class="v2-btn v2-btn--navy v2-btn--block">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    Modifier
                </button>

                <div class="v2-form-hint">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                    <span>Choisissez un mot de passe suffisamment robuste, différent de votre mot de passe actuel.</span>
                </div>
            </form>
        </div>
    </div>

    {{-- ############# Reconnaissance faciale ############# --}}
    <div class="v2-card v2-face-card" style="margin-top: 20px;">
        <div class="v2-card__header">
            <div>
                <p class="v2-card__title">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18" style="margin-right:6px;vertical-align:-3px;"><path d="M2 12C2 6.5 6.5 2 12 2a10 10 0 0 1 8 4"></path><path d="M5 19.5C5.5 18 6 15 6 12a6 6 0 0 1 .34-2"></path><path d="M17.29 21.02c.12-.6.43-2.3.5-3.02"></path><path d="M12 12v0a6 6 0 0 1 0 5.5"></path><path d="M8 15v-3a4 4 0 0 1 8 0v1"></path></svg>
                    Enregistrement de l'empreinte faciale
                </p>
                <p class="v2-face-card__desc">Cette empreinte faciale pourra être utilisée lors de vos connexions ou pour l'authentification OTP.</p>
            </div>
        </div>

        <div class="v2-face-frame" id="v2-face-frame">
            <video id="video" class="video-custom" autoplay muted></video>
            <canvas id="overlay" class="overlay-custom"></canvas>
            <div id="loader" class="d-none">
                <div class="spinner-border text-primary" role="status"></div>
            </div>
            <div class="v2-face-frame__placeholder">
                <span class="v2-face-frame__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7V5a2 2 0 0 1 2-2h2"></path><path d="M17 3h2a2 2 0 0 1 2 2v2"></path><path d="M21 17v2a2 2 0 0 1-2 2h-2"></path><path d="M7 21H5a2 2 0 0 1-2-2v-2"></path><rect x="7" y="7" width="10" height="10" rx="1"></rect></svg>
                </span>
                <span>Démarrer la capture</span>
            </div>
        </div>

        <div class="v2-face-controls">
            <select id="camera-select" class="v2-form-select"></select>
            <select id="resolution-select" class="v2-form-select">
                <option value="640x480">480p</option>
                <option value="1280x720" selected>720p</option>
                <option value="1920x1080">1080p</option>
            </select>
        </div>

        <div class="v2-card__footer">
            <button type="button" id="start-scan" class="v2-btn v2-btn--primary v2-btn--block">Activer la caméra</button>
            <button type="button" id="stop-scan" class="v2-btn v2-btn--block d-none" style="margin-top: 8px; background: var(--v2-danger); color: #fff;">Désactiver la caméra</button>
        </div>
    </div>

@endsection
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
        const frame = document.getElementById('v2-face-frame');

        let stream;
        let intervalId;
        let embeddings = [];
        const MAX_EMBEDDINGS = 5;

        if (!video || !startBtn || !stopBtn || !canvas || !loader || !cameraSelect || !resolutionSelect) {
            console.error("Certains éléments DOM requis sont introuvables.");
            return;
        }

        await Promise.all([
            faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
            faceapi.nets.faceRecognitionNet.loadFromUri('/models'),
            faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
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

        function getResolution() {
            const [width, height] = resolutionSelect.value.split('x').map(Number);
            return { width, height };
        }

        async function startCamera() {
            if (!navigator.mediaDevices?.getUserMedia) {
                toastr.warning('Votre navigateur ne supporte pas la webcam.', 'Attention');
                return;
            }

            const resolution = getResolution();
            const constraints = {
                video: {
                    deviceId: cameraSelect.value ? { exact: cameraSelect.value } : undefined,
                    width: resolution.width,
                    height: resolution.height
                }
            };

            try {
                stream = await navigator.mediaDevices.getUserMedia(constraints);
                video.srcObject = stream;

                // Attendre que la vidéo soit prête
                await new Promise(resolve => {
                    if (video.readyState >= 2) resolve();
                    else video.onloadedmetadata = () => resolve();
                });

                await video.play(); // Redondant mais sûr

                const size = { width: video.videoWidth, height: video.videoHeight };
                canvas.width = size.width;
                canvas.height = size.height;
                faceapi.matchDimensions(canvas, size);

                startBtn.classList.add('d-none');
                stopBtn.classList.remove('d-none');
                if (frame) frame.classList.add('is-active');

                // Démarrer la capture directement, sans attendre 'play'
                intervalId = setInterval(() => captureEmbedding(size), 2000);

            } catch (err) {
                toastr.error("Erreur d'accès caméra", 'Erreur');
                console.error("Erreur d'accès caméra :", err);
            }
        }


        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
            clearInterval(intervalId);
            video.srcObject = null;
            startBtn.classList.remove('d-none');
            stopBtn.classList.add('d-none');
            loader.classList.add('d-none');
            if (frame) frame.classList.remove('is-active');
            embeddings.length = 0;
        }

        async function captureEmbedding(displaySize) {
            loader.classList.remove('d-none');

            const detection = await faceapi
                .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                .withFaceLandmarks()
                .withFaceDescriptor();

            const context = canvas.getContext('2d');
            context.clearRect(0, 0, canvas.width, canvas.height);

            if (!detection) {
                toastr.error("Aucun visage détecté", 'Erreur');
                loader.classList.add('d-none');
                return;
            }

            const resized = faceapi.resizeResults(detection, displaySize);
            faceapi.draw.drawDetections(canvas, resized);

            const drawBox = new faceapi.draw.DrawBox(resized.detection.box, { label: "" });
                drawBox.draw(canvas);

            const descriptor = Array.from(detection.descriptor);
            embeddings.push(descriptor);

            //console.log(`Embedding ${embeddings.length}/${MAX_EMBEDDINGS} capturé`);

            if (embeddings.length >= MAX_EMBEDDINGS) {
                clearInterval(intervalId);
                await sendEmbeddings();
                stopCamera();
            }

            loader.classList.add('d-none');
        }

        async function sendEmbeddings() {
            if (!embeddings.length) {
                toastr.warning("Aucune reconnaissance envoyée", 'Attention');
                return;
            }

            let successCount = 0;

            for (let i = 0; i < embeddings.length; i++) {
                const singleEmbedding = embeddings[i];
                if (singleEmbedding.length !== 128) {
                    console.warn(`Embedding ${i} invalide`);
                    continue;
                }

                try {
                    const res = await fetch('/panel/securite/compte/face-auth/register/save', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ inputEmbedding: singleEmbedding }),
                    });

                    const data = await res.json();
                    //console.log(`Réponse backend embedding ${i}:`, data);

                    if (data.type === 'success') {
                        successCount++;
                    } else {
                        console.warn(`Échec embedding ${i}: ${data.message}`);
                    }

                } catch (err) {
                    console.error(`Erreur réseau pour embedding ${i}:`, err);
                }
            }

            if (successCount === embeddings.length) {
                sendSuccess("Reconnaissance faciale enregistrée avec succès", '');
            } else if (successCount > 0) {
                toastr.warning(`${successCount} sur ${embeddings.length} visages enregistrés. Veuillez réessayer si besoin.`, 'Partiel');
            } else {
                toastr.error("Échec de l'enregistrement des visages", 'Erreur');
            }
        }


        startBtn.addEventListener('click', startCamera);
        stopBtn.addEventListener('click', stopCamera);
        cameraSelect.addEventListener('change', startCamera);
        resolutionSelect.addEventListener('change', startCamera);

        await listCameras();
    });
</script>

@endpush
