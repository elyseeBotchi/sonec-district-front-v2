<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GlobalSendService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class RubriquesController extends Controller
{



    public function lock(Request $request)
    {
        $url_path = "/autorisations/entite/rubrique/delete";

        $data = [
           // 'admin_uuid' => AuthConnect()['uuid'],
            'uuid'=>$request->id ?? '',
            'status'=>$request->param ?? ''
        ];

        $response = (new GlobalSendService())->CallApi($url_path,$data,'POST');

       // return dd($response);

        if(isset($response['type'])){
            if($response['type'] =='success'){
                $dataResponse =[
                    'type'=>'success',
                    'urlback'=>'',
                    'message'=>$response['message'] ?? '',
                    'code'=>200,
                ];
            return response()->json($dataResponse);
            }
            else{
                $dataResponse =[
                    'type'=>'error',
                    'urlback'=>'',
                    'message'=>$response['message'] ?? '',
                    'code'=>500,
                ];
                return response()->json($dataResponse);
            }
        }else{
            $dataResponse =[
                'type'=>'error',
                'urlback'=>'',
                'message'=>$response['message'] ?? '',
                'code'=>500,
            ];
            return response()->json($dataResponse);
        }

    }


    public function store(Request $request){

        $url_path = "/autorisations/entite/rubrique/store";

        $data = [
            //'admin_uuid' => AuthConnect()['uuid'],
            'entity_uuid' => $request->entity_uuid,
            'rubrique' =>$request->rubrique,
            'rubrique_options' =>$request->rubrique_options,

        ];

        $response = (new GlobalSendService())->CallApi($url_path,$data,'POST');
      //  return dd($response);
        return response()->json($response);

    }

    public function update(Request $request){

        $url_path = "/autorisations/entite/rubrique/update";

        if($request->target_rule =="rubriques"){
            $uuid = $request->rubrique_uuid;
        }
        else{
            $uuid = $request->rubrique_option_uuid;
        }
        
        $data = [
            //'admin_uuid' => AuthConnect()['uuid'],
            'entity_uuid' => $request->entity_uuid,
            'rubrique_uuid' =>$request->rubrique_uuid,
            'libelle' =>$request->rubrique,
            'rubrique_options' =>$request->rubrique_option_uuid,
            'uuid' => $uuid,
            'target_rule' => $request->target_rule
        ];

        $response = (new GlobalSendService())->CallApi($url_path,$data,'POST');
      //  return dd($response);
        return response()->json($response);

    }

    public function findOneConfig($uuid){

        $url_path = "/autorisations/entite/rubrique/findOneConfig";

        $data = [
            //'admin_uuid' => AuthConnect()['uuid'],
            'entity_uuid' => $uuid,
        ];

        $response = (new GlobalSendService())->CallApi($url_path,$data,'POST');
      // return dd($response);
        return response()->json($response);
    }

}
