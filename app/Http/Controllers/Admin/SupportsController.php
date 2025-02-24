<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GlobalSendService;
use Illuminate\Http\Request;

class SupportsController extends Controller
{

    public function index($entity_uuid)
    {
       // return dd($entity_uuid);
        return view('admins.support.index', [
                'Entity_uuid'=>$entity_uuid ?? '',
        ]);
      
    }    
    
    public function search(Request $request)
    {
        $request->validate([
            'status' => 'required|string',
           // 'target' => 'required|string',
            'entity_uuid' => 'required|uuid'
        ]);

        if($request->target ==""){
            return response()->json([
                'type' => 'error',
                'message' => "Un élément retrouvé",
                'code' => 200,
                'urlback'=> '',
                'data' => ''
            ]);
        }


        $url_path = "/autorisations/admin/supports/search";

        $data = [
            'status' => $request->status,
            'target' => $request->target,
            'entity_uuid' => $request->entity_uuid
        ];

        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');
      // return dd($responses);

        return response()->json($responses);
      
    }


    public function annulerPaiement($uuid){
        $url_path = "/autorisations/admin/supports/annuler/paiement";

        $data = [
            'uuid' => $uuid,
        ];

        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');
      // return dd($responses);

        return response()->json($responses);

    }

}
