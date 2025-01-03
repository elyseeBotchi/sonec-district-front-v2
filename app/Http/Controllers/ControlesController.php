<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\GlobalSendService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ControlesController extends Controller
{

    public function index()
    {
       // return dd('*******');
        return view('agents.index');
    }

    
    public function login()
    {
        return view('agents.auth.login');
    }

    
    public function Connexion(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'matricule' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $data['error'] = true;
            $data['message'] = "Connexion échouée, Veuillez vérifier vos paramètres de connexion";

            $dataResponse =[
                 'type'=> 'error',
                 'urlback'=> '',
                 'message'=> $data['message'] ?? '',
                 'code'=> 500,
                 'step'=> 'Param connexion'
                 ];
                 return response()->json($dataResponse);
        }
        else {

            $url_path = "/authentification/controle/login";
            $data = [
                'matricule' => $request->matricule,
                'password' => $request->password,
                'latitude_web' => $request->latitude_web,
                'longitude_web' => $request->longitude_web,
            ];

            $clientLogin = (new GlobalSendService())->CallApi($url_path,$data,'POST');
           
            //  dd($clientLogin);

            if(isset($clientLogin["type"])){
                if ($clientLogin["type"] == "success") {
                    // Stocker les données dans la session 'admin'
                    unset($clientLogin["data"]["user"]["password"]);

                    Session::put('admin', $clientLogin["data"]["user"]);

                    // Récupérer les données de la session 'admin' et les assigner à des clés spécifiques dans un tableau
                    $adminData = Session::get('admin');
                    $adminData['role'] = $clientLogin["data"]["role"] ?? [];
                    $adminData['offices'] = $clientLogin["data"]["offices"] ?? [];
                    $adminData['permissions'] = $clientLogin["data"]["permissions"] ?? [];
                    $adminData['AccessToken'] = $clientLogin["data"]["token"] ?? [];

                    // Remettre le tableau modifié dans la session 'admin'
                    Session::put('admin', $adminData);

                   // return dd($adminData);
                    $dataResponse =[
                     'type'=>'success',
                     'urlback'=> route('controle.home'),
                     'message'=>$clientLogin['message'] ?? '',
                     'code'=>200,
                 ];
                 return response()->json($dataResponse);
                }
                else {
                     $dataResponse = [
                     'type'=> 'error',
                     'urlback'=> '',
                     'message'=> $clientLogin['message'] ?? 'Connexion echouée ',
                     'code'=>500,
                 ];
                 return response()->json($dataResponse);
                }
            }else{
                $dataResponse = [
                    'type'=> 'error',
                    'urlback'=> '',
                    'message'=> $clientLogin['message'] ?? 'Connexion echouée ',
                    'code'=>500,
                ];
                return response()->json($dataResponse);
            }
        }
        //return $data;
    }

    public function verifyV1($decodedText){
        $url_path = "/autorisations/agents/verify";

        /* ####################### */
       // preg_match('/ref\s*:\s*(\d+)/', $decodedText, $matches);
       preg_match('/ref\s*:\s*([A-Za-z0-9\-]+)/', $decodedText, $matches);
       $tabQrtext = explode("|", $decodedText);
       $searchMatricule = $tabQrtext[0];
        // Vérification si la référence a été trouvée
        Log::info($decodedText);
        Log::info($matches[1] ?? 'non retrouvé');
        if (isset($matches[1])) {
            $reference = $matches[1];
        } else {
          //  echo "Référence non trouvée.";
            return response()->json([
                'type' => 'error',
                'message' => "Référence non trouvée.",
                'code' => 500,
            ]);
        }

        $data = [
            'qrCodeData' => $decodedText ?? '',
            'reference' => $reference ?? '',
        ];

        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'POST');
       // return dd($dataResponse);
        return response()->json($dataResponse);
        
        if($dataResponse['type'] == 'error'){
            return response()->json($dataResponse);
        }else{
            return response()->json([
                'type' => 'success',
                'message' => $dataResponse['message'] ?? "Un élément enregistré",
                'code' => 200,
                'urlback'=>'',
                'data' => $dataResponse['data'] ?? ''
            ]);
        }
    }
    
    public function verify($decodedText)
    {
        $url_path = "/autorisations/agents/manual/verify";
        /* ####################### */
       // preg_match('/ref\s*:\s*(\d+)/', $decodedText, $matches);
      // preg_match('/ref\s*:\s*([A-Za-z0-9\-]+)/', $decodedText, $matches);
       $tabQrtext = explode("|", $decodedText);
       $searchMatricule = $tabQrtext[0];
        // Vérification si la référence a été trouvée
        if (isset($searchMatricule)) 
        {
            $searchMatricule = $searchMatricule;
        } 
        else 
        {
          //  echo "Référence non trouvée.";
            return response()->json([
                'type' => 'error',
                'message' => "Référence non trouvée.",
                'code' => 500,
            ]);
        }

        $type = 'immatriculation';
        $data = [
            'qrCodeData' => $decodedText ?? '',
            'reference' => $searchMatricule ?? '',
            'type' => $type ?? ''
        ];
        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'POST');
       // return dd($dataResponse);
        return response()->json($dataResponse);
        
        if($dataResponse['type'] == 'error'){
            return response()->json($dataResponse);
        }else{
            return response()->json([
                'type' => 'success',
                'message' => $dataResponse['message'] ?? "Un élément enregistré",
                'code' => 200,
                'urlback'=>'',
                'data' => $dataResponse['data'] ?? ''
            ]);
        }
    }
    
    public function verify_manual($decodedText){
        $url_path = "/autorisations/agents/manual/verify";
            /* ####################### */

            // Regex pour numéro d'immatriculation
            $regexImmatriculation = '/^([0-9]{1,4}[A-Z]{2}[0-9]{2})|([A-Z]{2}[0-9]{1,4}[A-Z]{2})$/';
            
            // Regex pour numéro de carte grise
            $regexCarteGrise = '/^[A-Z]{2}[0-9]{6}$|^[0-9]{6}[A-Z]{2}$|^[A-Z]{2}-[0-9]{4}-[A-Z]{2}$/';

            // Vérification si c'est un numéro d'immatriculation
            if (preg_match($regexImmatriculation, $decodedText)) {
                //return "Il s'agit d'un numéro d'immatriculation.";
                $reference = $decodedText;
                $type = 'immatriculation';
            }
            // Vérification si c'est un numéro de carte grise
            elseif (preg_match($regexCarteGrise, $decodedText)) {
                //return "Il s'agit d'un numéro de carte grise.";
                $reference = $decodedText;
                $type = 'carte_grise';
            }
            else {
                return response()->json([
                    'type' => 'error',
                    'message' => "Format incorrect",
                    'code' => 500,
                ]);
            }

        $data = [
            'qrCodeData' => $decodedText ?? '',
            'reference' => $reference ?? '',
            'type' => $type ?? ''
        ];

        Log::info(json_encode($data));

        $dataResponse = (new GlobalSendService())->CallApi($url_path,$data,'POST');
        //return dd($data);
        Log::info(json_encode($dataResponse));
        return response()->json($dataResponse);

        /*         
        if($dataResponse['type'] == 'error'){
            return response()->json($dataResponse);
        }else{
            return response()->json([
                'type' => 'success',
                'message' => $dataResponse['message'] ?? "Un élément enregistré",
                'code' => 200,
                'urlback'=>'',
                'data' => $dataResponse['data'] ?? ''
            ]);
        } */
    }
}
