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

    let html_render = "";

    
    html_render += `
    <tr> 
        <td><h3>Taxe payé </h3></td> 
        <td><h3> ${pay_element['rubrique_name'] || ''} ${pay_element['rubrique_option_name'] || ''} </h3></td> 
    </tr>`;

    html_render += `
    <tr> 
        <td> <h3> Montant payé </h3></td> 
        <td><h3> ${pay_element['amount'] || ''} Francs CFA </h3></td> 
    </tr>`;

    if (Array.isArray(entete) && entete.length > 0) {
        entete.forEach(element => {
            const slugifiedName = slugify(element.name);
            const payElementValue = pay_element[slugifiedName] || ''; // Récupère la valeur correspondante dans pay_element
            if(slugifiedName !="email" && slugifiedName !="telephone"){
                html_render += `
                <tr> 
                    <td> <h3> ${element.name} </h3></td> 
                    <td><h3> ${payElementValue} </h3></td> 
                </tr>`;  
            }
        });


        html_render += `
        <tr> 
            <td> <h3> Référence paiement </h3> </td> 
            <td> <h3> ${pay_element['reference'] || ''} </h3> </td> 
        </tr>`;

        function formatDate(dateString) {
            const date = new Date(dateString);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0'); // Les mois commencent à 0
            const year = date.getFullYear();
            return `${day} - ${month} - ${year}`;
        }

        var date_actuelle = new Date().toISOString().split('T')[0]; // Date actuelle au format YYYY-MM-DD

        const dateDebutFormatted = formatDate(pay_element['date_debut']);
        const dateFinFormatted = formatDate(pay_element['date_fin']);

        if (pay_element['date_fin'] > date_actuelle) {
            html_render += `
            <tr> 
                <td> <h3> Période </h3> </td> 
                <td> 
                   <h3>  <span class="badge badge-pill badge-success">${dateDebutFormatted}</span> au <span class="badge badge-pill badge-success">${dateFinFormatted}</span> </h3> 
                </td> 
            </tr>`;
        } else if (pay_element['date_fin'] < date_actuelle) {
            html_render += `
            <tr> 
                <td><h3> Période </h3> </td> 
                <td> 
                  <h3>   <span class="badge badge-pill badge-danger">${dateDebutFormatted}</span> au <span class="badge badge-pill badge-danger">${dateFinFormatted}</span>    </h3>                        
                </td> 
            </tr>`;
        }
        else {
            html_render += `
            <tr> 
                <td><h3> Période </h3> </td> 
                <td> 
                   <h3>  <span class="badge badge-pill badge-danger">Aucun paiement valide</span></h3> 
                </td> 
            </tr>`;
        }

        if(pay_element['state'] ==="enable"){
            html_render += `
            <tr> 
                <td> <h3> Statut </h3> </td> 
                <td>
                  <h3>  <span class="adge bg-warning font-12 text-white font-weight-medium badge-pill "> En attente </span> </h3> 
                </td> 
            </tr>`;

            

        } else if(pay_element['state'] ==="validate"){
            html_render += `
            <tr> 
                <td><h3> Statut </h3> </td> 
                <td> <h3> <span class="adge bg-success font-12 text-white font-weight-medium badge-pill"> Validé </span> </h3> </td> 
            </tr>`;

            html_render += `
            <tr> 
                <td> <h3> Validé par </h3> </td> 
                <td> <h3>  ${pay_element['validate_firstname']  || ''} ${pay_element['validate_lastname']  || ''} le ${new Date(pay_element['validate_at']).toLocaleString()} </h3> </td> 
            </tr>`;
 
           
        }else{
            html_render += `
            <tr> 
                <td><h3> Statut </h3> </td> 
                <td>
                  <h3> <span class="adge bg-danger font-12 text-white font-weight-medium badge-pill "> Rejeté </span> </h3> 
                </td> 
            </tr>`;

            html_render += `
            <tr> 
                <td> <h3> Validé par </h3> </td> 
                <td> <h3> ${pay_element['validate_firstname']  || ''} ${pay_element['validate_lastname']  || ''} le ${new Date(pay_element['validate_at']).toLocaleString()} </h3> </td> 
            </tr>`;

          //  document.getElementById('validation-info').style.display = "none";  
        }
        
        if(pay_element['penalty_pound_amount_total']){
                html_render += `
            <tr> 
                <td> <h3> Montant de la pénalité </h3> </td> 
                <td> <h3> ${pay_element['penalty_pound_amount_total']  || ''} Francs CFA </h3> </td> 
            </tr>`;


            html_render += `
            <tr> 
                <td> <h3> Pénalité appliqué par </h3> </td> 
                <td> <h3>  [${penalty['partner_name'] || ''}] ${penalty['admin_firstname']  || ''} ${penalty['admin_lastname']  || ''} le ${new Date(penalty['created_at']).toLocaleString()} </h3> </td> 
            </tr>`;
        }

    } else {
        html_render = "<tr><td colspan='2'>Aucune donnée disponible pour l'entête</td></tr>";
    }

    document.getElementById('html_render').innerHTML = html_render;

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