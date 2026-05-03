<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    /**
     * Send to primary (TELEGRAM_BOT_TOKEN / TELEGRAM_CHAT_ID) or secondary bot/chat.
     * If secondary credentials are missing, falls back to primary so alerts are not lost.
     */
    public static function sendMessage(string $text, string $channel = 'primary'): bool
    {
        if ($channel === 'secondary') {
            $token = trim((string) config('services.telegram.bot_token_2', ''));
            $chatId = trim((string) config('services.telegram.chat_id_2', ''));
            if ($token === '' || $chatId === '') {
                return self::sendMessage($text, 'primary');
            }
        } else {
            $token = trim((string) config('services.telegram.bot_token', ''));
            $chatId = trim((string) config('services.telegram.chat_id', ''));
        }

        if ($token === '' || $chatId === '') {
            Log::warning('Telegram skipped: set TELEGRAM_BOT_TOKEN and TELEGRAM_CHAT_ID in .env (see php artisan telegram:test)', [
                'channel' => $channel,
            ]);

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
                    'channel' => $channel,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::warning('Telegram send exception', [
                'channel' => $channel,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
