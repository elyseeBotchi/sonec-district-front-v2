

$(document).ready(function() {
    findStatus('today','all');
    findStatistique();


    function findStatus(status,paymode) {
       var libelle_status = ""
       var libelle_paymode =""

        if (status === "today") {
            libelle_status = "DU JOUR"
        }
        else if(status === "all"){
            libelle_status = ""
        }

        if (paymode === "all") {
            libelle_paymode = ""
        }
        else{
            libelle_paymode = paymode
        }



        
        document.getElementById('titre_liste').innerHTML= " <i class='fa fa-spinner fa-spin'></i> LISTE DES PAIEMENTS"+ libelle_status+ " "+libelle_paymode+" EN COURS DE CHARGEMENT ..."

        fetch(`/panel/statistique/findStatus/data/${status}/${paymode}/${Entity_uuid}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
               // 'Authorization':'Bearer '+swagger_API_KEY
            },
           // body: JSON.stringify({ admin_uuid : admin_uuid,status:status })
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Une erreur est survenue');
                }
                return response.json();
            })
            .then(data => {
                const results = data.data;
                document.getElementById('titre_liste').innerHTML= "LISTE DES PAIEMENTS " + libelle_status + " "+libelle_paymode

                // Vérifie si le tableau a déjà été initialisé
                if ($.fn.DataTable && $.fn.DataTable.isDataTable('#datatable-custom')) {
                    // Détruire l'instance existante
                    $('#datatable-custom').DataTable().destroy();
                }

                $('#datatable-custom').DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/2.0.2/i18n/fr-FR.json',
                    },
                    data: results,
                    columns: [
                        {
                            data: 'nom',
                            render: function(data, type, row) {
                                return ` ${data}  ${row.prenoms}`;
                            }
                        },
                        { data: 'telephone' },
                        { data: 'montant' },
                        { data: 'reference' },
                        { data: 'paymode' },
                        { data: 'transaction_id' },
                        {
                            data: 'state',
                            render: function(data, type, row) {
                                if(data ==="paid"){
                                    return `
                                    <span class="badge bg-success-subtle text-success"> Payé</span>
                                `;
                                }
                                else if(data ==="fail"){
                                    return `
                                    <span class="badge bg-danger-subtle text-danger"> Rejété </span>
                                `;
                                }else{
                                    return `
                                    <span class="badge bg-warning-subtle text-warning"> En attente </span>
                                `;
                                }

                            }
                        },
                        {
                            data: 'updated_at',
                            render: function(data, type, row) {
                                return `${data}`;
                            }
                        },
                    ]
                });

                // Ajouter un événement pour le bouton Détail
                $('#datatable-custom').on('click', '.btn-detail', function() {
                    const uuid = $(this).data('uuid');
                    console.log(uuid)
                    fetchCandidatDetail(uuid);
                });
            });
    }

    function findStatistique() {
        fetch(`/panel/statistique/data/count/${Entity_uuid}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                //'Authorization':'Bearer '+swagger_API_KEY
            },
           // body: JSON.stringify({ admin_uuid : admin_uuid })
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Une erreur est survenue');
                }
                return response.json();
            })
            .then(data => {
                const results = data.data;
                console.log(data);
                document.getElementById('paiement_jour_montant').innerHTML = results.paiement_jour_montant;
                document.getElementById('paiement_jour_nbre').innerHTML = results.paiement_jour_nbre;

                document.getElementById('paiement_jour_montant_wave').innerHTML = results.paiement_jour_montant_wave;
                document.getElementById('paiement_jour_nbre_wave').innerHTML = results.paiement_jour_nbre_wave;


                document.getElementById('paiement_jour_montant_orange').innerHTML = results.paiement_jour_montant_orange;
                document.getElementById('paiement_jour_nbre_orange').innerHTML = results.paiement_jour_nbre_orange;


                document.getElementById('paiement_jour_montant_mtn').innerHTML = results.paiement_jour_montant_mtn;
                document.getElementById('paiement_jour_nbre_mtn').innerHTML = results.paiement_jour_nbre_mtn;


                document.getElementById('paiement_jour_montant_moov').innerHTML = results.paiement_jour_montant_moov;
                document.getElementById('paiement_jour_nbre_moov').innerHTML = results.paiement_jour_nbre_moov;

                document.getElementById('paiement_jour_montant_tresor').innerHTML = results.paiement_jour_montant_tresor;
                document.getElementById('paiement_jour_nbre_tresor').innerHTML = results.paiement_jour_nbre_tresor;


                document.getElementById('total_paiement').innerHTML = results.total_paiement;
                document.getElementById('total_paiement_nbre').innerHTML = results.total_paiement_nbre;
            })
            .catch(error => {
                console.error('Erreur lors de la récupération des statistiques :', error);
            });
    }



    document.querySelectorAll('.Load_paiement').forEach(item => {
        item.addEventListener('click', event => {
            // Enlever la classe highlight de tous les éléments
            document.querySelectorAll('.Load_paiement').forEach(element => {
                element.classList.remove('highlight');
            });

            // Ajouter la classe highlight à l'élément cliqué
            item.classList.add('highlight');

            // Appeler la fonction findStatus avec le statut de l'élément cliqué
            const status = item.getAttribute('data-status');
            const pay = item.getAttribute('data-pay');
            findStatus(status,pay);
        });
    });


});
