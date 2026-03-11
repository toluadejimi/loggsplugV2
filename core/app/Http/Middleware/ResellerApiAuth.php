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
        $apiKey = is_string($apiKey) ? trim($apiKey) : '';

        if ($apiKey === '') {
            return response()->json([
                'success' => false,
                'message' => 'API key required. Send X-Api-Key header or api_key in body.',
            ], 401);
        }

        $reseller = Reseller::where('api_key', $apiKey)->first();

        if (!$reseller) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API key. No reseller found with this key. Check the key in config.php or regenerate it on the platform.',
            ], 403);
        }

        if (!$reseller->isActive()) {
            $reason = $reseller->api_key_revoked_at
                ? 'API key was revoked. Regenerate the key in Reseller dashboard on the platform.'
                : 'Reseller account is suspended. Contact the platform admin.';
            return response()->json([
                'success' => false,
                'message' => $reason,
            ], 403);
        }

        $request->attributes->set('reseller', $reseller);
        return $next($request);
    }
}
