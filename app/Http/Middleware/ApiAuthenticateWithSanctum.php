<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthenticateWithSanctum
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->session()->get('api_token');
        dd($token);

        if ($token) {
            $personalAccessToken = PersonalAccessToken::findToken($token);

            if ($personalAccessToken && $personalAccessToken->isValid) {
                Auth::login($personalAccessToken->tokenable);
                return $next($request);
            }
        }

        return response()->json(['message' => 'Unauthenticated.'], Response::HTTP_UNAUTHORIZED);
    }
}
