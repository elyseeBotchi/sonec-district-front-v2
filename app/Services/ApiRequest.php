<?php
namespace App\Services;
use Illuminate\Support\Facades\Http;

class ApiRequest
{
    public static function send(string $urlPath, array $params = [], string $verb = "GET")
    {
        $request = Http::withHeaders([
            'AuthorizationGate' => 'Bearer ' . API_AccessKey(),
            'Authorization' => 'Bearer ' . (AuthConnect()['AccessToken'] ?? ''),
        ])->timeout(180); // Définit un délai d'attente de 180 secondes
        
        

        $url = config('app.apiBaseUrl') . $urlPath;

        return self::executeRequest($request, $url, $params, $verb);
    }

    private static function executeRequest($request, $url, $params, $verb)
    {
        $httpVerbs = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];

        if (!in_array($verb, $httpVerbs)) {
            throw new \InvalidArgumentException("Invalid HTTP verb: {$verb}");
        }

        // Laisse remonter les exceptions (timeout, connexion refusée, erreur HTTP...)
        // pour que l'appelant (GlobalSendService) les traite de façon uniforme.
        return $request->$verb($url, $params);
    }
    
}

