<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scanner QR Code Professionnel</title>
    
    <!-- Inclusion de la bibliothèque Html5Qrcode -->
    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode/minified/html5-qrcode.min.js"></script>
    
    <!-- Styles CSS pour un design professionnel -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fa;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 80vh;
        }

        h1 {
            font-size: 24px;
            color: #34495e;
            margin-bottom: 20px;
        }

        #scanner-container {
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            padding: 15px; /* Réduire le padding */
            max-width: 310px; /* Réduire la largeur maximale */
            width: 100%;
            text-align: center;
        }

        #reader {
            width: 100%;
            height: auto;
            min-height: 200px; /* Réduire la hauteur minimale */
            border: 2px dashed #3498db;
            border-radius: 10px;
            margin-bottom: 15px; /* Réduire la marge */
        }

        table.table td {
            padding: 8px; /* Réduire le padding des cellules */
            border-bottom: 1px solid #ddd;
            vertical-align: middle;
            text-align: justify;
        }

        table.table td:first-child {
            font-weight: bold;
            text-align: left;
        }

        table.table td:last-child {
            text-align: left;
        }



        #result {
            background-color: #ecf0f1;
            padding: 15px;
            border-radius: 10px;
            font-size: 16px;
            color: #2c3e50;
            border: 1px solid #bdc3c7;
        }

        .btn {
            background-color: #3498db;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            margin: 10px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        .btn-stop {
            background-color: #e74c3c;
        }

        .btn-stop:hover {
            background-color: #c0392b;
        }

        .avatar {
            border-radius: 50%;
            width: 80px;
            height: 80px;
            object-fit: cover;
            margin-bottom: 20px;
        }

        .logout-link {
            color: #e74c3c;
            font-size: 16px;
            text-decoration: none;
            display: block;
            margin: 20px 0;
        }

        .logout-link:hover {
            text-decoration: underline;
        }

        footer {
            margin-top: 20px;
            font-size: 12px;
            color: #95a5a6;
        }

        #data-bien, #data-paiement {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .pricing-header h4 {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
        }

        table.table {
            width: 100%;
            border-collapse: collapse;
        }

    
        /* Spinners styles */
        .fa-spinner {
            margin-right: 5px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            #data-bien, #data-paiement {
                padding: 15px;
            }

            .pricing-header h4 {
                font-size: 1.25rem;
            }

            table.table td {
                padding: 8px;
            }
        }


        /* Style général pour la modal */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1000; 
            left: 0; 
            top: 0; 
            width: 100%; 
            height: 100%; 
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Contenu de la modal */
        .modal-content {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.3s ease-in-out;
        }

        /* Animation d'apparition */
        @keyframes fadeIn {
            from {opacity: 0;}
            to {opacity: 1;}
        }

        /* Header de la modal */
        .modal-header {
            display: flex;
            justify-content: flex-end;
            padding-bottom: 10px;
        }

        /* Bouton de fermeture */
        .modal-header .close {
            cursor: pointer;
            font-size: 24px;
            color: #333;
            transition: color 0.3s;
        }

        .modal-header .close:hover {
            color: #f44336;
        }

        /* Corps de la modal */
        .modal-body {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }

        .modal-body label {
            font-size: 16px;
            margin-bottom: 8px;
            color: #333;
        }

        .modal-body .form-control {
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: border-color 0.3s;
            width: 100%;
            box-sizing: border-box;
        }

        .modal-body .form-control:focus {
            outline: none;
            border-color: #007bff;
        }

        /* Footer de la modal */
        .modal-footer {
            display: flex;
            justify-content: center;
        }

        .modal-footer .btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .modal-footer .btn:hover {
            background-color: #0056b3;
        }

        .form-control {
        display: block;
        width: 100%;
        height: calc(1.5em + .75rem + 2px);
        padding: .375rem .75rem;
        font-size: 1rem;
        line-height: 1.5;
        color: #4F5467;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #e9ecef;
        border-radius: 2px;
        transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }

        /* Responsivité */
        @media (max-width: 768px) {
            .modal-content {
                max-width: 80%;
            }

            .modal-body label {
                font-size: 14px;
            }

            .modal-body .form-control {
                font-size: 12px;
            }

            .modal-footer .btn {
                padding: 8px 16px;
                font-size: 14px;
            }
        }


    </style>
