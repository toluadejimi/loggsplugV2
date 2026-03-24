<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AddCorsHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $origin = $request->header('Origin');
        $allowed = $this->isOriginAllowed($origin);

        if ($request->isMethod('OPTIONS')) {
            return response('', 204)
                ->withHeaders($this->corsHeaders($origin, $allowed));
        }

        $response = $next($request);

        if ($allowed) {
            foreach ($this->corsHeaders($origin, true) as $key => $value) {
                $response->header($key, $value);
            }
        }

        return $response;
    }

    private function isOriginAllowed(?string $origin): bool
    {
        if (!$origin) {
            return false;
        }
        if (preg_match('#^http://(localhost|127\.0\.0\.1)(:\d+)?$#', $origin)) {
            return true;
        }
        if (preg_match('#^https?://[a-zA-Z0-9.-]+(:\d+)?$#', $origin) && env('FRONTEND_URL')) {
            return trim(env('FRONTEND_URL'), '/') === trim(parse_url($origin, PHP_URL_SCHEME) . '://' . parse_url($origin, PHP_URL_HOST));
        }
        return false;
    }

    private function corsHeaders(?string $origin, bool $allowed): array
    {
        $headers = [
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, PATCH, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Accept, Authorization, X-Requested-With',
            'Access-Control-Max-Age' => '86400',
        ];
        if ($allowed && $origin) {
            $headers['Access-Control-Allow-Origin'] = $origin;
            $headers['Access-Control-Allow-Credentials'] = 'true';
        }
        return $headers;
    }
}
