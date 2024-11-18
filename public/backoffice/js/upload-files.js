
// function refreshTable(myTable,url,target) {


//     // Obtenez une référence à la table
//     var table = document.getElementById(myTable);
//     table.innerHTML="";
//     document.getElementById(target).innerHTML =' Chargement en cours ...';
//     fetch(url, {
//         method: 'POST',
//         body: JSON.stringify({
//             target: target,
//             _token:'{{ csrf_token() }}'
//         }),
//         headers: {
//             'Content-Type': 'application/json'
//         }
//     })
//         .then(response => response.json())
//         .then(data => {

//             /*##############################################################################*/
//             /*##############################################################################*/
//             /*##############################################################################*/
//             table.innerHTML=data.DataReturn;
//             /*##############################################################################*/
//             /*##############################################################################*/
//             /*##############################################################################*/
//             document.getElementById(target).innerHTML =' ';
//         })
//         .catch(error => {
//             console.error(error)
//             document.getElementById(target).innerHTML =' echec du chargement';
//         })
// }

// const form = document.querySelector('form');
// const inputFichier = document.querySelector('#fichier');
// const divFichiersCharges = document.querySelector('#fichiers-charges');

// inputFichier.addEventListener('change', () => {
//     for (const fichier of inputFichier.files) {
//         const barreProgression = document.createElement('div');
//         barreProgression.classList.add('progress', 'my-2');

//         const champCache = document.createElement('input');
//         champCache.setAttribute('type', 'hidden');
//         champCache.setAttribute('name', 'docs[]');

//         const barreProgressionInterne = document.createElement('div');
//         barreProgressionInterne.classList.add('progress-bar', 'progress-bar-striped', 'progress-bar-animated');
//         barreProgressionInterne.setAttribute('role', 'progressbar');
//         barreProgressionInterne.setAttribute('aria-valuemin', '0');
//         barreProgressionInterne.setAttribute('aria-valuemax', '100');
//         barreProgressionInterne.setAttribute('aria-valuenow', '0');
//         barreProgressionInterne.style.width = '0%';
//         barreProgressionInterne.innerText = `Chargement de ${fichier.name}...`;

//         const boutonSupprimer = document.createElement('button');
//         boutonSupprimer.classList.add('btn', 'btn-sm', 'btn-danger', 'ml-2');
//         boutonSupprimer.innerHTML = '<i class="bx bx-x"></i>';
//         boutonSupprimer.addEventListener('click', () => {
//             barreProgression.remove();
//             inputFichierCaches.forEach((inputFichierCache, index) => {
//                 if (inputFichierCache.value === barreProgression.dataset.url) {
//                     inputFichierCache.remove();
//                     inputFichierCaches.splice(index, 1);
//                 }
//             });
//         });

//         barreProgression.dataset.url = '';
//         barreProgression.appendChild(barreProgressionInterne);
//         barreProgression.appendChild(boutonSupprimer);
//         divFichiersCharges.appendChild(barreProgression);

//         const xhr = new XMLHttpRequest();
//         xhr.open('POST', '/switch/conector/gestion/fichiers/upload-tmp-document');
//         xhr.upload.addEventListener('progress', (e) => {
//             const pourcentage = (e.loaded / e.total) * 100;
//             barreProgressionInterne.setAttribute('aria-valuenow', pourcentage);
//             barreProgressionInterne.style.width = `${pourcentage}%`;
//             barreProgressionInterne.innerText = `Chargement de ${fichier.name}... ${Math.round(pourcentage)}%`;
//         });
//         xhr.addEventListener('load', () => {
//             barreProgressionInterne.classList.remove('progress-bar-striped', 'progress-bar-animated');
//             barreProgressionInterne.classList.add('bg-success');
//             barreProgressionInterne.innerText = `${fichier.name} chargé avec succès !`;

//             // Ajout du chemin du fichier à un champ caché du formulaire
//             champCache.setAttribute('value', xhr.responseText);
//             barreProgression.appendChild(champCache);
//         });
//         xhr.addEventListener('error', () => {
//             barreProgressionInterne.classList.remove('progress-bar-striped', 'progress-bar-animated');
//             barreProgressionInterne.classList.add('bg-danger');
//             barreProgressionInterne.innerText = `Erreur lors du chargement de ${fichier.name} !`;
//         });
//         const formData = new FormData();
//         formData.append('fichier', fichier);
//         xhr.send(formData);
//     }
// });

// // Tableau pour stocker les champs cachés pour chaque fichier téléchargé
// const inputFichierCaches = [];
