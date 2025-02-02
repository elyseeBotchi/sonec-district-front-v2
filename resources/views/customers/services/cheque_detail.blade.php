@extends('layout.customerApp')

@section('content')
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0">DETAIL DU CHEQUE <span id="libelle-cheque"></span> </h4>    
        </div>
    </div>

    <div class="row"> 
        <div class="pt-5">
            <table class="table table-striped table-bordered" id="datatable-custom">
                <thead>
                <tr></tr>
                </thead>
                <tbody class="render-html">
                    <tr>
                        <td> <i class="fa fa-spinner fa-spin"></i> Chargement en cours ... </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


    
@endsection

@push('footer-script')
<script>
    var cheque_uuid = @Json($cheque_uuid ?? '');
    var Entity_uuid = @Json($entty_uuid ?? '');
</script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
<script src="{{ asset('/backoffice/js/front/services_cheque.js') }}"></script> 
@endpush
