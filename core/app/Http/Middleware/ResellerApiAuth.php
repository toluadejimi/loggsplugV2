<?php

namespace App\Http\Middleware;

use App\Models\Reseller;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResellerApiAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-Api-Key')
            ?? $request->header('Authorization')
            ?? $request->input('api_key');

        if (is_string($apiKey) && str_starts_with($apiKey, 'Bearer ')) {
            $apiKey = trim(substr($apiKey, 7));
        }

        if (empty($apiKey)) {
            return response()->json([
                'success' => false,
                'message' => 'API key required. Send X-Api-Key header or api_key in body.',
            ], 401);
        }

        $reseller = Reseller::where('api_key', $apiKey)->first();

        if (!$reseller || !$reseller->isActive()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or inactive API key.',
            ], 403);
        }

        $request->attributes->set('reseller', $reseller);
        return $next($request);
    }
}
