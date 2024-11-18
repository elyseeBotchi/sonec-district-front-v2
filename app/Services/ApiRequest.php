<?php
namespace App\Services;
use Illuminate\Support\Facades\Http;

class ApiRequest
{
    public static function send(string $urlPath, array $params = [], string $verb = "GET")
    {
        $request = Http::withHeaders([
            'AuthorizationGate' => 'Bearer ' . API_AccessKey(),
            'Authorization' => 'Bearer '.(AuthConnect()['AccessToken'] ?? ''),
        ]);
        

        $url = config('app.apiBaseUrl') . $urlPath;

        return self::executeRequest($request, $url, $params, $verb);
    }

    private static function executeRequest($request, $url, $params, $verb)
    {
        $httpVerbs = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];

        if (!in_array($verb, $httpVerbs)) {
            return false;
        }
        return $request->$verb($url, $params);
    }
}