</head>
<body>
        <!-- Avatar et lien de déconnexion -->
        <div style="display: flex; align-items: center;">
            <h1 style="margin-right: 15px;">{{ env('APP_NAME') }}</h1>
            
            <div style="position: relative;">
                <img
                    @isset(AuthConnect()['avatar'])
                        src="{{ \Illuminate\Support\Facades\Storage::url('users/avatar/'.AuthConnect()['uuid'].'/'.AuthConnect()['avatar']) }}"
                    @else
                        @isset(AuthConnect()['civility'])
                            src="{{ asset(AuthConnect()['civility'] == 'm' ? 'backoffice/man.png' : 'backoffice/woman.png') }}"
                        @else
                            src="{{ asset('backoffice/man.png') }}"
                        @endisset
                    @endisset
                    alt="user" class="avatar" style="width: 30px; height: 30px; border-radius: 50%; cursor: pointer;">
                
                <div class="dropdown-menu" style="display: none; position: absolute; right: 0;">
                    <a class="logout-link" href="javascript:void(0)" onclick="event.preventDefault(); document.getElementById('logout-form2').submit();">
                        <span>Déconnexion</span>
                    </a>
                    <form id="logout-form2" action="{{ route('controle.logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
        
        <script>
            const avatar = document.querySelector('.avatar');
            const dropdownMenu = document.querySelector('.dropdown-menu');
        
            avatar.addEventListener('click', () => {
                dropdownMenu.style.display = dropdownMenu.style.display === 'none' ? 'block' : 'none';
            });
        </script>
        
    <center>
        <img src="{{ asset('template/assets/images/logo.png') }}" width="60px" alt="homepage" class="dark-logo" />
    </center>

    <div id="scanner-container">
        <div id="reader"></div>
        <p id="result">Scan un QR Code pour voir le résultat ici</p>
        <div id="data-bien"  style="display: none">
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
                        <td id="TaxeEntity"> <i class="fa fa-spinner fa-spin"></i> </td>
                    </tr>
                    <tr>
                        <td>Référence du paiement</td>
                        <td id="reference"> <i class="fa fa-spinner fa-spin"></i> </td>
                    </tr>
                    <tr>
                        <td>Montant payé</td>
                        <td id="montant_paye"> <i class="fa fa-spinner fa-spin"></i> </td>
                    </tr>
                    <tr>
                        <td>Mode de paiement</td>
                        <td id="mode_paiement"> <i class="fa fa-spinner fa-spin"></i> </td>
                    </tr>
                    <tr>
                        <td>Date de paiement</td>
                        <td id="created_at"> <i class="fa fa-spinner fa-spin"></i> </td>
                    </tr>
                    <tr>
                        <td>Statut du paiement</td>
                        <td id="status_paiement"> <i class="fa fa-spinner fa-spin"></i> </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Boutons pour démarrer et arrêter le scanner -->
        <div class="row">
            <div id="camera-container"></div>
             <button class="btn" id="start-btn" onclick="startScanner()">Démarrer le Scan</button>
             <button class="btn btn-stop" id="stop-btn" onclick="stopScanner('Scanner arrêté.')" style="display: none;">Arrêter le Scan</button>
            <button class="btn" id="searchManual" onclick="openModal()">Recherche</button>
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
                    <button class="btn" type="submit" >Rechercher</button>
                </div>
            </form>
            
        </div>
    </div>



    <footer>
        &copy; 2024 {{ env('APP_NAME') }}. Tous droits réservés.
    </footer>
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
                            selectedIndex = index;
                        }

                       

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
                           // alert(`Caméra sélectionnée : ${cameras[selectedIndex].label}`);
                            startQrScanner(cameras[selectedIndex].id);
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
                    //alert(data.message)
                    
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
            console.log('Réponse du serveur:', data);
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
