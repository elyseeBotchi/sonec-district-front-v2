$(document).ready(function() {

    // activer le tooltip
    $('[data-toggle="tooltip"]').tooltip()

    function loading() {
        Swal.fire({
            // title: "En cours de traitement...",
            text: "Traitement en cours",
            imageUrl: "/assets/images/loader.gif",
            imageWidth: 50,
            imageHeight: 50,
            showConfirmButton: false,
            allowOutsideClick: true
        });
    }

    function unloading() {
        Swal.close();
    }
    function SendError(title, messageError) { //fonction pour envoi de formulaire chargement loading
        Swal.fire({
            icon: 'error',
            //title: title,
            text: messageError,
            confirmButtonText: 'FERMER'
        })
    } //fin de la focntion SendError

    //debut fonction sendSuccess
    function sendSuccess(title, message, urlback = '') { // retour en cas de success d'envoi de formulaire
        if (urlback != '') {
            if (urlback == 'back') {
//Si url de retour exist
                Swal.fire({
                    icon: 'success',
                    //title: title,
                    text: message,
                    showConfirmButton: false,
                    timer: 3000
                });
                setTimeout(() => {
                    location.reload();
                }, 2000);
            } else {
//Si url de retour exist
                Swal.fire({
                    icon: 'success',
                    //title: title,
                    text: message,
                    showConfirmButton: false,
                    timer: 2000
                });
                setTimeout(() => {
                    window.location.href = urlback;
                }, 2000);
            }
        } else {
//Si url de retour exist pas dans le retour du formulaire

            Swal.fire({
                icon: 'success',
                //title: title,
                text: message,
                showConfirmButton: true,
                // timer: 2000
            })
            $('.sendForm')[0].reset();
        }

    }

    function SendError2(messageError){ //fonction pour envoi de formulaire chargement loading
        Swal.fire({
            icon: 'error',
            title: messageError,
            text: '',
        })
    } //fin de la focntion SendError

//debut fonction sendSuccess
    function sendSuccess2(message, urlback=''){ // retour en cas de success d'envoi de formulaire
        if (urlback != '') {
            if(urlback == 'back'){
                //Si url de retour exist
                Swal.fire({
                    icon: 'success',
                    title: message,
                    showConfirmButton: true,
                    timer: 2000
                });
                setTimeout(() => {
                    location.reload();
                }, 2000);
            }else {
                //Si url de retour exist
                Swal.fire({
                    icon: 'success',
                    title: message,
                    showConfirmButton: true,
                    timer: 2000
                });
                setTimeout(() => {
                    window.location.href = urlback;
                }, 2000);
            }
        }
        else {
            //Si url de retour exist pas dans le retour du formulaire

            Swal.fire({
                icon: 'success',
                title: message,
                showConfirmButton: false,
                timer: 2000
            })
        }

    }

    // bonne fonction d'envoie
    $('.sendForm2').submit(function (e) {
        document.querySelector('button[type="submit"]').innerHTML = '<i class="fa fa-spinner fa-spin"><i/>';
        e.preventDefault();
        var action = $(this).attr('action');
        var formData = new FormData(this);
       // document.getElementById('divLoading').style.display ='block';

       // $(":submit").attr('disabled', 'disabled');
       // $(":submit").css("disabled", "true");
       // $(":submit").addClass("fa fa-spinner");
        //alert('***')
        $.ajax({

            url: action,
            type: 'POST',
            data: formData,
            async: false,
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function (){
                Swal.fire({
                    // title: "En cours de traitement...",
                    text: "Traitement en cours",
                    imageUrl: "/assets/images/loader.gif",
                    imageWidth: 50,
                    imageHeight: 50,
                    showConfirmButton: false,
                    allowOutsideClick: true
                });

                document.querySelector('button[type="submit"]').innerHTML = '<i class="fa fa-spinner fa-spin"><i/>';
            },
            success: function (data) {
                document.querySelector('button[type="submit"]').innerHTML = 'Valider';
                if (data.type === "success") {
                   // console.log(data.dataReturn)
                    if( data.urldownload !== undefined && data.urldownload !==""){
                        var link = document.createElement("a");
                        link.download = data.filename;
                        link.href = data.urldownload;
                        link.click();

                        //var page = data.urldownload;
                        //var myWindow = window.open(page, "_blank", "scrollbars=yes,width=400,height=500,top=300");
                        // focus on the popup //
                        //myWindow.focus();
                    }
                   // alert(data.dataTarget);
                    if(data.dataReturn !==undefined && data.dataReturn !==""){
                        $('#'+data.dataTarget).html(data.dataReturn)
                        $('#'+data.modalid).modal('hide');
                    }

                    if(data.remove_tr !==undefined && data.remove_tr !==""){
                        $('#'+data.remove_tr).remove();
                       // remove_fields()
                    }

                    if(data.load_invite !==undefined && data.load_invite !==""){
                        load_invite()
                        remove_fields()
                    }

                    if( data.urlreload !== undefined && data.urlreload !==""){
                        var page=data.urlreload;
                        var myWindow = window.open(page, "_blank", "scrollbars=yes,width=500,height=600,top=200");
                        //focus on the popup //
                        myWindow.focus();
                    }
                    sendSuccess2(data.message, data.urlback);
                }
                else if(data.type ==="error_captcha") {
                    sendError_rec(data.message);
                }
                else {
                    SendError2(data.message);
                }
            },
            error: function (data) {
                document.querySelector('button[type="submit"]').innerHTML = 'Valider';
                SendError2("messageError");
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });

    // bonne fonction d'envoie
    $('.sendForm').submit(function (e) {
        e.preventDefault();
        var action = $(this).attr('action');
        var formData = new FormData(this);
        $.ajax({

            url: action,
            type: 'POST',
            data: formData,
            async: false,
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () { // if form submit
                loading();
            },
            success: function (data) {
                if (data.type === "success") {//if formData forme is very good

                    if( data.urldownload !== undefined && data.urldownload !==""){
                        var link = document.createElement("a");
                        link.download = data.filename;
                        link.href = data.urldownload;
                        link.click();

                        //var page = data.urldownload;
                        //var myWindow = window.open(page, "_blank", "scrollbars=yes,width=400,height=500,top=300");
                        // focus on the popup //
                        //myWindow.focus();
                    }

                    if( data.urlreload !== undefined && data.urlreload !==""){
                        var page=data.urlreload;
                        var myWindow = window.open(page, "_blank", "scrollbars=yes,width=500,height=600,top=200");
                        //focus on the popup //
                        myWindow.focus();
                    }
                    sendSuccess2(data.message, data.urlback);

                }
                else if(data.type =="error_captcha") {
                    sendError_rec(data.message);
                }
                else {
                    SendError2(data.message);
                }
            },
            error: function (data) {
                //alert('****')
                    SendError2("messageError");
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });

    $("#container").on('submit', '.showLoader', function (e) {
        loading();
    });

    $("#container").on('click', '.sendLink', function (e) {

        e.preventDefault();
        var action = $(this).attr('href');
        var caption = $(this).attr('caption');

        Swal.fire({
            icon : 'warning',
            title: 'Attention !',
            text: caption ? caption : 'Vous êtes sur le point d\'effectuer un changement',
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: `OUI, CONTINUER`,
            denyButtonText: `NON, FERMER`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {

                $.ajax({

                    url: action,
                    type: 'GET',
                    // data: formData,
                    dataType: 'json',
                    // headers: {
                    //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    // },
                    beforeSend: function () { // if form submit
                        loading();
                    },
                    success: function (data) {
                        if (data.type == "success") {//if formData forme is very good

                            sendSuccess(data.title, data.message, data.urlback);
                        } else {
                            SendError(data.title, data.message);
                        }
                    },
                    error: function (data) {
                        if (data.type == "error") {// if error occured
                            SendError("messageError");
                        }
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });

            } else if (result.isDenied) {
                Swal.close()
            }
        })

    });

    $("#container").on('click', '.recapCart', function (e) {

        e.preventDefault();
        var action = $(this).attr('href');
        var title = $(this).attr('title');

        Swal.fire({
            icon : 'warning',
            title: 'Attention !',
            text: title ? title : 'Vous êtes sur le point d\'effectuer un changement',
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: `OUI, CONTINUER`,
            denyButtonText: `NON, FERMER`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {

                $.ajax({

                    url: action,
                    type: 'GET',
                    // data: formData,
                    dataType: 'json',
                    // headers: {
                    //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    // },
                    beforeSend: function () { // if form submit
                        loading();
                    },
                    success: function (data) {
                        if (data.type == "success") {//if formData forme is very good

                            sendSuccess(data.title, data.message, data.urlback);
                        } else {
                            SendError(data.title, data.message);
                        }
                    },
                    error: function (data) {
                        if (data.type == "error") {// if error occured
                            SendError("messageError");
                        }
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });

            } else if (result.isDenied) {
                Swal.close()
            }
        })

    });

    $("#container").on('submit', '.sendConfirmForm', function (e) {

        e.preventDefault();
        var action = $(this).attr('action');
        var formData = new FormData(this);

        Swal.fire({
            icon: 'warning',
            title: 'Attention !',
            text: 'Vous êtes sur le point d\'effectuer un changement',
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: `OUI, CONTINUER`,
            denyButtonText: `NON, FERMER`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {

                $.ajax({

                    url: action,
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function () { // if form submit
                        loading();
                    },
                    success: function (data) {
                        if (data.type == "success") {//if formData forme is very good

                            sendSuccess(data.title, data.message, data.urlback);
                        } else {

                            SendError(data.title, data.message);
                        }
                    },
                    error: function (data) {
                        if (data.type == "error") {// if error occured
                            SendError("messageError");
                        }
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });

            } else if (result.isDenied) {
                Swal.close()
            }
        })

    });

    /*############################# MODAL ALERT############################*/
    function showConfirm_submit(id,uuid,token,url,title,message,param,param2,param3,urlback) {
        Swal.fire({
            title: title,
            text: message,
            icon: "warning",
            buttons: true,
            dangerMode: true,

            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, continuer !',
            cancelButtonText: 'Annuler',
            confirmButtonClass: 'btn btn-warning',
            cancelButtonClass: 'btn btn-danger ml-1',
        })
            .then((result) => {
                if (result.value) {
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: { id : id, uuid : uuid,_token :token ,param:param,param2:param2,urlback:urlback},
                        dataType: "json",

                        beforeSend: function() { // if form submit
                            Swal.fire({
                                title: "En cours de traitement...",
                                text: "Patientez un instant",
                                imageUrl: "/images/loading.giff",
                                showConfirmButton: false,
                                allowOutsideClick: false
                            });
                        },
                        success: function(data) {
                            if(data.type ==="success"){//if formData forme is very good
                                Swal.fire(data.message, {
                                    icon: "success"
                                });
                                window.location.href =data.urlback;
                            }
                            else{
                                Swal.fire(data.message, {
                                });
                            }
                        },
                        error: function(data) {
                            if(data.type ==="error"){// if error occured
                                Swal.fire(data.message, {
                                });
                            }
                        }
                    });
                }
                else if (result.dismiss === Swal.DismissReason.cancel) {
                    /*Swal.fire({
                        title: 'Cancelled',
                        text: 'Your imaginary file is safe :)',
                        type: 'error',
                        confirmButtonClass: 'btn btn-success',
                    })*/
                }

            });
    }

    /*############################# MODAL ALERT############################*/

    $("#container").on('click', '.deleteConfirmation', function() {
        var type = $(this).data('type');
        var title = $(this).data('title');
        var message = $(this).data('message');
        var id = $(this).data('id');
        var uuid = $(this).data('uuid');
        var token = $(this).data('token');
        var url = $(this).data('url');
        var urlback = $(this).data('urlback');
        var param = $(this).data('param');
        var param2 = $(this).data('param2');
        var param3 = $(this).data('param3');


        showConfirm_submit(id,uuid,token,url,title,message,param,param2,param3,urlback);
    });

    $("#container").on('click', '.set_status', function() {
        var message = $(this).data('message');
        var url = $(this).data('url');
        var uuid = $(this).data('uuid');
        var token = $(this).data('token');
        var status = $(this).data('status');

       // alert(uuid)
        set_status(uuid,token,url,status,message)
    });

    i=0;
    $(".add_new_box").click(function(){
        var $clone = $('.add_new:last').clone();

        $clone.insertAfter('.add_new:last');
        var i=$('.add_new:last').attr('id');

        i= Number(i)+1;
        $('.add_new:last').attr('id',i);
        $('.sup_new_box:last').attr('id',i);
        $('.niv_new_box:last').attr('id',i);

        $('#'+i).innerHTML('00000');
    });

    $(".add_new_content").on('click', '.sup_new_box', function() {
        var i= $(this,'.sup_new_box').attr('id');
        $('#'+i).remove();
    });
    /*End script*/
    $("#container").on('click', '.decision', function () {
        var id= $(this).val();
        $('.decision_input').val(id);

    });


});


function load_data(select_val,hidden_div){
    var checkdata =  document.getElementById(select_val).value;
    //alert(checkdata)
    if(checkdata =="autre" || checkdata =="oui" ){
        document.getElementById(hidden_div).style.display="";
    }
    else {
        document.getElementById(hidden_div).style.display="none";
    }
}



function load_search(remove_loading,loader){
    document.getElementById(remove_loading).style.display="none";
    document.getElementById(loader).style.display="";
}


function remove_fields(){
    document.getElementById('nom').value="";
    document.getElementById('prenom').value="";
    document.getElementById('telephone').value="";
    document.getElementById('email').value="";
}


function load_invite(){
    // alert('****')
    var url = document.getElementById('url').value;
    var token = document.getElementById('token').value;
    //alert(url);
    $.ajax({
        url: url,
        type: "POST",
        data: {
            _token: token,
            //date_deb: date_deb,
            // date_fin: date_fin,
        },
        dataType: "json",

        beforeSend: function () { // if form submit

        },
        success: function (data) {
            // closeLoader
            if (data.type === "success") {//if formData forme is very good
                $('#invite_list').html(data.get_invites);
                // $('.tableData').append(data.tableReturn);
                // $('#invite_list').append(data.get_invites);
                // $('.tableData').append('<tr><td>' + item.created_at + '</td><td>' + item.request + '</td><td>' + motif + '</td></tr>');

                //alert('***** ok')
            } else {

            }
        },
        error: function (data) {

        }
    });
}

function set_status(uuid,token,url,status,message){
    //alert(url)

    Swal.fire({
        title: "Confirmation de présence",
        text: message,
        icon: "warning",
        buttons: true,
        dangerMode: true,

        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oui, continuer !',
        cancelButtonText: 'Annuler',
        confirmButtonClass: 'btn btn-warning',
        cancelButtonClass: 'btn btn-danger ml-1',
    })
        .then((result) => {
            if (result.value) {

                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _token: token,
                        uuid: uuid,
                        status: status,
                    },
                    dataType: "json",

                    beforeSend: function () { // if form submit
                        Swal.fire({
                            title: "En cours de traitement...",
                            text: "Patientez un instant",
                            imageUrl: "/images/loading.giff",
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });
                    },
                    success: function (data) {
                        // closeLoader
                        if (data.type === "success") {//if formData forme is very good
                            if(data.remove_tr !==""){
                                $('#'+uuid).remove();
                                //$('#'+uuid).style.display="none";
                            }
                            if(data.urlback !==undefined && data.urlback !==''){
                                window.location.href =data.urlback;
                            }
                            Swal.fire(data.message, {
                                icon: "success"
                            });

                        } else {

                        }
                    },
                    error: function (data) {
                        alert(data)
                    }
                });

            }
            else if (result.dismiss === Swal.DismissReason.cancel) {
                /*Swal.fire({
                    title: 'Cancelled',
                    text: 'Your imaginary file is safe :)',
                    type: 'error',
                    confirmButtonClass: 'btn btn-success',
                })*/
            }

        });
}
