$(document).ready(function() {
    findAll();

    function findAll() {
      //  alert(Entity_uuid)
        fetch(`/panel/services/taxes/rdv/today/activite/${Entity_uuid}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Une erreur est survenue lors de la récupération des données');
                }
                return response.json();
            })
            .then(data => { 
                searchData(data.historique)
            })
            .catch(error => {
                console.error('Erreur:', error);
                // Vous pouvez afficher un message utilisateur ici, comme un toast ou une alerte
                alert('Une erreur est survenue lors de la récupération des données.');
            });
    }

    
    function searchData(data) {
        if (!Array.isArray(data) || data.length === 0) {
            console.error("Les données fournies ne sont pas valides ou sont vides.");
            return;
        }
    
        console.log("Données reçues :", data);
    
        // Sélectionner le corps du tableau
        const tableBody = document.querySelector('#datatable-traitement tbody');
    
        // Vérifier si le tableau a un `tbody`, sinon en créer un
        if (!tableBody) {
            console.error("Le tableau ne contient pas de corps `<tbody>`.");
            return;
        }
    
        // Effacer les lignes existantes dans le tableau
        tableBody.innerHTML = '';
        let nombre_total = 0;

        // Boucler sur les données et créer les lignes
        data.forEach(row => {
            const tr = document.createElement('tr'); // Créer une ligne de tableau
    
            // Créer une cellule pour le service
            const tdService = document.createElement('td');
            tdService.textContent = row.service || 'N/A'; // Valeur par défaut
            tr.appendChild(tdService);
    
            // Créer une cellule pour le montant payé
            const tdMontant = document.createElement('td');
            tdMontant.textContent = row.montant_paye !== undefined ? `${row.montant_paye} F` : '0 F'; // Valeur par défaut
            tr.appendChild(tdMontant);
    
            // Créer une cellule pour le nombre
            const tdNombre = document.createElement('td');
            tdNombre.textContent = row.nombre !== undefined ? row.nombre : '0'; // Valeur par défaut
            nombre_total += row.nombre !== undefined ? row.nombre : 0;

            tr.appendChild(tdNombre);
    
            // Ajouter la ligne au tableau
            tableBody.appendChild(tr);
        });

        document.getElementById('traitement_total').innerHTML = nombre_total;

    }
    
    
    
    

});