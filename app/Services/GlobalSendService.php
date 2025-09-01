<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GlobalSendService
{
    /**
     * @param $statut
     * @param string $uuid
     * @return JsonResponse
     */
    public function CallApi($url_path,$data,$method)
    {
        /* $getUserIP = getUserIP();
       // Log::info($getUserIP);
        $data['user_agent'] = $getUserIP['user_agent'] ?? $_SERVER['HTTP_USER_AGENT'];
        $data['data_location'] = json_encode($getUserIP['data_location']);
        $data['hostname'] = $getUserIP['hostname'] ?? '';
        
        
        $data['shell'] = json_encode(getUserIP()); */
        try {
            $response = ApiRequest::send($url_path, $data, $method);
           // Log::info($method);
            Log::info($response);
            return $response->json();
           
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        //return $response->json();
    }
}
