<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Scanner QR Code Professionnel</title>

    <!-- Inclusion de la bibliothèque Html5Qrcode -->
    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode/minified/html5-qrcode.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Styles CSS pour un design professionnel -->
    <link rel="stylesheet" href="{{ asset('css/v2/espace-agent-v2.css') }}?v={{ filemtime(public_path('css/v2/espace-agent-v2.css')) }}">
</head>
<body>
    <!-- En-tête -->
    <header class="topbar">
        <div class="brand">
            <img src="{{ asset('template/assets/images/logo.png') }}" alt="Logo District Autonome d'Abidjan" class="brand__logo">
            <div class="brand__text">
                <span class="brand__title">{{ env('APP_NAME') ?: 'DISTRICT ABIDJAN' }}</span>
                <span class="brand__subtitle">Espace Contrôleur</span>
            </div>
        </div>

        <div class="profile-wrap">
            <button type="button" class="avatar profile-btn" aria-label="Profil">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            </button>

            <div class="dropdown-menu" style="display: none;">
                <a class="logout-link" href="javascript:void(0)" onclick="event.preventDefault(); document.getElementById('logout-form2').submit();">
                    <span>Déconnexion</span>
                </a>
                <form id="logout-form2" action="{{ route('controle.logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </header>

    <script>
        const avatar = document.querySelector('.avatar');
        const dropdownMenu = document.querySelector('.dropdown-menu');

        avatar.addEventListener('click', () => {
            dropdownMenu.style.display = dropdownMenu.style.display === 'none' ? 'block' : 'none';
        });
    </script>

    <main class="page">
        <div id="scanner-container">
            <!-- Carte caméra -->
            <div class="camera-card">
                <div id="reader"></div>
                <div class="scan-overlay">
                    <div class="scan-frame">
                        <span class="scan-frame__corner scan-frame__corner--tl"></span>
                        <span class="scan-frame__corner scan-frame__corner--tr"></span>
                        <span class="scan-frame__corner scan-frame__corner--bl"></span>
                        <span class="scan-frame__corner scan-frame__corner--br"></span>
                    </div>
                    <div class="scan-hint">Placez le QR Code au centre</div>
                </div>
            </div>

            <div id="camera-container"></div>

            <!-- Carte d'état -->
            <div class="status-card">
                <div class="status-card__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 3H5a2 2 0 0 0-2 2v2"></path><path d="M17 3h2a2 2 0 0 1 2 2v2"></path><path d="M21 17v2a2 2 0 0 1-2 2h-2"></path><path d="M7 21H5a2 2 0 0 1-2-2v-2"></path><rect x="7" y="7" width="10" height="10" rx="1"></rect></svg>
                </div>
                <p id="result">Prêt pour le scan</p>
                <p class="status-card__desc">Scannez la vignette ou la carte grise pour vérifier la conformité fiscale.</p>

                <div id="data-bien" style="display: none">
                    <div class="pricing-header">
                        <h4>Informations du Bien</h4>
                    </div>
                    <table class="table">
                        <tbody id="html_render">
                            <tr>
                                <td colspan="2">
                                    <span class="fa fa-spinner fa-spin"></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div id="data-paiement" style="display: none">
                    <div class="pricing-header">
                        <h4>Informations du paiement</h4>
                    </div>
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>Taxe</td>
                                <td id="TaxeEntity"><i class="fa fa-spinner fa-spin"></i></td>
                            </tr>
                            <tr>
                                <td>Référence du paiement</td>
                                <td id="reference"><i class="fa fa-spinner fa-spin"></i></td>
                            </tr>
                            <tr>
                                <td>Montant payé</td>
                                <td id="montant_paye"><i class="fa fa-spinner fa-spin"></i></td>
                            </tr>
                            <tr>
                                <td>Mode de paiement</td>
                                <td id="mode_paiement"><i class="fa fa-spinner fa-spin"></i></td>
                            </tr>
                            <tr>
                                <td>Date de paiement</td>
                                <td id="created_at"><i class="fa fa-spinner fa-spin"></i></td>
                            </tr>
                            <tr>
                                <td>Statut du paiement</td>
                                <td id="status_paiement"><i class="fa fa-spinner fa-spin"></i></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <footer class="page-footer">
            &copy; {{ date('Y') }} {{ env('APP_NAME') }}. Tous droits réservés.
        </footer>
    </main>

    <!-- Barre d'actions -->
    <div class="action-bar">
        <div class="action-bar__inner">
            <button class="btn-scan" id="start-btn" onclick="startScanner()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                Scanner
            </button>
            <button class="btn-scan btn-scan--stop" id="stop-btn" onclick="stopScanner('Scanner arrêté.')" style="display: none;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="6" width="12" height="12" rx="2"></rect></svg>
                Arrêter le Scan
            </button>
            <button class="btn-search" id="searchManual" onclick="openModal()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                Recherche
            </button>
        </div>
    </div>

    <!-- Modal de recherche -->
    <div id="searchModal" class="modal">
        <div class="modal-content">
            <form class="ManualSearch">
                @csrf

                <div class="modal-header">
                    <span class="close fa fa-close" onclick="closeModal()">&times;</span>
                </div>
                <div class="modal-body">
                    <label for="numeroCarteGrise">Numéro de carte grise ou d'immatriculation :</label>
                    <input type="text" id="numeroCarteGrise" class="form-control"
                        placeholder="Entrez le numéro de carte grise ou d'immatriculation"
                        pattern="^([0-9]{1,4}[A-Z]{2}[0-9]{2})|([A-Z]{2}[0-9]{1,4}[A-Z]{2})$|^[A-Z]{2}[0-9]{6}$|^[0-9]{6}[A-Z]{2}$|^[A-Z]{2}-[0-9]{4}-[A-Z]{2}$"
                        title="Le numéro doit être sous le format 1234AB01, AB1234CD, AB123456, 123456AB, ou AB-1234-CD"

                        required />
                </div>
                <div class="modal-footer">
                    <button class="btn" type="submit">Rechercher</button>
                </div>
            </form>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
       let html5QrCode; // Déclaration globale pour permettre de l'arrêter et le redémarrer

        function startScanner() {
            document.getElementById('reader').style.display = "block";
            document.getElementById('result').innerHTML = `Scanne en cours ...`;
            document.getElementById('searchManual').style.display = "none";
            document.getElementById('reference').innerHTML = '';
            document.getElementById('montant_paye').innerHTML = '';
            document.getElementById('mode_paiement').innerHTML = '';
            document.getElementById('created_at').innerHTML = '';
            document.getElementById('status_paiement').innerHTML = '';

            document.getElementById('data-bien').style.display = 'none';
            document.getElementById('data-paiement').style.display = 'none';
            document.getElementById('html_render').innerHTML = '';

            html5QrCode = new Html5Qrcode("reader");

            Html5Qrcode.getCameras().then(cameras => {
                if (cameras && cameras.length) {
                    // Créer un sélecteur de caméra
                    const cameraSelector = document.createElement('select');
                    cameraSelector.id = 'camera-selector';
                    cameraSelector.classList.add('form-control');

                    let selectedIndex = -1; // Initialise l'index pour suivre la caméra contenant "back"

                    cameras.forEach(camera => {
                        const option = document.createElement('option');
                        option.value = camera.id;
                        option.textContent = camera.label || `Caméra ${camera.id}`;

                        cameraSelector.appendChild(option);

                        // Vérifie si le label contient "back" (insensible à la casse)
                        if (camera.label && camera.label.toLowerCase().includes('back')) {
                            selectedIndex = camera.id;
                        }
                        //    alert(selectedIndex)
                    });

                    // Vérifier si le conteneur 'camera-container' existe
                    const cameraContainer = document.getElementById('camera-container');
                    if (cameraContainer) {
                        cameraContainer.innerHTML = ''; // Vider le conteneur si déjà existant
                        cameraContainer.appendChild(cameraSelector);
                    }


                    if (selectedIndex !== -1) {
                            cameraSelector.selectedIndex = selectedIndex; // Met à jour l'option sélectionnée dans <select>
                            cameraSelector.dispatchEvent(new Event('change')); // Déclenche l'événement 'change' pour assurer la prise en compte
                           // alert(`Caméra sélectionnée : ${selectedIndex}`);
                            startQrScanner(selectedIndex);
                    }else{
                        // Commencer avec la première caméra par défaut
                        let currentCameraId = cameras[0].id;
                        startQrScanner(currentCameraId);
                    }


                    // Gérer le changement de caméra
                    cameraSelector.addEventListener('change', (event) => {
                        currentCameraId = event.target.value;
                        html5QrCode.stop().then(() => {
                            startQrScanner(currentCameraId);
                        }).catch(err => {
                            console.error(`Erreur lors de l'arrêt du scanner : ${err}`);
                        });
                    });

                    // Cacher le bouton démarrer et afficher le bouton arrêter
                    document.getElementById('start-btn').style.display = 'none';
                    document.getElementById('stop-btn').style.display = 'inline-block';
                    document.getElementById('searchManual').style.display = 'none';


                }
            }).catch(err => {
                console.error(`Erreur de récupération des caméras: ${err}`);
            });
        }

        function startQrScanner(cameraId) {
            html5QrCode.start(
                cameraId,
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 }
                },
                onScanSuccess,
                onScanFailure
            ).catch(err => {
                console.error(`Erreur lors du démarrage du scanner : ${err}`);
            });
        }

        function stopScanner__(message) {
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    document.getElementById('reader').style.display = "none";
                    document.getElementById('start-btn').style.display = 'inline-block';
                    document.getElementById('stop-btn').style.display = 'none';
                    document.getElementById('searchManual').style.display = 'inline-block';

                // alert(message);
                }).catch(err => {
                    console.error(`Erreur lors de l'arrêt du scanner : ${err}`);
                });
            }
        }



        function stopScanner(message) {
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    document.getElementById('result').innerHTML = message;
                    // Afficher le bouton démarrer et cacher le bouton arrêter
                    document.getElementById('start-btn').style.display = 'inline-block';
                    document.getElementById('stop-btn').style.display = 'none';
                    document.getElementById('camera-selector').style.display = 'none';
                    document.getElementById('searchManual').style.display = "inline-block";

                }).catch(err => {
                    // Affiche un message d'erreur si l'arrêt échoue
                    console.error("Erreur lors de l'arrêt du scanner: ", err);

                    // Vérification si le flux vidéo existe
                    if (html5QrCode._localMediaStream && html5QrCode._localMediaStream.getVideoTracks().length > 0) {
                        console.warn("Flux vidéo détecté mais erreur lors de l'arrêt.");
                    } else {
                        console.warn("Aucun flux vidéo actif pour le scanner.");
                    }
                });
            } else {
                console.warn("Le scanner n'est pas initialisé.");
            }
        }



        function onScanSuccess(decodedText, decodedResult) {
            let message = `QR Code détecté: ${decodedText} <br> <code>Vérification en cours ...</code>`;
              stopScanner(message)
           //   document.getElementById('result').innerText = "Scanner arrêté.";


            // Envoyer le résultat au serveur pour vérification
            fetch(`/controle/verify/${decodedText}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                //console.log('Réponse du serveur:', data);
                 //   alert(data.message)

                    document.getElementById('reader').style.display = "none";
                if(data.type === "error"){
                    document.getElementById('result').innerHTML = `
                        <div style="padding: 10px; background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; border-radius: 5px;">
                            <span class="fa fa-exclamation-circle" style="color:#dc3545; font-size: 24px; margin-right: 10px;"></span>
                            <strong>${data.message}</strong>
                        </div>
                        <br>
                        <code>QR Code détecté: ${decodedText}</code>
                    `;

                }else if(data.type === "success"){
                    if(data.validite === 1){
                        document.getElementById('result').innerHTML = `
                            <div style="padding: 10px; background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; border-radius: 5px;">
                                <span class="fa fa-check" style="color:green; font-size: 24px; margin-right: 10px;"></span>
                                <strong>${data.message} </strong>
                            </div>
                            <br>
                            <code>QR Code détecté: ${decodedText}</code>
                        `
                    }else{
                        document.getElementById('result').innerHTML = `
                            <div style="padding: 10px; background-color: #fff3cd; border: 1px solid #ffeeba; color: #856404; border-radius: 5px;">
                                <span class="fa fa-exclamation-triangle" style="color:#856404; font-size: 24px; margin-right: 10px;"></span>
                                <strong>${data.message}</strong>
                            </div>
                            <br>
                            <code>QR Code détecté: ${decodedText}</code>
                        `;
                   }
                    // Afficher le résultat de la vérification

                const results = data.data;
                //console.log(results)
                const entete = results.entete || [];
                const pay_element = results.pay_element || {};
                const paiement = results.paiement || {};
                const entity = results.entity || {};

                // Vérification des données avant de les insérer dans le DOM
                if (!entity.name || !entity.front_name) {
                    throw new Error("Informations de l'entité manquantes");
                }

              //  document.getElementById('TaxeEntity').innerHTML = entity.name;

                let elements = document.getElementsByClassName('services');
                for (let i = 0; i < elements.length; i++) {
                    elements[i].innerHTML = entity.front_name;
                }

                let html_render = "";
                if (Array.isArray(entete) && entete.length > 0) {
                    entete.forEach(element => {

                        const slugifiedName = slugify(element.name);
                        const payElementValue = pay_element[slugifiedName] || ''; // Récupère la valeur correspondante dans pay_element

                        html_render += `
                        <tr>
                            <td>${element.name}</td>
                            <td>${payElementValue}</td>
                        </tr>`;
                    });
                } else {
                    html_render = "<tr><td colspan='2'>Aucune donnée disponible pour l'entête</td></tr>";
                }

                document.getElementById('html_render').innerHTML = html_render;

                // Vérification des données du paiement avant de les afficher
                if (paiement.reference && paiement.amount && paiement.operateur_uuid && paiement.updated_at && paiement.status) {
                    document.getElementById('reference').innerHTML = paiement.reference;
                    document.getElementById('montant_paye').innerHTML = paiement.amount;
                    document.getElementById('mode_paiement').innerHTML = paiement.operateur_uuid;
                    document.getElementById('created_at').innerHTML = paiement.updated_at;
                    document.getElementById('status_paiement').innerHTML = paiement.state;

                    document.getElementById('data-bien').style.display = 'block';
                    document.getElementById('data-paiement').style.display = 'block';

                } else {
                    console.warn('Informations de paiement manquantes ou incomplètes.');
                }
                }
                // Traitez la réponse du serveur si nécessaire
            })
            .catch((error) => {
                console.error('Erreur lors de l\'envoi des données:', error);
            });
        }

        function onScanFailure(error) {
            console.warn(`Erreur de scan: ${error}`);
        }


        function slugify(string) {
            // Remplacer les espaces et les caractères spéciaux par des tirets, et convertir en minuscule
             var data = string.toString().toLowerCase()
            .replace(/\s+/g, '-')           // Remplace les espaces par des tirets
            .replace(/[^\w\-]+/g, '')       // Supprime tous les caractères non alphanumériques
            .replace(/\-\-+/g, '-')         // Remplace les doubles tirets par un seul tiret
            .replace(/^-+/, '')             // Supprime les tirets au début
            .replace(/-+$/, '');

            return convertSlugToName(data) ;
        }

        function convertSlugToName(slug) {
            // Remplacer les tirets par des underscores
            return slug.replace(/-/g, '_');
        }



    // Fonction pour ouvrir le modal
    function openModal() {
        document.getElementById('searchModal').style.display = 'flex';
    }

    // Fonction pour fermer le modal
    function closeModal() {
        document.getElementById('searchModal').style.display = 'none';
    }

    // Assurer que le modal est fermé au chargement de la page
    window.onload = function() {
        closeModal();
    }

  // Create
  $(".ManualSearch").on('submit', function (e) {

        e.preventDefault();
        const action = $(this).attr('action');
        const formData = new FormData(this);
        recherche();
});



    function recherche(){
        const decodedText = document.getElementById('numeroCarteGrise').value;

        let message = `Code détecté: ${decodedText} <br> <code>Vérification en cours ...</code>`;
        document.getElementById('result').innerHTML = message;
        closeModal();
        //   document.getElementById('result').innerText = "Scanner arrêté.";


        // Envoyer le résultat au serveur pour vérification
        fetch(`/controle/manual/verify/${decodedText}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            //console.log('Réponse du serveur:', data);
                //alert(data.message)

                document.getElementById('reader').style.display = "none";
            if(data.type === "error"){
                document.getElementById('result').innerHTML = `
                    <div style="padding: 10px; background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; border-radius: 5px;">
                        <span class="fa fa-exclamation-circle" style="color:#dc3545; font-size: 24px; margin-right: 10px;"></span>
                        <strong>${data.message}</strong>
                    </div>
                    <br>
                    <code>Code détecté: ${decodedText}</code>
                `;

            }else if(data.type === "success"){
                if(data.validite === 1){
                    document.getElementById('result').innerHTML = `
                        <div style="padding: 10px; background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; border-radius: 5px;">
                            <span class="fa fa-check" style="color:green; font-size: 24px; margin-right: 10px;"></span>
                            <strong>${data.message} </strong>
                        </div>
                        <br>
                        <code>QR Code détecté: ${decodedText}</code>
                    `
                }else{
                    document.getElementById('result').innerHTML = `
                        <div style="padding: 10px; background-color: #fff3cd; border: 1px solid #ffeeba; color: #856404; border-radius: 5px;">
                            <span class="fa fa-exclamation-triangle" style="color:#856404; font-size: 24px; margin-right: 10px;"></span>
                            <strong>${data.message}</strong>
                        </div>
                        <br>
                        <code>QR Code détecté: ${decodedText}</code>
                    `;
            }
                // Afficher le résultat de la vérification

            const results = data.data;
            //console.log(results)
            const entete = results.entete || [];
            const pay_element = results.pay_element || {};
            const paiement = results.paiement || {};
            const entity = results.entity || {};

            // Vérification des données avant de les insérer dans le DOM
            if (!entity.name || !entity.front_name) {
                throw new Error("Informations de l'entité manquantes");
            }

        //  document.getElementById('TaxeEntity').innerHTML = entity.name;

            let elements = document.getElementsByClassName('services');
            for (let i = 0; i < elements.length; i++) {
                elements[i].innerHTML = entity.front_name;
            }

            let html_render = "";
            if (Array.isArray(entete) && entete.length > 0) {
                entete.forEach(element => {

                    const slugifiedName = slugify(element.name);
                    const payElementValue = pay_element[slugifiedName] || ''; // Récupère la valeur correspondante dans pay_element

                    html_render += `
                    <tr>
                        <td>${element.name}</td>
                        <td>${payElementValue}</td>
                    </tr>`;
                });
            } else {
                html_render = "<tr><td colspan='2'>Aucune donnée disponible pour l'entête</td></tr>";
            }

            document.getElementById('html_render').innerHTML = html_render;

            // Vérification des données du paiement avant de les afficher
            if (paiement.reference && paiement.amount && paiement.operateur_uuid && paiement.updated_at && paiement.status) {
                document.getElementById('reference').innerHTML = paiement.reference;
                document.getElementById('montant_paye').innerHTML = paiement.amount;
                document.getElementById('mode_paiement').innerHTML = paiement.operateur_uuid;
                document.getElementById('created_at').innerHTML = paiement.updated_at;
                document.getElementById('status_paiement').innerHTML = paiement.state;

                document.getElementById('data-bien').style.display = 'block';
                document.getElementById('data-paiement').style.display = 'block';

            } else {
                console.warn('Informations de paiement manquantes ou incomplètes.');
            }
            }
            // Traitez la réponse du serveur si nécessaire
        })
        .catch((error) => {
            console.error('Erreur lors de l\'envoi des données:', error);
        });
    }
    </script>


</body>
</html>
