@extends('layout.customerApp')

@section('content')
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0">DETAIL DU PAIEMENT</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">Services</li>
                    <li class="breadcrumb-item active services"><i class="fa fa-spinner fa-spin"></i></li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <span class="alert alert-danger">
            Échec du paiement ! Merci de bien vouloir réessayer ultérieurement ou de contacter l'assistance.
        </span>
        
    </div>


    
@endsection

