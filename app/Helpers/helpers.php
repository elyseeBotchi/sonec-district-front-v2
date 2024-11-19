<?php

/*###############################  ##########################################*/

use App\Models\Credit;
use \App\Models\Notification;
use App\Models\Admin;

use App\Models\User;
use \App\Models\Fonctionality;
use App\Services\GlobalSendService;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use \Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Ixudra\Curl\Facades\Curl;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Jobs\SendEmailJob;
use App\Models\Log_activity;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Admin\FilesController;
use Illuminate\Support\Facades\File;

if(!function_exists('AuthConnect')) {
    function AuthConnect(){
        if(Session::get('admin') != null){ //Log::info(Session::get('admin'));
            return Session::get('admin');
        }
        elseif(Session::get('user') != null){ //Log::info(Session::get('admin'));
            return Session::get('user');
        }
        else{
            return null;
        }
    }
}


if(!function_exists('UserConnect')) {
    function UserConnect(){
        if(Session::get('user') != null){ 
            return Session::get('user');
        }
        else{
            return null;
        }
    }
}


if(!function_exists('SetUserConnect')) {
    function SetUserConnect($data){
        if(Session::get('user') != null){
            Session::put('user', $data);
        }
    }
}


if(!function_exists('SetAuthConnect')) {
    function SetAuthConnect($data){
        if(Session::get('admin') != null){
            Session::put('admin', $data);
        }
        elseif(Session::get('user') != null){
            Session::put('user', $data);
        }
    }
}

if(!function_exists('CanPermission')) {
    function CanPermission($permission){
        if(Session::get('admin') != null){
            //Log::info(Session::get('admin'));
            if(isset(AuthConnect()['permissions'])){
               // return dd(AuthConnect()['permissions']);
                if(AuthConnect()['permissions'] !=""){
                    foreach (AuthConnect()['permissions'] as $perm) {
                        if ($perm['slug'] === $permission) {
                            return true;
                        }
                    }
                }

            }

            return true;
          //  return false;
        }else{
            return true;
           // return false;
        }
    }
}

if(!function_exists('getUserIP')) {
   
    function getUserIP() {
        $os = PHP_OS_FAMILY;
        $ip = 'IP non trouvée';
        $hostname = 'Nom de machine non trouvé';

        // Récupérer le nom de la machine
        if ($os === 'Windows') {
            $hostname = gethostname(); // Méthode intégrée en PHP pour récupérer le nom d'hôte
            $output = shell_exec('ipconfig');
            if (preg_match('/IPv4.*?:\s*([0-9\.]+)/i', $output, $matches)) {
                $ip = $matches[1];
            } elseif (preg_match('/Adresse IPv4.*?:\s*([0-9\.]+)/i', $output, $matches)) { // En cas de format français
                $ip = $matches[1];
            }
        } elseif ($os === 'Darwin') { // Pour macOS
            $hostname = gethostname(); // Utiliser également gethostname()
            $output = shell_exec('ifconfig');
            if (preg_match('/inet ([0-9\.]+) netmask/', $output, $matches)) {
                $ip = $matches[1];
            }
        } else { // Pour Linux/Unix
            $hostname = shell_exec('hostname'); // Récupérer le nom de la machine sous Unix/Linux
            $output = shell_exec('hostname -I');
            $ips = explode(' ', trim($output));
            $ip = $ips[0] ?? 'IP non trouvée';
        }

        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

        // Initialiser les variables pour éviter les erreurs
        $publicIP = 'IP non trouvée';
        $data_location = null;

        try {
            $publicIP = file_get_contents('http://ipinfo.io');
            if ($publicIP !== false) {
                $data_location = \Location::get($publicIP);
                if (is_object($data_location)) {
                    $data_location->ip_prive = $ip ?? '';
                }
            }
        } catch (Exception $e) {
            // Gérer l'erreur ici
            $publicIP = 'Erreur lors de la récupération de l\'IP publique';
            $data_location = 'Erreur lors de la récupération des informations de localisation';
        }

        return [
            "hostname" => trim($hostname), // Ajouter le nom de la machine
            "ip_prive" => $ip,
            "ip" => $publicIP,
            "shell" => json_encode($output),
            "user_agent" => $user_agent,
            "data_location" => $data_location
        ];
    }
}


