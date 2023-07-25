<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    //
    public function generateBearerToken()
    {
        $apiKey = env('API_KEY');
        $secretKey = env('SECRET_KEY');

        // Define the payload data 
        $payload = [
            'iat' => time(), // Issued At (current timestamp)
            'jti' => time() + 3600, // Expiration Time (current timestamp + 1 hour)
            'sub' => $apiKey, // Your API key
        ];

        try {
            $jwt = JWT::encode($payload, $secretKey, 'HS256');
            return $jwt;

        } catch (\Exception $e) {
            // Handle any exceptions that occur during token generation
            return response()->json(['error' => 'Failed to generate bearer token'], 500);
            //return null;
        }
    }

    public function getToken()
    {
        $token = $this->generateBearerToken();

        if ($token) {
            return response()->json(['token' => $token]);
        } else {
            return response()->json(['error' => 'Failed to generate bearer token'], 500);
        }
    }
}
