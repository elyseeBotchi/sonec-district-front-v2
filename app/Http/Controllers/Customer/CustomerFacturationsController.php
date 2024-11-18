<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\GlobalSendService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CustomerFacturationsController extends Controller
{

    public function index($slug,$target)
    {
        $url_path = "/autorisations/services/taxe/operateurs";

        $data = [
            'uuid' => $target
        ];

        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');

      // dd($responses);
        if(isset($responses['type'])){
            if($responses['type'] =='success'){
                return view('customers.services.index', [
                    'operateurs'=>$responses['data'] ?? '',
                    'entity_uuid' =>$target ?? '',

                ]);
            }
            else{
                return view('customers.services.index', [
                    'operateurs'=>$responses['data'] ?? '',
                    'entity_uuid' =>$target ?? ''

                ]);
            }
        }
        else{
            return view('customers.services.index', [
                'operateurs'=>$responses['data'] ?? '',
                'entity_uuid' =>$target ?? ''
            ]);
        }
    }

    public function Paystore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'entity_uuid' => 'required',
            'pay_uuid' => 'required',
            'paymode' => 'required',
            'numero_paiement' => 'required',
            'rubrique_facturation_uuid' => 'required',
        ]);

        if($validator->fails()){
            $dataResponse =[
                'type'=>'error',
                'urlback'=>'',
                'message'=>"Veuillez renseigner tous les champs",
                'code'=>400,
                'errors' => $validator->errors()
            ];
            return response()->json($dataResponse);
        }
        
        $url_path = "/autorisations/services/taxe/pay";

        $data = [
            'entity_uuid'=> $request->entity_uuid ?? '',
            'pay_uuid'=> $request->pay_uuid ?? '',
            'paymode'=> $request->paymode ?? '',
            'numero_paiement' => $request->numero_paiement ?? '',
            'rubrique_facturation_uuid' => $request->rubrique_facturation_uuid ?? ''
        ];

       // return response()->json($data);

        $response = (new GlobalSendService())->CallApi($url_path,$data,'POST');
       // return response()->json($response);

        if(isset($response['type'])){
            if($response['type'] =='success'){
                $dataResponse =[
                    'type'=>'success',
                    'urlback'=> route('customer.entities.taxe.info_paiement',['uuid' => $response['data']]),
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


    
    public function info_paiement($uuid)
    {
        $url_path = "/autorisations/services/taxe/info_paiement";

        $data = [
            'uuid' => $uuid
        ];

      //  $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');

      // dd($responses);
        if(isset($responses['type'])){
            if($responses['type'] =='success'){
                return view('customers.services.info_paiement', [
                    'paiement_uuid' => $uuid,
                ]);
            }
            else{
                return view('customers.services.info_paiement', [
                    'paiement_uuid' => $uuid,
                ]);
            }
        }
        else{
            return view('customers.services.info_paiement', [
                'paiement_uuid' => $uuid,
            ]);
        }
    }


    
    public function paiement_data($uuid)
    {
        $url_path = "/autorisations/services/taxe/info_paiement";

        $data = [
            'uuid' => $uuid
        ];

        $responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');

        return response()->json($responses);
 
    }



}