if(!function_exists('API_AccessKey')) {
    function API_AccessKey() {
              /*eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiZjk3YjMzY2UzZWRiNGY0Mzc1NmYxYzVhMzM1Y2I2ZGU4NDYxNTU5YzE1YTc2ZWRiNjIwMmM4ZTgwZTViMzMxNWRmNWEwMGRjZTdiYjMxYzkiLCJpYXQiOjE3MjQyMzE2NzIuMDc3ODU1LCJuYmYiOjE3MjQyMzE2NzIuMDc3ODYsImV4cCI6MTc1NTc2NzY3MS42MDQ2NDksInN1YiI6IiIsInNjb3BlcyI6W119.O2MeW0TMIf0F5LxzE5nlLn_8cDWZzZa31YM42wf0rjI_VXpbMtBPMB79I9eW_p0tRiS3yMCEZDGv2c0uCYrXjXXXRLfykRXtYFWzIXAp87sTahlT4PKz0d2QE31DLGtzNEeMtzn_0et1pu9mzOuMt8-pcZ-GSp8210LNJ8JqjNG6UuvvxtBai9c3jfeTBaw-xilCVSJ467jS3Y8jpxLrF1_avxMV3nOgLXnaOaci_UzkG2HrL481eD9z7MQ9S8LdLLy7JtBzi0dnnndzfhvYFtoYurR7QmfyTNd6_vN47wbIwQGtQZxShtqFE7-hNcl2KgiYuyW7HZ5IT8TiV4dLHcKSr5cdesK0WxBag83IKTul6jfq4v-MpBaphgDeK3KRTzheXZTtbBa8KkneA_mpqG9t24Qn8KBtXyui0Z6GMGDACVjqlI1QXW3Y2yWvEVcugpbXj8GnDe8xq3NchW3qsgyNHc1tl2LKwM3bcBVNulhDoviv_ipbvc_yYRok_1QE-PeJaQThe2fjs_WXfVWvPQqD5IsaGDWzZm22xBNKK0B2ZZ58SmzdXdfS9jmTXwdTyyOjdAuMLpU1QA-yDLZYN9KchDsJ3XOphZNpRkqfUxrGZX3VawFhOLfw2ch2Us3NP1Bxas6MSnCg2nUs-zoMLI-pedRv5YVoGw_-h4HPIFE*/
        return "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIyIiwianRpIjoiNzEwNDVkNzBkZGI5NzBkNWE4YjM0OTQxZGNhYzk1MzdlMzVmNzEzZWIwZjNiZWRlODc0NGVjYWQyYTk0ODMyYzNmYmZlOGNiNzMxZDIzNWMiLCJpYXQiOjE3MjI4NjY2NzksIm5iZiI6MTcyMjg2NjY3OSwiZXhwIjoxNzIyODcwMjc5LCJzdWIiOiI1Iiwic2NvcGVzIjpbXX0.jL02jxbm-ibBVJJO6lXopLUgOdnA06X2-HlG5xCAo31NJwFFCuzcFm_zFmUlt3wpMZNuYBp6Angi2t26UuFHzUDfBASORTphSWUYgc2iPUeFRTTplj4nLtEWHrMGCmC9iLvccU3I9KIt40MfdaDKx-3mvS0xer-BvLGvmeSR13h2tBpqHCb9QilDDjvCrdWXO3CVDLzU6f2it1vuKpZ533SlWIJ3dPOZEnHEMd9az17Vavy23RPKHFC7ChbqINgtd1HV2QSpldVhz2vOtaMn9839vNaGp-IL6Oc_y6bNJOF1uMt0nRhkaW6raokglVZw4WGjx7g-SXgvmp1Renw9eAnWIPf5hktpiu_BjBHVfo02IVGUCUSnZcdFXYvm0KpBiT-P8p8l0UHEK8uBQVcZmBEYLorWrhTpn0wAa_FdB4uk3xZlVAEDe-b6fmg2HhrO1fPSD1ELX_DxSQRQtUUF4NvpAHHax5-J6cppumEhl_Hs5KpX8Fr2IeY1Gy23MuE5FHdeDojbqC5g6QxMdhCN4q7OXyWQuQfHpOdnkLJS-1YKzWAzxQSONeFWSjzVzbmmcEXuwU5fIFwhuzkLs74svy9fJ9OZlPqeGZMqY7xD59pqlobWSeSmEUUwzO23JeLkLk0Br3QvD0pYQqdzITv6z60lZBrwenz_Nco-35I-zFk"; //csrf_token();
    }
}


