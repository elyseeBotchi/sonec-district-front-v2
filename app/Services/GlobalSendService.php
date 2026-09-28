<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class GlobalSendService
{
    /**
     * @param string $url_path
     * @param array $data
     * @param string $method
     * @return array Toujours un tableau : soit la réponse JSON décodée de
     *               l'API, soit ['type' => 'error', ...] en cas d'échec.
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

            return $response->json();
        } catch (\Throwable $e) {
            Log::error("Erreur lors de l'appel à l'API [{$method} {$url_path}] : " . $e->getMessage());

            return [
                'type' => 'error',
                'message' => "Le service est momentanément indisponible. Veuillez réessayer.",
                'data' => [],
            ];
        }
    }
}
