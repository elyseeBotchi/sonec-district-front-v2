<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GlobalSendService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class FacturationsController extends Controller
{

   

  public function store(Request $request){

    $url_path = "/autorisations/entite/rubrique/facturation/store";

    $data = [
        //'admin_uuid' => AuthConnect()['uuid'],
        'entity_uuid' => $request->entity_uuid,
        'rubrique' =>$request->rubrique_uuid,
        'rubrique_options' =>$request->rubrique_option_uuid,
        'target' =>$request->target,
        'billings' =>$request->billings,
        'lieu_rdv' => $request->lieu_rdv ?? ''
         

    ];

      $response = (new GlobalSendService())->CallApi($url_path,$data,'POST');
    //  return dd($response);
      return response()->json($response);

  }
   
  public function storePenalty(Request $request){

    $url_path = "/autorisations/entite/rubrique/facturation/penalty/store";

    $data = [
        //'admin_uuid' => AuthConnect()['uuid'],
        'entity_uuid' => $request->entity_uuid,
        'rubrique' =>$request->rubrique_uuid,
        'rubrique_options' =>$request->rubrique_option_uuid,
        'target' =>$request->target,
        'billings' =>$request->billings,
        'facturation_uuid' => $request->facturation_uuid,
         
    ];
    
    //return dd($data);

      $response = (new GlobalSendService())->CallApi($url_path,$data,'POST');
      //return dd($response);
      return response()->json($response);

  }

public function lock(Request $request){

  $url_path = "/autorisations/entite/rubrique/facturation/delete";

  $data = [
      //'admin_uuid' => AuthConnect()['uuid'],
      'uuid' => $request->id,
      'status' =>$request->param,

  ];


//  return dd($data);
  $response = (new GlobalSendService())->CallApi($url_path,$data,'POST');
 // return dd($response);
  return response()->json($response);

}

 
}
