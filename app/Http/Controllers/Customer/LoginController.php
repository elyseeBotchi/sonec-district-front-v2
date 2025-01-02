<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\GlobalSendService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

     public function index()
     {
        $entiteNav = Entity_Customer();
       // dd($entiteNav);
        return redirect()->route('customer.entities.taxe', ['slug' => $entiteNav['slug'], 'target' => $entiteNav['uuid']]);
        // return view('customers.index');
     }

    public function login()
    {
        //return dd("*****");
        return view('customers.auth.login');
    }
    
    public function register($service)
    {
        return view('customers.auth.register',['entity_uuid' => $service]);
    }

    

    public function Connexion(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required',
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

            $url_path = "/customer/authentification/login";
            $data = [
                'email' => $request->email,
                'password' => $request->password,
                'latitude_web' => $request->latitude_web,
                'longitude_web' => $request->longitude_web,
            ];

            $clientLogin = (new GlobalSendService())->CallApi($url_path,$data,'POST');
           
           // dd($clientLogin);

            if(isset($clientLogin["type"])){
                if ($clientLogin["type"] == "success") {
                    // Stocker les données dans la session 'admin'
                    unset($clientLogin["data"]["user"]["password"]);

                    Session::put('user', $clientLogin["data"]["user"]);

                    // Récupérer les données de la session 'admin' et les assigner à des clés spécifiques dans un tableau
                    $adminData = Session::get('user');
                    $adminData['AccessToken'] = $clientLogin["data"]["token"] ?? [];

                    // Remettre le tableau modifié dans la session 'admin'
                    Session::put('user', $adminData);

                    $dataResponse =[
                     'type'=>'success',
                     'urlback'=> route('customer.home'),
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

    public function otp()
    {
        if(UserConnect() != null){
            if(UserConnect()['otp_actif'] !== true){
               return redirect()->route('customer.login');
            }
            elseif(UserConnect()['otp_actif'] == false)
            {
                return redirect()->route('customer.home');
            }
            else{
                return view('customers/auth/otp');
            }
        }
        else{
            return redirect()->route('login');
        }
    }

    public function Otp_Connexion(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'otp' => 'required',
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

            $url_path = "/customer/authentification/otp/login";
            $data = [
                'code' => $request->otp,
            ];

            $clientLogin = (new GlobalSendService())->CallApi($url_path,$data,'POST');
            // return dd($clientLogin);

            if(isset($clientLogin["type"])){
                if ($clientLogin["type"] == "success") {
                    $user =UserConnect();
                    $user['otp_actif'] = false;
                   Session::put('user',  $user);

                    $dataResponse =[
                        'type'=>'success',
                        'urlback'=> route('customer.home'),
                        'message'=>$clientLogin['message'] ?? '',
                        'code'=>200,
                    ];
                    return response()->json($dataResponse);
                }
                else {
                    $dataResponse = [
                        'type'=> 'error',
                        'urlback'=> '',
                        'message'=> $check['message'] ?? 'Connexion echouée ',
                        'code'=>500,
                        'errors' =>$clientLogin['message'] ?? [],
                    ];
                    return response()->json($dataResponse);
                }
            }else{
                $dataResponse = [
                    'type'=> 'error',
                    'urlback'=> '',
                    'message'=> $check['message'] ?? 'Connexion echouée ',
                    'code'=>500,
                ];
                return response()->json($dataResponse);
            }
        }
        //return $data;
    }

    public function resend_otp(Request $request)
    {
        $url_path = "/customer/authentification/otp/resend";
        $data = [
            'uuid' => $request->uuid,
        ];

        $OtpResponses = (new GlobalSendService())->CallApi($url_path,$data,'POST');
         //return dd($OtpResponses);

        if(isset($OtpResponses["type"])){
            if ($OtpResponses["type"] == "success") {

                $dataResponse =[
                    'type'=>'success',
                    'urlback'=> "",
                    'message'=>$OtpResponses['message'] ?? '',
                    'code'=>200,
                ];
                return response()->json($dataResponse);
            }
            else {
                $dataResponse = [
                    'type'=> 'error',
                    'urlback'=> '',
                    'message'=> $check['message'] ?? 'Connexion echouée ',
                    'code'=>500,
                    'errors' =>$clientLogin['message'] ?? [],
                ];
                return response()->json($dataResponse);
            }
        }else{
            $dataResponse = [
                'type'=> 'error',
                'urlback'=> '',
                'message'=> $check['message'] ?? 'Connexion echouée ',
                'code'=>500,
            ];
            return response()->json($dataResponse);
        }
    }


    public function logout(Request $request){
        $url_path = "/customer/authentification/logout";
        $data = [
            
        ];

        $Responses = (new GlobalSendService())->CallApi($url_path,$data,'POST');
         //return dd($Responses);

        Session::forget('user');
        toastr()->success("Déconnexion effectuée. A bientôt !");

        return redirect()->route('login');
    }

    public function password_forget() {
        return view('customers.auth.forget');
    }

    public function send_password_forget_email(Request $request) {
        $url_path = "/customer/authentification/generate/forget/link";
        $data = [
            'email' =>$request->email ?? '',
        ];

        $check = (new GlobalSendService())->CallApi($url_path,$data,'POST');

        if(isset($check['type'])){
            if($check['type'] =='success'){
                 $dataResponse =[
                     'type'=>'success',
                     'urlback'=>'',
                     'message'=>$check['message'] ?? '',
                     'code'=>200,
                 ];
                 return response()->json($dataResponse);

                /*toastr()->success('Un email de réinitialisation vous a été envoyé a cette adresse email !');
                return redirect()->route('login');*/
            }
            else{
                $dataResponse =[
                      'type'=>'error',
                      'urlback'=>'',
                      'message'=>$check['message'] ?? '',
                      'code'=>500,
                  ];
                  return response()->json($dataResponse);

              /*  toastr()->error("Echec de l'envoi de la demande !");
                return view('auth.passwords.email');*/
            }
        }
        else{
            $dataResponse =[
                'type'=>'error',
                'urlback'=>'',
                'message'=> 'Oups!',
                'code'=>503,
            ];
            return response()->json($dataResponse);
        }

    }

    public function password_reset($token) {
        $url_path = "/customer/authentification/verify/forget/token";
        $data = [
            'token' =>$token ?? '',
        ];

        $check = (new GlobalSendService())->CallApi($url_path,$data,'POST');

        // return dd( $check);
        if(isset($check['type'])){
            if($check['type'] == 'success'){
                $data=$check['data'] ?? '';
                $name = $data["civility"] ?? '';
                $name .= $data['firstname'] ?? '';


                toastr()->success($name.' votre demande de réinitialisation a été approuvé ! Veuillez definir vos nouveaux accès !','Succès');
                return view('customer.auth.password_confirm',['token'=>$data['token'] ?? '']);
            }
            else{
                toastr()->error('Aucune demande de réinitialisation retrouvée !');
                return view('customer.auth.forget');
            }
        }else{
            toastr()->error('Aucune demande de réinitialisation retrouvée !');
            return view('customer.auth.forget');
        }
    }



    public function password_confirm(Request $request) {
        if($request->password != $request->confirm){
            $dataResponse =[
                'type'=>'error',
                'urlback'=>'',
                'message'=>'Mot de passe différent !',
                'code'=>500,
            ];
            return response()->json($dataResponse);
        }

        $url_path = "/customer/authentification/reset/password";
        $data = [
            'password' =>$request->password ?? '',
            'confirm' =>$request->confirm ?? '',
            'token' =>$request->token ?? '',
        ];

        $check = (new GlobalSendService())->CallApi($url_path,$data,'POST');

       // return dd($check);
        if(isset($check['type'])){
            if($check['type'] =='success'){
                $dataResponse =[
                    'type'=>'success',
                    'urlback'=>route('customer.home'),
                    'message'=>$check['message'] ?? '',
                    'code'=>200,
                ];
                return response()->json($dataResponse);
            }
            else{
                $dataResponse =[
                    'type'=>'error',
                    'urlback'=>'',
                    'message'=>$check['message'] ?? '',
                    'code'=>500,
                ];
                return response()->json($dataResponse);
            }
        }
        else{

        }

    }

    public function register_submit(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',            
            'confirm' => 'required',
            'entity_uuid' => 'required'
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

            if($request->password != $request->confirm){
                $dataResponse =[
                    'type'=> 'error',
                    'urlback'=> '',
                    'message'=> 'Mot de passe différents !',
                    'code'=> 500,
                    'step'=> 'Param register'
                    ];
                    return response()->json($dataResponse);
            }

            $url_path = "/customer/authentification/register";
            $data = [
                'email' => $request->email,
                'password' => $request->password,
                'entity_uuid'=> $request->entity_uuid ?? '',
                'firstname' => $request->firstname ?? '',
                'lastname' => $request->lastname ?? '',
                'phone' => $request->telephone ?? '',
                'civility_uuid' => $request->civility_uuid ?? '',
                'element'=> $request->all(),
            ];

            $clientLogin = (new GlobalSendService())->CallApi($url_path,$data,'POST');
           
           // dd($clientLogin);

            if(isset($clientLogin["type"])){
                if ($clientLogin["type"] == "success") {
                   
                    $dataResponse =[
                     'type'=>'success',
                     'urlback'=> route('login'),
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


    }
}
