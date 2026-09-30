$(document).ready(function() {
     $('.searchForm').submit(function (e) {
         e.preventDefault();
 
         var action = $(this).attr('action');
         var formData = new FormData(this);
         $.ajax({
             url: action,
             type: 'POST',
             data: formData,
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
             beforeSend: function () {
                 loader();
                 // Remove previous error styles and messages
                 $('.is-invalid').removeClass('is-invalid');
                 $('.invalid-feedback').remove();
             },
             success: function (data) {
                loader('hide');
                if (data.type === "success") {
                    sendSuccess(data.message, '');
                    renderHtml(data);
                    document.getElementById('formulaire').style.display = "none";
                }
                else {
                    SendError(data.message);
                    document.getElementById('html_render').innerHTML = "";
                    const printToolbarErr = document.getElementById('html_render-toolbar');
                    if (printToolbarErr) printToolbarErr.style.display = "none";
                    document.getElementById('formulaire').style.display = "block";
                    //console.log(data.penalty);

                    if(data.penalty.uuid){
                        penalty_date_begin = data.penalty.penalty_date_begin;
                        
                        if(!data.penalty.NotShow){
                            document.getElementById('penaltyGate').style.display = "block";
                            document.getElementById('penalite_structure').innerHTML = data.penalty.partner_nom || '';
                            document.getElementById('penalite_date').innerHTML = formatPenDate(penalty_date_begin || '');
                            document.getElementById('penalite_dimmatriculation').innerHTML = data.penalty.numero_dimmatriculation || '';
                        }else{
                            document.getElementById('penaltyGate').style.display = "none";
                            document.getElementById('penalite_structure').innerHTML = '';
                            document.getElementById('penalite_date').innerHTML = '';
                            document.getElementById('penalite_dimmatriculation').innerHTML = '';
                        }
                    }
                    const search = document.getElementById('search');
                    document.getElementById('numero_dimmatriculation').value = search.value;
                    search.value = "";
                }
            },
            error: function (xhr) {
                loader('hide');
                // var errors = xhr.responseJSON.errors;
                // handleErrors(errors);
                document.getElementById('html_render').innerHTML = "";
                const printToolbarErr2 = document.getElementById('html_render-toolbar');
                if (printToolbarErr2) printToolbarErr2.style.display = "none";
                SendError('Veuillez corriger les erreurs ci-dessous.');
                document.getElementById('formulaire').style.display = "block";
                
                const search = document.getElementById('search');
                document.getElementById('numero_dimmatriculation').value = search.value;
                search.value = "";
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });  
 
 });

 function formatPenDate(value) {
  const s = String(value || '').trim();
  if (!s) return '';
  const d = new Date(s.includes(' ') ? s.replace(' ', 'T') : s);
  if (isNaN(d)) return '';
  const pad = (n) => String(n).padStart(2, '0');
  return `${pad(d.getDate())}-${pad(d.getMonth() + 1)}-${d.getFullYear()} `;
}

 function renderHtml(data){

    const results = data.data;
    const entete = results.entete || [];
    const pay_element = results.pay_element || {};
    //const factures = results.factures || {};
    const entity = results.entity || {};
    const penalty = results.penaltyApplique || {};
    //console.log(penalty);
    // Vérification des données avant de les insérer dans le DOM
    if (!entity.name || !entity.front_name) {
        throw new Error("Informations de l'entité manquantes");
    }

    //document.getElementById('TaxeEntity').innerHTML = entity.name;

    let elements = document.getElementsByClassName('services');
    for (let i = 0; i < elements.length; i++) {
        elements[i].innerHTML = entity.front_name;
    }

    const printToolbar = document.getElementById('html_render-toolbar');
    const printBtn = document.getElementById('html_render-print');

    if (!Array.isArray(entete) || entete.length === 0) {
        document.getElementById('html_render').innerHTML = '<div class="v2-card"><div class="v2-card__header"><p class="v2-card__title">Aucune donnée disponible pour l\'entête</p></div></div>';
        if (printToolbar) printToolbar.style.display = 'none';
        return;
    }

    function formatDateTime(value) {
        if (!value) return '';
        const d = new Date(value);
        if (isNaN(d)) return '';
        return d.toLocaleString('fr-FR', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        if (isNaN(date)) return '';
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
    }

    // Champs dynamiques de l'entité (numéro d'immatriculation, propriétaire, carte grise, ...)
    let vehicleRows = '';
    entete.forEach(element => {
        const slugifiedName = slugify(element.name);
        if (slugifiedName === 'email' || slugifiedName === 'telephone') return;
        const value = pay_element[slugifiedName] || '';
        vehicleRows += `
            <div class="v2-detail-row">
                <span class="v2-detail-row__label">${element.name}</span>
                <span class="v2-detail-row__value">${value}</span>
            </div>`;
    });
    if (pay_element['rubrique_option_name']) {
        vehicleRows += `
            <div class="v2-detail-row">
                <span class="v2-detail-row__label">Catégorie</span>
                <span class="v2-detail-row__value">${pay_element['rubrique_option_name']}</span>
            </div>`;
    }

    // Statut du paiement
    let statusClass = 'v2-status-hero--pending';
    let statusIcon = '<circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline>';
    let statusTitle = 'En attente de validation';
    let statusBadge = '<span class="v2-status v2-status--pending">En attente</span>';
    let validatedByRow = '';

    if (pay_element['state'] === 'validate') {
        statusClass = 'v2-status-hero--success';
        statusIcon = '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline>';
        statusTitle = 'Paiement Validé';
        statusBadge = '<span class="v2-status v2-status--success">Actif</span>';
    } else if (pay_element['state'] === 'enable') {
        statusClass = 'v2-status-hero--pending';
        statusIcon = '<circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline>';
        statusTitle = 'En attente de validation';
        statusBadge = '<span class="v2-status v2-status--pending">En attente</span>';
    } else {
        statusClass = 'v2-status-hero--danger';
        statusIcon = '<circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line>';
        statusTitle = 'Paiement rejeté';
        statusBadge = '<span class="v2-status v2-status--danger">Rejeté</span>';
    }

    if (pay_element['validate_firstname'] || pay_element['validate_lastname']) {
        validatedByRow = `
            <div class="v2-agent-card">
                <div class="v2-agent-card__left">
                    <span class="v2-agent-card__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </span>
                    <div>
                        <p class="v2-agent-card__label">Validé par l'agent</p>
                        <p class="v2-agent-card__name">${pay_element['validate_firstname'] || ''} ${pay_element['validate_lastname'] || ''}</p>
                        <p class="v2-agent-card__sub">le ${formatDateTime(pay_element['validate_at'])}</p>
                    </div>
                </div>
            </div>`;
    }

    // Période de validité
    var date_actuelle = new Date().toISOString().split('T')[0];
    const dateDebutFormatted = formatDate(pay_element['date_debut']);
    const dateFinFormatted = formatDate(pay_element['date_fin']);
    let periodeValue = '';
    if (pay_element['date_fin'] && pay_element['date_fin'] > date_actuelle) {
        periodeValue = `<span class="v2-status v2-status--success">${dateDebutFormatted} → ${dateFinFormatted}</span>`;
    } else if (pay_element['date_fin'] && pay_element['date_fin'] < date_actuelle) {
        periodeValue = `<span class="v2-status v2-status--danger">${dateDebutFormatted} → ${dateFinFormatted}</span>`;
    } else {
        periodeValue = `<span class="v2-status v2-status--danger">Aucun paiement valide</span>`;
    }

    let paymentRows = `
        <div class="v2-detail-row">
            <span class="v2-detail-row__label">Taxe appliquée</span>
            <span class="v2-detail-row__value">${pay_element['rubrique_name'] || ''}</span>
        </div>`;

    if (pay_element['reference']) {
        paymentRows += `
        <div class="v2-detail-row">
            <span class="v2-detail-row__label">Référence paiement</span>
            <span class="v2-detail-row__value"><span class="v2-code">${pay_element['reference']}</span></span>
        </div>`;
    }

    if (pay_element['mode_paiement']) {
        paymentRows += `
        <div class="v2-detail-row">
            <span class="v2-detail-row__label">Mode de paiement</span>
            <span class="v2-detail-row__value">${pay_element['mode_paiement']}</span>
        </div>`;
    }

    paymentRows += `
        <div class="v2-detail-row">
            <span class="v2-detail-row__label">Période de validité</span>
            <span class="v2-detail-row__value">${periodeValue}</span>
        </div>`;

    if (pay_element['validate_at']) {
        paymentRows += `
        <div class="v2-detail-row">
            <span class="v2-detail-row__label">Date d'opération</span>
            <span class="v2-detail-row__value">${formatDateTime(pay_element['validate_at'])}</span>
        </div>`;
    }

    if (pay_element['penalty_pound_amount_total']) {
        paymentRows += `
        <div class="v2-detail-row">
            <span class="v2-detail-row__label">Montant de la pénalité</span>
            <span class="v2-detail-row__value">${pay_element['penalty_pound_amount_total']} F CFA</span>
        </div>
        <div class="v2-detail-row">
            <span class="v2-detail-row__label">Pénalité appliquée par</span>
            <span class="v2-detail-row__value">[${penalty['partner_name'] || ''}] ${penalty['admin_firstname'] || ''} ${penalty['admin_lastname'] || ''} le ${formatDateTime(penalty['created_at'])}</span>
        </div>`;
    }

    const html_render = `
        <div class="v2-status-hero ${statusClass}" style="margin-bottom: 20px;">
            <div class="v2-status-hero__left">
                <span class="v2-status-hero__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">${statusIcon}</svg>
                </span>
                <div>
                    <p class="v2-status-hero__label">Statut du paiement</p>
                    <p class="v2-status-hero__title">${statusTitle} ${statusBadge}</p>
                </div>
            </div>
            <div class="v2-status-hero__right">
                <p class="v2-status-hero__label">Montant payé</p>
                <p class="v2-status-hero__amount">${pay_element['amount'] || 0} <span class="unit">FCFA</span></p>
            </div>
        </div>

        <div class="v2-grid v2-grid--2col" style="margin-bottom: 20px;">
            <div class="v2-card">
                <div class="v2-card__header">
                    <p class="v2-card__title">Informations véhicule</p>
                </div>
                ${vehicleRows || '<p class="v2-card__subtitle">Aucune information disponible</p>'}
            </div>
            <div class="v2-card">
                <div class="v2-card__header">
                    <p class="v2-card__title">Détails du paiement</p>
                </div>
                ${paymentRows}
            </div>
        </div>

        ${validatedByRow}
    `;

    document.getElementById('html_render').innerHTML = html_render;

    if (printToolbar) printToolbar.style.display = '';
    if (printBtn) {
        if (pay_element['paiement_uuid']) {
            printBtn.dataset.paiementUuid = pay_element['paiement_uuid'];
        } else {
            delete printBtn.dataset.paiementUuid;
        }

        if (!printBtn.dataset.bound) {
            printBtn.dataset.bound = '1';
            printBtn.addEventListener('click', function () {
                if (printBtn.dataset.paiementUuid) {
                    window.location.href = '/landing/services/facturation/taxe/data/generate/carte/' + printBtn.dataset.paiementUuid;
                } else {
                    window.print();
                }
            });
        }
    }

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