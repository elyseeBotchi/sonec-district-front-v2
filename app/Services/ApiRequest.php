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
        ])->timeout(60); // Définit un délai d'attente de 180 secondes
        
        

        $url = config('app.apiBaseUrl') . $urlPath;

        return self::executeRequest($request, $url, $params, $verb);
    }

    private static function executeRequest($request, $url, $params, $verb)
    {
        $httpVerbs = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];
    
        if (!in_array($verb, $httpVerbs)) {
            return response()->json(['error' => 'Invalid HTTP verb'], 400);
        }
    
        try {
            return $request->$verb($url, $params);
        } catch (\Illuminate\Http\Client\RequestException $e) {
            // Gestion des erreurs spécifiques à la requête
            return response()->json([
                'error' => 'Request failed',
                'message' => $e->getMessage(),
                'status' => $e->response ? $e->response->status() : null,
            ], 500);
        } catch (\Exception $e) {
            // Gestion des autres exceptions
            return response()->json([
                'error' => 'Unexpected error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    
}

