<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    /** @var array<string, true> */
    private static array $notifiedThisRequest = [];

    /**
     * Send to the single configured bot (TELEGRAM_BOT_TOKEN + TELEGRAM_CHAT_ID).
     * Used for explicit one-off messages (e.g. reseller flows); no deduplication.
     */
    public static function sendMessage(string $text): bool
    {
        return self::sendToPrimary($text);
    }

    /**
     * Same as sendMessage, but identical text in the same HTTP request is only sent once
     * (so legacy send_notification + send_notification2 pairs do not double-post).
     */
    public static function notify(string $text): bool
    {
        $key = md5($text);
        if (isset(self::$notifiedThisRequest[$key])) {
            return true;
        }
        self::$notifiedThisRequest[$key] = true;

        return self::sendToPrimary($text);
    }

    private static function sendToPrimary(string $text): bool
    {
        $token = trim((string) config('services.telegram.bot_token', ''));
        $chatId = trim((string) config('services.telegram.chat_id', ''));

        if ($token === '' || $chatId === '') {
            Log::warning('Telegram skipped: set TELEGRAM_BOT_TOKEN and TELEGRAM_CHAT_ID in .env (php artisan telegram:test)', []);

            return false;
        }

        $url = "https://api.telegram.org/bot{$token}/sendMessage";

        $http = Http::timeout(20);
        if (! (bool) config('services.telegram.verify_ssl', true)) {
            $http = $http->withoutVerifying();
        }

        try {
            $response = $http->asForm()->post($url, [
                'chat_id' => $chatId,
                'text' => $text,
                'disable_web_page_preview' => true,
            ]);

            if (! $response->successful()) {
                Log::warning('Telegram API error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::warning('Telegram send exception', ['error' => $e->getMessage()]);

            return false;
        }
    }
}
