<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reconnaissance Faciale</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      overflow: hidden;
    }

    #video-container {
      position: relative;
      width: fit-content;
      margin: auto;
    }

    video {
      display: block;
      max-width: 100%;
    }

    canvas#overlay {
      position: absolute;
      top: 0;
      left: 0;
      z-index: 2;
    }

    #face-thumbnails {
      position: absolute;
      top: 0;
      right: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 10px;
      background-color: rgba(255, 255, 255, 0.9);
      border-left: 1px solid #ccc;
      height: 100%;
      overflow-y: auto;
      z-index: 3;
    }

    #controls {
      position: fixed;
      bottom: 20px;
      left: 20px;
      z-index: 4;
      background: rgba(255, 255, 255, 0.95);
      padding: 10px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    #controls select,
    #controls button {
      margin-right: 10px;
      padding: 4px 8px;
    }
    
  </style>
</head>
<body>

  <!-- Conteneur de la vidéo et du canvas -->
  <div id="video-container">
    <video id="video" autoplay muted playsinline></video>
    <canvas id="overlay"></canvas>
    <div id="face-thumbnails"></div>
  </div>

  <!-- Contrôles -->
  <div id="controls">
    <label for="camera-select">Caméra :</label>
    <select id="camera-select"></select>

    <label for="resolution-select">Résolution :</label>
    <select id="resolution-select">
      <option value="640x480">640x480</option>
      <option value="1280x720">1280x720</option>
      <option value="1920x1080">1920x1080</option>
    </select>

    <button id="clear-thumbnails">Effacer</button>
  </div>

  <!-- Scripts -->

    <script src="{{ asset('template/assets/libs/jquery/dist/jquery.min.js') }}"></script>

    <script src="{{ asset('template/start/jquery-3.4.1.min.js') }}"></script>

    <script src="{{ asset('backoffice/js/app_script.js') }}"></script>
    <script src="{{ asset('backoffice/js/js-loading-overlay.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="{{ asset('models/face-api.min.js') }}"></script>
    <script>
 document.addEventListener('DOMContentLoaded', async () => {
    const video = document.getElementById('video');
    const canvas = document.getElementById('overlay');
    const cameraSelect = document.getElementById('camera-select');
    const resolutionSelect = document.getElementById('resolution-select');
    const thumbnailContainer = document.getElementById('face-thumbnails');
    const clearBtn = document.getElementById('clear-thumbnails');

    let stream;
    const COOLDOWN_TIME = 5000;
    const labelCooldowns = new Map();

    await Promise.all([
        faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
        faceapi.nets.faceRecognitionNet.loadFromUri('/models'),
        faceapi.nets.faceLandmark68Net.loadFromUri('/models')
    ]);

    async function listCameras() {
        const devices = await navigator.mediaDevices.enumerateDevices();
        const videoDevices = devices.filter(device => device.kind === 'videoinput');
        cameraSelect.innerHTML = '';
        videoDevices.forEach((device, index) => {
            const option = document.createElement('option');
            option.value = device.deviceId;
            option.textContent = device.label || `Caméra ${index + 1}`;
            cameraSelect.appendChild(option);
        });
    }

    function getResolution() {
        const [width, height] = resolutionSelect.value.split('x').map(Number);
        return { width, height };
    }

    async function startCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }

        const constraints = {
            video: {
                deviceId: cameraSelect.value ? { exact: cameraSelect.value } : undefined,
                ...getResolution()
            }
        };

        stream = await navigator.mediaDevices.getUserMedia(constraints);
        video.srcObject = stream;

        await new Promise(resolve => {
            video.onloadedmetadata = () => resolve();
        });

        video.play();

        // Mise à jour du canvas à chaque nouvelle résolution
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        detectLoop();
    }

    async function detectLoop() {
        const displaySize = { width: video.videoWidth, height: video.videoHeight };
        faceapi.matchDimensions(canvas, displaySize);

        // aussi important si la résolution change pendant la détection
        canvas.width = displaySize.width;
        canvas.height = displaySize.height;

        setInterval(async () => {
            const detections = await faceapi
                .detectAllFaces(video, new faceapi.TinyFaceDetectorOptions())
                .withFaceLandmarks()
                .withFaceDescriptors();

            const resizedDetections = faceapi.resizeResults(detections, displaySize);
            const context = canvas.getContext('2d');
            context.clearRect(0, 0, canvas.width, canvas.height);

            for (const detection of resizedDetections) {
                const descriptorArray = Array.from(detection.descriptor);

                const response = await fetch('/panel/face-auth/identify', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ embedding: descriptorArray })
                });

                const result = await response.json();
                const label = result.label || 'Inconnu';
                const color = result.type === 'success' ? 'green' : 'red';
                const now = Date.now();

                if (labelCooldowns.has(label) && now - labelCooldowns.get(label) < COOLDOWN_TIME) continue;
                labelCooldowns.set(label, now);

                const drawBox = new faceapi.draw.DrawBox(detection.detection.box, { label, boxColor: color });
                drawBox.draw(canvas);

                // Miniature
                const box = detection.detection.box;
                const faceCanvas = document.createElement('canvas');
                faceCanvas.width = box.width;
                faceCanvas.height = box.height;

                const ctx = faceCanvas.getContext('2d');
                ctx.drawImage(video, box.x, box.y, box.width, box.height, 0, 0, box.width, box.height);

                const img = new Image();
                img.src = faceCanvas.toDataURL();
                img.width = 80;
                img.height = 80;
                img.style.borderRadius = "4px";
                img.style.objectFit = "cover";

                const thumbWrapper = document.createElement('div');
                thumbWrapper.style.textAlign = "center";
                thumbWrapper.style.fontSize = "12px";
                thumbWrapper.style.marginBottom = "8px";

                const nameLabel = document.createElement('div');
                nameLabel.textContent = label;
                nameLabel.style.marginTop = "4px";
                nameLabel.style.fontWeight = "bold";
                nameLabel.style.color = color;

                const timeLabel = document.createElement('div');
                const date = new Date();
                timeLabel.textContent = date.toLocaleTimeString();
                timeLabel.style.fontSize = "11px";
                timeLabel.style.color = "#666";

                thumbWrapper.appendChild(img);
                thumbWrapper.appendChild(nameLabel);
                thumbWrapper.appendChild(timeLabel);

                // Afficher en haut
                thumbnailContainer.insertBefore(thumbWrapper, thumbnailContainer.firstChild);

                if (thumbnailContainer.childElementCount > 10) {
                    thumbnailContainer.removeChild(thumbnailContainer.lastChild);
                }
            }
        }, 2000);
    }

    clearBtn.addEventListener('click', () => {
        thumbnailContainer.innerHTML = '';
        labelCooldowns.clear();
    });

    cameraSelect.addEventListener('change', startCamera);
    resolutionSelect.addEventListener('change', startCamera);

    await listCameras();
    await startCamera();
});

    </script>

</body>
</html>

