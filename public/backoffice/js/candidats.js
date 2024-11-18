

$(document).ready(function() {
    findStatus('pending');
    findStatistique();
    function findAll() {
        document.getElementById('titre_liste').innerHTML= " <i class='fa fa-spinner fa-spin'></i> LISTE DES CANDIDATS INSCRITS EN COURS DE CHARGEMENT ..."

        fetch(`/panel/inscription/candidat/findAll`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Une erreur est survenue');
                }
                return response.json();
            })
            .then(data => {
                const results = data.data;
                document.getElementById('titre_liste').innerHTML= "LISTE DES CANDIDATS INSCRITS"

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
                        { data: 'nom' },
                        { data: 'prenoms' },
                        { data: 'email' },
                        { data: 'portable' },
                        { data: 'date_naissance' },
                        { data: 'lieu_naissance' },
                        {
                            data: 'state',
                            render: function(data, type, row) {
                                if(data ==="success"){
                                    return `
                                    <span class="badge bg-success-subtle text-success"> Validé</span>
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
                            data: 'uuid',
                            render: function(data, type, row) {
                                return `
                                    <button class="btn btn-sm btn-outline-info waves-effect waves-light material-shadow-none btn-detail" data-uuid="${data}"><i class="ri-menu-2-line"></i> Détail</button>
                                `;
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

    function findStatus(status) {
       var libelle_status = ""
        if (status === "success") {
            libelle_status = "VALIDES"
        }
        else if(status === "fail"){
            libelle_status = "REJETES"
        }
        else if(status === "pending"){
            libelle_status = "EN ATTENTE DE DECISION"
        }
        document.getElementById('titre_liste').innerHTML= " <i class='fa fa-spinner fa-spin'></i> LISTE DES CANDIDATS"+ libelle_status+ " EN COURS DE CHARGEMENT ..."

        fetch(`/panel/inscription/candidat/findStatus/${status}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Une erreur est survenue');
                }
                return response.json();
            })
            .then(data => {
                const results = data.data;
                document.getElementById('titre_liste').innerHTML= "LISTE DES CANDIDATS INSCRITS " + libelle_status

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
                        { data: 'nom' },
                        { data: 'prenoms' },
                        { data: 'email' },
                        { data: 'portable' },
                        { data: 'date_naissance' },
                        { data: 'lieu_naissance' },
                        {
                            data: 'state',
                            render: function(data, type, row) {
                                if(data ==="success"){
                                    return `
                                    <span class="badge bg-success-subtle text-success"> Validé</span>
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
                            data: 'uuid',
                            render: function(data, type, row) {
                                return `
                                    <button class="btn btn-sm btn-outline-info waves-effect waves-light material-shadow-none btn-detail" data-uuid="${data}"><i class="ri-menu-2-line"></i> Détail</button>
                                `;
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
        fetch(`/panel/inscription/candidat/findStatistique`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Une erreur est survenue');
                }
                return response.json();
            })
            .then(data => {
                const results = data.data;
                document.getElementById('total_inscrit').innerHTML = results.total_inscrit;
                document.getElementById('total_attente').innerHTML = results.total_attente;
                document.getElementById('total_valide').innerHTML = results.total_valide;
                document.getElementById('total_rejete').innerHTML = results.total_rejete;
            })
            .catch(error => {
                console.error('Erreur lors de la récupération des statistiques :', error);
            });
    }

    function fetchCandidatDetail(uuid) {
        loader()
        fetch(`/panel/inscription/candidat/detail/${uuid}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Une erreur est survenue');
                }
                return response.json();
            })
            .then(data => {

                const result = data.data;
                document.getElementById('detail_numero_table').textContent = result.numero_table;
                document.getElementById('detail_numero_inscription').textContent = result.code;
                document.getElementById('detail_nom').innerHTML =  result.civilite+" "+result.nom+" "+result.prenoms;
                document.getElementById('detail_date_naissance').innerHTML = result.date_naissance+" à "+result.lieu_naissance;
                document.getElementById('detail_type_piece').textContent = result.type_piece;
                document.getElementById('detail_email').textContent = result.email;
                document.getElementById('detail_portable').textContent = result.portable;
                document.getElementById('detail_nombre_enfant').textContent = result.nombre_enfant;
                document.getElementById('detail_nationalite').textContent = result.nationalite;
                // Suppose that result.date_piece is in a format that can be parsed by Date constructor
                const datePiece = new Date(result.date_piece);
                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                const date_pieceFormat = datePiece.toLocaleDateString('fr-FR', options);

                document.getElementById('detail_date_piece').innerHTML = result.type_piece + " établie le " + date_pieceFormat + " sur le n° " + result.numero_piece;

                document.getElementById('detail_nom_pere').textContent = result.nom_pere;
                document.getElementById('detail_nom_mere').textContent = result.nom_mere;

                document.getElementById('detail_adresse').textContent = result.adresse;
                document.getElementById('detail_region').textContent = result.region;

                document.getElementById('detail_departement').textContent = result.departement;
                document.getElementById('detail_commune').textContent = result.commune;
                document.getElementById('detail_residence').textContent = result.residence;
                const dateDiplome = new Date(result.date_diplome);
                const date_diplomeFormat = dateDiplome.toLocaleDateString('fr-FR', options);

                document.getElementById('detail_libelle_diplome').textContent = result.libelle_diplome +" obtenu le "+date_diplomeFormat+" sur le n°"+result.numero_diplome;
                const dateRdv = new Date(result.date_rdv);
                document.getElementById('detail_date_rdv').textContent = dateRdv.toLocaleDateString('fr-FR', options);
                let result_statut =""
                    if(result.state ==="success"){
                         result_statut = '<span class="badge bg-success-subtle text-success"> Validé</span>'
                    }
                    else if(result.state ==="fail"){
                         result_statut = '<span class="badge bg-danger-subtle text-danger"> Rejeté</span>'
                    }
                    else if(result.state ==="pending"){
                         result_statut = '<span class="badge bg-warning-subtle text-warning"> En attente</span>'
                    }else{
                         result_statut = '<span class="badge bg-info-subtle text-info">'+ result.state +' </span>'
                    }

                $('#validerCandidatButton').data('uuid', result.uuid);
                $('#rejeterCandidatButton').data('uuid', result.uuid);


                document.getElementById('detail_statut').innerHTML = result_statut;
                    if(result.avatar !=="" && result.avatar !==null){
                        document.getElementById('candidat-avatar').src ="/uploads/profile-photos/"+result.avatar
                    }else{
                        document.getElementById('candidat-avatar').src ="/assets/images/users/avatar.png"
                    }
                if(result.state !== "pending"){
                    document.getElementById('detail_footer').style.display = "none"

                    $('#validerCandidatButton').data('uuid', "");
                    $('#rejeterCandidatButton').data('uuid', "");

                }


                loader('hide')

                $('#candidatDetailModal').modal('show');
            });
    }

    $('#validerCandidatButton').on('click', function() {
        const uuid = $(this).data('uuid');
        updateCandidatStatus(uuid, 'success');
    });

    $('#rejeterCandidatButton').on('click', function() {
        const uuid = $(this).data('uuid');
        updateCandidatStatus(uuid, 'fail');
    });

    function updateCandidatStatus(uuid, status) {
       // alert(uuid)
        loader()
        fetch(`/panel/inscription/candidat/${uuid}/updateStatus`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({ status: status, admin_uuid : admin_uuid ,_token : _token})
        })
            .then(response => {
                if (!response.ok) {
                    loader('hide')
                    throw new Error('Une erreur est survenue');
                }
                return response.json();
            })
            .then(data => {
                loader('hide')
                if (data.type === 'success') {
                    sendSuccess(data.message);
                    $('#candidatDetailModal').modal('hide');
                    findStatus('pending'); // Rafraîchit le tableau après la mise à jour
                    findStatistique();
                } else {
                    SendError(data.message);
                }
            })
            .catch(error => {
                loader('hide')
                console.error('Erreur lors de la mise à jour du statut du candidat :', error);
                SendError('Une erreur est survenue lors de la mise à jour du statut du candidat.');
            });
    }


    document.querySelectorAll('.Load_candidat').forEach(item => {
        item.addEventListener('click', event => {
            // Enlever la classe highlight de tous les éléments
            document.querySelectorAll('.Load_candidat').forEach(element => {
                element.classList.remove('highlight');
            });

            // Ajouter la classe highlight à l'élément cliqué
            item.classList.add('highlight');

            // Appeler la fonction findStatus avec le statut de l'élément cliqué
            const status = item.getAttribute('data-status');
            findStatus(status);
        });
    });

   /* $('.Load_candidat').on('click', function() {
        const status = $(this).data('status');
        findStatus(status);
    });*/
});
