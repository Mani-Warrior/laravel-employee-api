<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('Authorization');
        if(strpos($apiKey, 'Api-key') === 0){
            $apiKey = substr($apiKey, 8);
        }
        if(!$apiKey || $apiKey !== config('app.api_key')){
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized: Invalid API key'
            ], 401);
        }
        return $next($request);
    }
}
