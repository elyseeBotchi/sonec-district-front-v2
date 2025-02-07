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
use Illuminate\Support\Facades\Cache;
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

          //  return true;
            return false;
        }else{
           //  return true;
           return false;
        }
    }
}

if(!function_exists('getUserIP')) {
   
    function getUserIP() {
        $os = PHP_OS_FAMILY;
        $ip = 'IP non trouvée';
        $hostname = 'Nom de machine non trouvé';

    /*     // Récupérer le nom de la machine
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
        } */

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
           // "shell" => json_encode($output),
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


if (!function_exists('Entities_Customer')) {
    function Entities_Customer() {
        $url_path = "/services/findAll";
        $cache_key = 'entities_customer_list'; // Définir une clé de cache unique
    
        // Vérifier ou récupérer depuis le cache
        $entitiesList = Cache::remember($cache_key, 1440, function () use ($url_path) {
            $data = [
                'user_uuid' => AuthConnect()['uuid'] ?? ''
            ];
    
            // Appel à l'API
            return (new GlobalSendService())->CallApi($url_path, $data, 'POST');
        });
    
        // Vérifier si la réponse provient du cache ou de l'API
        if (Cache::has($cache_key)) {
            Log::info("Données récupérées depuis le cache pour Entities_Customer.");
        } else {
            Log::info("Données récupérées depuis l'API pour Entities_Customer.");
        }
    
        // Vérification si les données sont valides
        if (isset($entitiesList['type']) && $entitiesList['type'] === 'success') {
            return $entitiesList['data'];
        }
    
        // Si la réponse en cache est invalide, forcer un nouvel appel à l'API
        $data = [
            'user_uuid' => AuthConnect()['uuid'] ?? ''
        ];
        $newEntitiesList = (new GlobalSendService())->CallApi($url_path, $data, 'POST');
    
        // Si le nouvel appel réussit, mettre à jour le cache et retourner les données
        if (isset($newEntitiesList['type']) && $newEntitiesList['type'] === 'success') {
            Cache::put($cache_key, $newEntitiesList, 1440); // Mettre à jour le cache
            return $newEntitiesList['data'];
        }
    
        // Si tout échoue, retourner une valeur par défaut
        Log::error("Échec de récupération des données pour Entities_Customer.");
        return [];
    }
}



if(!function_exists('Entities_Customer__old')) {
    function Entities_Customer__old() {
        $url_path = "/services/findAll";
        $session_key = 'entities_list';
    
        // Vérifie si les données sont déjà en session
        if (session()->has($session_key)) {
            return session($session_key);
        }
    
        $data = [
            'user_uuid' => AuthConnect()['uuid'] ?? ''
        ];
    
        // Appel de l'API
        $entitiesList = (new GlobalSendService())->CallApi($url_path, $data, 'POST');
    
        // Vérification du type de réponse
        if (isset($entitiesList['type'])) {
            if ($entitiesList['type'] === "success") {
                // Stocker les données en session
                session([$session_key => $entitiesList['data']]);
                return $entitiesList['data'];
            } else {
                // Gérer les cas où 'type' n'est pas 'success'
                return [];
            }
        }
    
        // Retourner une valeur par défaut si aucune donnée valide n'est trouvée
        return [];
    }

        /* function Entities_Customer_old() {

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
            
            
        } */
}

if(!function_exists('Entity_Customer')) {

    function Entity_Customer()
    {
        $url_path = "/services/findOne";
        $cache_key = 'entity_customer';

        // Vérifier ou récupérer depuis le cache
        $entitiesList = Cache::remember($cache_key, 60, function () use ($url_path) {
            $data = [
                'uuid' => AuthConnect()['entity_uuid'] ?? ''
            ];

            // Appel à l'API
            return (new GlobalSendService())->CallApi($url_path, $data, 'POST');
        });

        if (Cache::has($cache_key)) {
            Log::info("Données récupérées depuis le cache pour Entity_Customer");
        } else {
            Log::info("Données récupérées depuis l'API pour Entity_Customer");
        }
        
        // Si les données en cache sont valides, on les retourne
        if (isset($entitiesList['type']) && $entitiesList['type'] == "success") {
            return $entitiesList['data'];
        }

        // Forcer un nouvel appel à l'API si les données sont invalides
        $data = [
            'uuid' => AuthConnect()['entity_uuid'] ?? ''
        ];
        $newEntitiesList = (new GlobalSendService())->CallApi($url_path, $data, 'POST');

        // Si le nouvel appel réussit, mettre à jour le cache et retourner les données
        if (isset($newEntitiesList['type']) && $newEntitiesList['type'] == "success") {
            Cache::put($cache_key, $newEntitiesList, 60); // Met à jour le cache
            return $newEntitiesList['data'];
        }

        // Si tout échoue, retourner une valeur par défaut
        return null;
    }



  /*   function Entity_Customer() {
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
        
        
    } */
}

if(!function_exists('Entities')) {
    /* function Entities() {
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
        
        
    } */

    function Entities()
    {
        $url_path = "/autorisations/entite/findAll";
        $cache_key = 'entities';
    
        // Vérifier ou récupérer depuis le cache
        $entitiesList = Cache::remember($cache_key, 60, function () use ($url_path) {
            $data = [
                'uuid' => AuthConnect()['entity_uuid'] ?? ''
            ];
    
            // Appel à l'API
            return (new GlobalSendService())->CallApi($url_path, $data, 'GET');
        });
    
        if (Cache::has($cache_key)) {
            Log::info("Données récupérées depuis le cache pour Entities");
        } else {
            Log::info("Données récupérées depuis l'API pour Entities");
        }
        // Si les données en cache sont valides, on les retourne
        if (isset($entitiesList['type']) && $entitiesList['type'] == "success") {
            return $entitiesList['data'];
        }
    
        // Forcer un nouvel appel à l'API si les données sont invalides
        $data = [
            'uuid' => AuthConnect()['entity_uuid'] ?? ''
        ];
        $newEntitiesList = (new GlobalSendService())->CallApi($url_path, $data, 'GET');
    
        // Si le nouvel appel réussit, mettre à jour le cache et retourner les données
        if (isset($newEntitiesList['type']) && $newEntitiesList['type'] == "success") {
            Cache::put($cache_key, $newEntitiesList, 60); // Met à jour le cache
            return $newEntitiesList['data'];
        }
    
        // Si tout échoue, retourner une valeur par défaut
        return null;
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

if (!function_exists('enlettre_')) {
    function enlettre_($nombre) {
        $unites = ["", "un", "deux", "trois", "quatre", "cinq", "six", "sept", "huit", "neuf"];
        $dizaines = ["", "dix", "vingt", "trente", "quarante", "cinquante", "soixante", "soixante-dix", "quatre-vingt", "quatre-vingt-dix"];
        $specials = [11 => "onze", 12 => "douze", 13 => "treize", 14 => "quatorze", 15 => "quinze", 16 => "seize"];

        // Cas pour zéro
        if ($nombre == 0) {
            return "zéro";
        }

        // Cas pour les nombres négatifs
        if ($nombre < 0) {
            return "moins " . enlettre(-$nombre);
        }

        $texte = "";

        // Gestion des millions
        if ($nombre >= 1000000) {
            $millions = intval($nombre / 1000000);
            $reste = $nombre % 1000000;
            $texte .= ($millions > 1 ? enlettre($millions) . " millions" : "un million");
            if ($reste > 0) {
                $texte .= " " . enlettre($reste);
            }
            return $texte;
        }

        // Gestion des milliers
        if ($nombre >= 1000) {
            $milliers = intval($nombre / 1000);
            $reste = $nombre % 1000;
            $texte .= ($milliers > 1 ? enlettre($milliers) . " mille" : "mille");
            if ($reste > 0) {
                $texte .= " " . enlettre($reste);
            }
            return $texte;
        }

        // Gestion des centaines
        if ($nombre >= 100) {
            $centaines = intval($nombre / 100);
            $reste = $nombre % 100;
            $texte .= ($centaines > 1 ? $unites[$centaines] . " cent" : "cent");
            if ($reste > 0) {
                $texte .= " " . enlettre($reste);
            }
            return $texte;
        }

        // Gestion des dizaines
        if ($nombre >= 20) {
            $dix = intval($nombre / 10);
            $reste = $nombre % 10;
            $texte .= $dizaines[$dix];
            if ($dix == 7 || $dix == 9) { // Cas des soixante-dix et quatre-vingt-dix
                $texte .= "-" . enlettre(10 + $reste);
            } elseif ($reste > 0) {
                $texte .= "-" . $unites[$reste];
            }
            return $texte;
        }

        // Gestion des nombres entre 11 et 19
        if ($nombre >= 11) {
            return $specials[$nombre];
        }

        // Gestion des unités (1 à 9)
        return $unites[$nombre];
    }
}

if (!function_exists('enlettre')) {
    function enlettre($nombre) {
        $unites = ["", "un", "deux", "trois", "quatre", "cinq", "six", "sept", "huit", "neuf"];
        $dizaines = ["", "dix", "vingt", "trente", "quarante", "cinquante", "soixante", "soixante-dix", "quatre-vingt", "quatre-vingt-dix"];
        $specials = [11 => "onze", 12 => "douze", 13 => "treize", 14 => "quatorze", 15 => "quinze", 16 => "seize", 17 => "dix-sept", 18 => "dix-huit", 19 => "dix-neuf"];

        // Cas pour zéro
        if ($nombre == 0) {
            return "zéro";
        }

        // Cas pour les nombres négatifs
        if ($nombre < 0) {
            return "moins " . enlettre(-$nombre);
        }

        $texte = "";

        // Gestion des millions
        if ($nombre >= 1000000) {
            $millions = intval($nombre / 1000000);
            $reste = $nombre % 1000000;
            $texte .= ($millions > 1 ? enlettre($millions) . " millions" : "un million");
            if ($reste > 0) {
                $texte .= " " . enlettre($reste);
            }
            return $texte;
        }

        // Gestion des milliers
        if ($nombre >= 1000) {
            $milliers = intval($nombre / 1000);
            $reste = $nombre % 1000;
            $texte .= ($milliers > 1 ? enlettre($milliers) . " mille" : "mille");
            if ($reste > 0) {
                $texte .= " " . enlettre($reste);
            }
            return $texte;
        }

        // Gestion des centaines
        if ($nombre >= 100) {
            $centaines = intval($nombre / 100);
            $reste = $nombre % 100;
            $texte .= ($centaines > 1 ? $unites[$centaines] . " cent" : "cent");
            if ($reste > 0) {
                $texte .= " " . enlettre($reste);
            }
            return $texte;
        }

        // Gestion des dizaines
        if ($nombre >= 20) {
            $dix = intval($nombre / 10);
            $reste = $nombre % 10;
            $texte .= $dizaines[$dix];
            if ($dix == 7 || $dix == 9) { // Cas des soixante-dix et quatre-vingt-dix
                $texte .= "-" . enlettre(10 + $reste);
            } elseif ($reste > 0) {
                $texte .= "-" . $unites[$reste];
            }
            return $texte;
        }

        // Gestion des nombres entre 11 et 19
        if ($nombre >= 11 && $nombre <= 19) {
            return $specials[$nombre];
        }

        // Gestion des unités (1 à 9)
        return $unites[$nombre];
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

if(!function_exists('liste_banques')){
    function liste_banques() {
        $banques = [
            ["nom" => "AFG BANK CÔTE D'IVOIRE", "sigle" => "AFG","status" => true],
            ["nom" => "BANK OF AFRICA - CÔTE D'IVOIRE", "sigle" => "BOA","status" => true],
            ["nom" => "BANQUE ATLANTIQUE CÔTE D'IVOIRE", "sigle" => "BAC","status" => true],
            ["nom" => "BANQUE D'ABIDJAN", "sigle" => "BDA","status" => true],
            ["nom" => "BANQUE DE L'HABITAT DE CÔTE D'IVOIRE", "sigle" => "BHCI","status" => true],
            ["nom" => "BANQUE DE L'UNION - CÔTE D'IVOIRE", "sigle" => "BUDI","status" => true],
            ["nom" => "BANQUE INTERNATIONALE POUR LE COMMERCE ET L'INDUSTRIE DE LA CÔTE D'IVOIRE", "sigle" => "BICICI"],
            ["nom" => "BANQUE NATIONALE D'INVESTISSEMENT", "sigle" => "BNI","status" => true],
            ["nom" => "BANQUE POPULAIRE DE CÔTE D'IVOIRE", "sigle" => "BPCI","status" => true],
            ["nom" => "BANQUE SAHÉLO-SAHARIENNE POUR L'INVESTISSEMENT ET LE COMMERCE - CÔTE D'IVOIRE", "sigle" => "BSIC","status" => true],
            ["nom" => "BGFIBANK CÔTE D'IVOIRE", "sigle" => "BGFI","status" => true],
            ["nom" => "BRIDGE BANK GROUP CÔTE D'IVOIRE", "sigle" => "BBG","status" => true],
            ["nom" => "CITIBANK CÔTE D'IVOIRE", "sigle" => "CITI","status" => true],
            ["nom" => "CORIS BANK INTERNATIONAL CÔTE D'IVOIRE", "sigle" => "CBI","status" => true],
            ["nom" => "ECOBANK - CÔTE D'IVOIRE", "sigle" => "ECOBANK","status" => true],
            ["nom" => "GUARANTY TRUST BANK CÔTE D'IVOIRE", "sigle" => "GTB","status" => true],
            ["nom" => "MANSA BANK", "sigle" => "MANSA","status" => true],
            ["nom" => "NSIA BANQUE CÔTE D'IVOIRE", "sigle" => "NSIA","status" => true],
            ["nom" => "ORABANK CÔTE D'IVOIRE", "sigle" => "ORABANK"],
            ["nom" => "SOCIÉTÉ GÉNÉRALE DE BANQUES EN CÔTE D'IVOIRE", "sigle" => "SGBCI","status" => true],
            ["nom" => "SOCIÉTÉ IVOIRIENNE DE BANQUE", "sigle" => "SIB","status" => true],
            ["nom" => "STANDARD CHARTERED BANK CÔTE D'IVOIRE", "sigle" => "SCB","status" => true],
            ["nom" => "UNITED BANK FOR AFRICA CÔTE D'IVOIRE", "sigle" => "UBA","status" => true],
            ["nom" => "VERSUS BANK", "sigle" => "VERSUS","status" => true]
        ];
        return $banques;
        
    }
}

if(!function_exists('getStatusBadge')){
    function getStatusBadge($status) {
        switch ($status) {
            case 'init':
                $statusBadge = '<span class="badge rounded-pill badge-secondary">Brouillon</span>';
                break;
            case 'enable':
                $statusBadge = '<span class="badge badge-pill badge-warning">En attente de cotation</span>';
                break;
            case 'validate':
                $statusBadge = '<span class="badge badge-pill badge-success">Validé</span>';
                break;
            case 'disable':
                $statusBadge = '<span class="badge rounded-pill badge-warning">Suspendu</span>';
                break;
            case 'fail':
                $statusBadge = '<span class="badge rounded-pill badge-danger">Annulé</span>';
                break;
                
            case 'pending':
                $statusBadge = '<span class="badge rounded-pill badge-primary">Annulé</span>';
                break;
            default:
                $statusBadge = '<span class="badge badge-pill badge-light">Inconnu</span>';
                break;
        }

        return $statusBadge;
    }
}

