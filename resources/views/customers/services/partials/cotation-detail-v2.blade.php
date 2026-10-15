{{-- Détail de la demande de cotation — balisage V2 (voir public/css/v2/detail-cotation-v2.css).
     Mêmes ids que l'ancien balisage : detail_cheque.js les remplit sans modification. --}}
<div class="cdv2-header">
    <div>
        <h1 class="cdv2-title">Détail de la demande de cotation <span id="libelle-cheque"></span></h1>
        <p class="cdv2-ref">Référence: <b id="reference-cotation" class="cdv2-ref__code"><i class="fa fa-spinner fa-spin"></i></b></p>
    </div>
    <span id="statut-cotation"><i class="fa fa-spinner fa-spin"></i></span>
</div>

<section class="cdv2-card">
    <header class="cdv2-card__head cdv2-card__head--line">
        <h2 class="cdv2-card__title">Informations générales</h2>
    </header>
    <div class="cdv2-info-grid">
        <div class="cdv2-field">
            <span class="cdv2-field__label">Libellé</span>
            <b class="cdv2-field__value" id="libelle-cotation"><i class="fa fa-spinner fa-spin"></i></b>
        </div>
        <div class="cdv2-field">
            <span class="cdv2-field__label">Entreprise</span>
            <b class="cdv2-field__value" id="entreprise-cotation"><i class="fa fa-spinner fa-spin"></i></b>
        </div>
        <div class="cdv2-field">
            <span class="cdv2-field__label">Compte contribuable</span>
            <b class="cdv2-field__value" id="contribuable-cotation"><i class="fa fa-spinner fa-spin"></i></b>
        </div>
        <div class="cdv2-field">
            <span class="cdv2-field__label">Contact</span>
            <b class="cdv2-field__value" id="telephone-cotation"><i class="fa fa-spinner fa-spin"></i></b>
        </div>
        <div class="cdv2-field">
            <span class="cdv2-field__label">Voitures à déclarer</span>
            <b class="cdv2-count" id="nbre_vehicule-cotation"><i class="fa fa-spinner fa-spin"></i></b>
            <span class="cdv2-field__unit">véhicules prévus</span>
        </div>
        <div class="cdv2-field">
            <span class="cdv2-field__label">Voitures enregistrées</span>
            <b class="cdv2-count cdv2-count--muted" id="nbre_vehicule_enregistrer-cotation"><i class="fa fa-spinner fa-spin"></i></b>
            <span class="cdv2-field__unit">véhicules saisis</span>
        </div>
    </div>
</section>

<div id="alert-message"></div>

<section class="cdv2-card cdv2-table-card">
    <header class="cdv2-card__head">
        <div>
            <h2 class="cdv2-card__title">Liste des véhicules</h2>
            <p class="cdv2-card__subtitle">Gérez les véhicules rattachés à cette demande</p>
        </div>
        <span id="add-cotation"></span>
    </header>

    <div class="cdv2-table-wrap">
        <table class="cdv2-table" id="datatable-custom">
            <thead>
                <tr>
                    <th>Nom du propriétaire</th>
                    <th>Carte grise</th>
                    <th>Immatriculation</th>
                    <th>Type</th>
                    <th>Montant</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="render-html">
                <tr>
                    <td colspan="6"><i class="fa fa-spinner fa-spin"></i> Chargement en cours ...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="cdv2-empty">
        <div class="cdv2-empty__icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 17H3v-5l2-5h11l3 4h2v6h-2"></path><circle cx="7.5" cy="17" r="2"></circle><circle cx="16.5" cy="17" r="2"></circle><line x1="9.5" y1="17" x2="14.5" y2="17"></line></svg>
        </div>
        <p class="cdv2-empty__title">Aucun véhicule enregistré</p>
        <p class="cdv2-empty__text">Commencez par ajouter les véhicules que vous souhaitez déclarer pour obtenir votre cotation.</p>
    </div>
</section>

<div class="cdv2-actions">
    <span id="submit-cotation"></span>
</div>
