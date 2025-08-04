@extends('layout.adminApp')

@section('content')

    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0">Mon compte</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">Sécurité</li>
                    <li class="breadcrumb-item active">Mon compte</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row" id="container">
         <div class="page-container col-sm-12">

            <div class="tab-content">
                <div class="tab-pane show active" id="profile-2" role="tabpanel" aria-labelledby="profile-tab-2">
                    <div class="row">
                         <div class="col-lg-6">
                             <div class="card">
                                 <div class="card-header">
                                     <h5>Information personnelle</h5>
                                 </div>
                                 <form class="sendForm" method="POST" action="{{ route('panel.securite.compte.update.data') }}" enctype="multipart/form-data">
                                     @csrf
                                     <div class="card-body">
                                         <div class="row">

                                             {{--###########################--}}
                                             <div class="col-sm-12 text-center mb-3">
                                                 <div class="user-upload wid-75">
                                                     <img
                                                         @isset($collaborator['avatar'])
                                                             src="{{ \Illuminate\Support\Facades\Storage::url('users/avatar/'.$collaborator['uuid'].'/'.$collaborator['avatar']) }}"
                                                         @else
                                                             src="{{ asset($collaborator['civility'] == 'm' ? 'backoffice/man.png' : 'backoffice/woman.png') }}"
                                                         @endisset
                                                         alt="img" class="rounded-circle img-fluid" style="width: 100px" id="avatarPreview">

                                                     <input type="file" id="uplfile" name="avatar" class="d-none" accept="image/*">
                                                 </div>
                                                 <label for="uplfile" class="img-avtar-upload">
                                                     <i class="fa fa-camera f-24 mb-1"></i>
                                                     <span>Modifier l'avatar</span>
                                                 </label>
                                             </div>

                                             {{--###########################--}}
                                             <div class="col-sm-12">
                                                 <input type="hidden" name="uuid" value="{{ $collaborator['uuid'] ?? '' }}" required >
                                                 <div class="form-group">
                                                     <label class="form-label">Civilité</label>

                                                     <select name="civility" id="" class="form-control">
                                                        <option value="m" @isset($collaborator['civility']) @if($collaborator['civility'] =="m") selected @endif @endisset >Monsieur</option>
                                                        <option value="mme" @isset($collaborator['civility']) @if($collaborator['civility'] =="mme") selected @endif @endisset >Madame</option>
                                                        <option value="mlle" @isset($collaborator['civility']) @if($collaborator['civility'] =="mlle") selected @endif @endisset >Demoiselle</option>
                                                     </select>
                                                 </div>
                                             </div>
                                             <div class="col-sm-6">
                                                 <input type="hidden" name="uuid" value="{{ $collaborator['uuid'] ?? '' }}" required >
                                                 <div class="form-group">
                                                     <label class="form-label">Nom</label>
                                                     <input type="text" class="form-control" name="firstname" value="{{ $collaborator['firstname'] ?? '' }}" required>
                                                 </div>
                                             </div>
                                             <div class="col-sm-6">
                                                 <div class="form-group">
                                                     <label class="form-label">Prénoms</label>
                                                     <input type="text" class="form-control" name="lastname" value="{{ $collaborator['lastname'] ?? '' }}" required>
                                                 </div>
                                             </div>
                                             <div class="col-sm-6">
                                                 <div class="form-group">
                                                     <label class="form-label">Email</label>
                                                     <input type="email" class="form-control" name="email" value="{{ $collaborator['email'] ?? '' }}" readonly />
                                                 </div>
                                             </div>
                                             <div class="col-sm-6">
                                                 <div class="form-group">
                                                     <label class="form-label">Telephone</label>
                                                     <input type="tel" class="form-control" name="phone" value="{{ $collaborator['phone'] ?? '' }}" required>
                                                 </div>
                                             </div>
                                         </div>
                                     </div>
                                     <div class="card-footer">
                                         <button type="submit" class="btn btn-rounded btn-outline-primary">Sauvegarder</button>
                                     </div>
                                 </form>
                            </div>
                         </div>
                         <div class="col-lg-6">
                             <div class="card">
                                 <div class="card-header">
                                     <h5>Modification du compte</h5>
                                 </div>

                                            <form class="sendForm" method="POST" action="{{ route('panel.securite.compte.update.password')}}" enctype="multipart/form-data">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Ancien Mot de passe</label>
                                                                <input type="password" class="form-control" name="old_password" min="6" placeholder="Ancien mot de passe" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Nouveau mot de passe</label>
                                                                <input type="password" class="form-control" name="password" min="6" placeholder="Nouveau mot de passe" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Confirmation du mot de passe</label>
                                                                <input type="password" name="confirm" class="form-control" min="6" required />
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <button type="submit" class="btn btn-rounded btn-outline-primary">Modifier</button>
                                                </div>
                                            </form>


                                 </div>
                             </div>

                         </div>

                         
                        <div class="col-lg-6">
                            <div class="card">
                                 <div class="card-header">
                                     <h5>Enregistrement de l'empreinte faciale</h5>
                                     <div class="alert alert-info">
                                        Cettte empreinte faciale pourrait etre utilisé lors de vos connexions ou pour l'authentification OTP
                                     </div>
                                 </div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="card-body p-5">
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
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="button" id="start-scan" class="btn btn-primary w-100">Activer la caméra</button>
                                    <button type="button" id="stop-scan" class="btn btn-danger w-100 d-none mt-2">Désactiver la caméra</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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

            console.log(`Embedding ${embeddings.length}/${MAX_EMBEDDINGS} capturé`);

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
                    console.log(`Réponse backend embedding ${i}:`, data);

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
