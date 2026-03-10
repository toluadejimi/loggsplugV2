<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class LogExcessiveRequests
{
    public function handle($request, Closure $next)
    {
        $ip = $request->ip();
        $key = "req_count_{$ip}";
        $limit = 50;   // max requests
        $minutes = 1;  // time window
        $blockKey = "blocked_{$ip}";

        // If IP is already blocked
        if (Cache::has($blockKey)) {
            return response()->json([
                'message' => 'Too many requests. Your IP has been temporarily blocked.'
            ], 429);
        }

        // Count requests
        $count = Cache::get($key, 0) + 1;
        Cache::put($key, $count, now()->addMinutes($minutes));

        if ($count > $limit) {
            Log::warning("IP BLOCKED: {$ip} made {$count} requests in {$minutes} minute(s).");

            // Block IP for 5 minutes in cache
            Cache::put($blockKey, true, now()->addMinutes(5));

            // Save IP to file
            $filePath = "blocked_ips.txt";
            $existing = Storage::exists($filePath) ? Storage::get($filePath) : "";
            if (!str_contains($existing, $ip)) {
                Storage::append($filePath, $ip);
            }

            return response()->json([
                'message' => 'Too many requests. Your IP has been blocked.'
            ], 429);
        }

        return $next($request);
    }
}