if(!function_exists('Entities_Customer')) {
    function Entities_Customer() {
        $url_path = "/services/findAll";

        $data = [
            'user_uuid' => AuthConnect()['uuid'] ?? ''
        ];

        $entitiesList = (new GlobalSendService())->CallApi($url_path,$data,'POST');

      //  return dd($entitiesList);
      if(isset($entitiesList['type'])){
        if($entitiesList['type'] =="success"){
            return $entitiesList['data']; 
        }
        else{

        }
      }else{
        return $entities ?? '';
      }
        
        
    }
}

if(!function_exists('Entity_Customer')) {
    function Entity_Customer() {
        $url_path = "/services/findOne";

        $data = [
            'uuid' => AuthConnect()['entity_uuid'] ?? ''
        ];

        $entitiesList = (new GlobalSendService())->CallApi($url_path,$data,'POST');

       // return dd(AuthConnect());
      if(isset($entitiesList['type'])){
        if($entitiesList['type'] =="success"){
            return $entitiesList['data']; 
        }
        else{

        }
      }else{
        return $entities ?? '';
      }
        
        
    }
}

if(!function_exists('Entities')) {
    function Entities() {
        $url_path = "/autorisations/entite/findAll";

        $data = [
            'admin_uuid' => AuthConnect()['uuid']
        ];

        $entitiesList = (new GlobalSendService())->CallApi($url_path,$data,'GET');

      //  return dd($entitiesList);
      if(isset($entitiesList['type'])){
        if($entitiesList['type'] =="success"){
            return $entitiesList['data']; 
        }
        else{

        }
      }else{
        return $entities ?? '';
      }
        
        
    }
}

if(!function_exists('apiBaseUrl')) {
    function apiBaseUrl() {
        return env('apiBaseUrl') ?? "http://api-district.local/api";
    }
}

if(!function_exists('apiBaseUrlFolder')) {
    function apiBaseUrlFolder() {
        return env('apiBaseUrlFolder') ?? "http://api-district.local";
    }
}


if(!function_exists('enlettre')) {
    function enlettre() {
        return "";
    }
}




if(!function_exists('calculateEndDate')) {
    function calculateEndDate($startDate, $periodicity) {
        $date = new DateTime($startDate);
        
        switch(strtolower($periodicity)) {
            case 'mensuelle':
            case 'mensuel':
            case 'monthly':
                $date->modify('+1 month');
                break;
            case 'trimestrielle':
            case 'trimestriel':
            case 'quarterly':
                $date->modify('+3 months');
                break;
            case 'semestrielle':
            case 'semestriel':
            case 'semi-annuel':
            case 'semi-annually':
                $date->modify('+6 months');
                break;
            case 'annuelle':
            case 'annuel':
            case 'yearly':
                $date->modify('+1 year');
                break;
            default:
                // Gérer le cas où la périodicité n'est pas reconnue
                throw new Exception('Périodicité non reconnue');
        }

        $date->modify('-1 day');

        return $date->format('d-m-Y');
    }
}


if(!function_exists('translatePeriodicity')) {
    function translatePeriodicity($periodicity) {
        
        switch(strtolower($periodicity)) {
            case 'mensuelle':
            case 'mensuel':
            case 'monthly':
                return "Mois";
                break;
            case 'trimestrielle':
            case 'trimestriel':
            case 'quarterly':
                return  "Trimestre";
                break;
            case 'semestrielle':
            case 'semestriel':
            case 'semi-annuel':
            case 'semi-annually':
                return   "Semestre";
                break;
            case 'annuelle':
            case 'annuel':
            case 'yearly':
              return "An";
                break;
            default:
                // Gérer le cas où la périodicité n'est pas reconnue
                throw new Exception('Périodicité non reconnue');
        }
        

    }
}

