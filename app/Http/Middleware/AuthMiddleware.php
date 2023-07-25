<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{ /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
    * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
    */
   public function handle(Request $request, Closure $next)
   {
       // Check if the 'Authorization' header exists and starts with 'Bearer '
       if (!$request->header('Authorization') || !str_starts_with($request->header('Authorization'), 'Bearer ')) {
           return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
       }
       try {
           JWTAuth::parseToken()->authenticate();
       } catch (\Exception $e) {
           return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
       }
       return $next($request);
   }
}
