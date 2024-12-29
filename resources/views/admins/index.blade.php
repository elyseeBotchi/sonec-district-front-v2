@extends('layout.adminApp')

@section('content')
<div class="card-group">
    @isset($lock)
    <div class="card border-right">
        <div class="card-body">
            <div class="d-flex d-lg-flex d-md-block align-items-center">
                <div>
                    <div class="d-inline-flex align-items-center">
                        <h2 class="text-dark mb-1 font-weight-medium">
                            <span id="paiement_journalier">
                               8
                            </span>
                        </h2>
                        {{-- <span class="badge bg-primary font-12 text-white font-weight-medium badge-pill ml-2 d-lg-block d-md-none">+18.33%</span> --}}
                    </div>
                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Pénalités journalier</h6>
                </div>
                <div class="ml-auto mt-md-3 mt-lg-0">
                    <span class="opacity-7 text-muted"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg></span>
                </div>
            </div>
        </div>
    </div>
    @endisset
    @isset($lock)
    <div class="card border-right">
        <div class="card-body">
            <div class="d-flex d-lg-flex d-md-block align-items-center">
                <div>
                    <div class="d-inline-flex align-items-center">
                        <h2 class="text-dark mb-1 font-weight-medium">153</h2>
                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Nombre de scanne journalier</h6>
                </div>
                <div class="ml-auto mt-md-3 mt-lg-0">
                    <span class="opacity-7 text-muted"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-plus"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg></span>
                </div>
            </div>
        </div>
    </div>
    @endisset
    

    <div class="card">
        <div class="card-body">
            <div class="d-flex d-lg-flex d-md-block align-items-center">
                <div>
                    <div class="d-inline-flex align-items-center">
                        <h2 class="text-dark mb-1 font-weight-medium" id="validation_pending">
                            <span class="fa fa-spinner fa-spin"></span>
                        </h2>
                    </div>

                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">
                       Validation en attente
                    </h6>
                </div>
                <div class="ml-auto mt-md-3 mt-lg-0">
                    <span class="opacity-7 text-muted"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-plus"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg></span>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-right">
        <div class="card-body">
            <div class="d-flex d-lg-flex d-md-block align-items-center">
                <div>
                    <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium" id="nb_total_jour">
                        <span class="fa fa-spinner fa-spin"></span>
                    </h2>
                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Paiement du jour
                    </h6>
                </div>
              
            </div>
        </div>
    </div>

   
</div>

<div class="row col-md-12">
    {{-- <div class="col-lg-4 col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">Evolution des paiements </h4>
                <div class="net-income mt-4 position-relative" style="height:294px;"></div>
                <ul class="list-inline text-center mt-5 mb-2">
                    <li class="list-inline-item text-muted font-italic">
                    </li>
                </ul>
            </div>
        </div>
    </div> --}}

    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <h4 class="card-title mb-0"></h4>
                    <div class="ml-auto"></div>
                </div>
                <div class="pl-4 mb-5">
                    <canvas id="myChart" style="width:100%;height: 315px;"></canvas>
                </div>
                <ul class="list-inline text-center mt-4 mb-0">
                    <li class="list-inline-item text-muted font-italic"></li>
                </ul>
            </div>
        </div>
    </div>
</div>


@push('footer-script')
    <script>
        var Entity_uuid = Entities()[0]['uuid'];
        alert(Entity_uuid)
    </script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="{{ asset('/backoffice/js/dashboard-admin.js') }}"></script> {{-- --}}
@isset($lock)
    <script>
        const xValues = ['janv','Fev','Mars','Avril','Mai','Juin','Jui','Aout','Sept','Oct','Nov','Dec'];
        const yValues = [7,8,8,9,9,9,10,11,14,14,15,50];
        
        new Chart("myChart", {
        type: "line",
        data: {
            labels: xValues,
            datasets: [{
            fill: false,
            lineTension: 0,
            backgroundColor: "rgba(0,0,255,1.0)",
            borderColor: "rgba(0,0,255,0.1)",
            data: yValues
            }]
        },
        options: {
            legend: {display: false},
            scales: {
            yAxes: [{ticks: {min: 6, max:16}}],
            }
        }
        });
    </script>
    @endisset

@endpush

@endsection
