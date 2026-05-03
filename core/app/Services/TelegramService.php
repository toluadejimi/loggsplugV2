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
            $token = (string) config('services.telegram.bot_token_2', '');
            $chatId = (string) config('services.telegram.chat_id_2', '');
            if ($token === '' || $chatId === '') {
                return self::sendMessage($text, 'primary');
            }
        } else {
            $token = (string) config('services.telegram.bot_token', '');
            $chatId = (string) config('services.telegram.chat_id', '');
        }

        if ($token === '' || $chatId === '') {
            Log::warning('Telegram not configured: missing bot token or chat id', ['channel' => $channel]);

            return false;
        }

        $url = "https://api.telegram.org/bot{$token}/sendMessage";

        try {
            $response = Http::timeout(15)->asForm()->post($url, [
                'chat_id' => $chatId,
                'text' => $text,
                'disable_web_page_preview' => true,
            ]);

            if (! $response->successful()) {
                Log::warning('Telegram send failed', [
                    'channel' => $channel,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::warning('Telegram send exception: ' . $e->getMessage(), ['channel' => $channel]);

            return false;
        }
    }
}
