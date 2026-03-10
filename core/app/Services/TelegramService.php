<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    public static function sendMessage(string $text): bool
    {
        $token = config('services.telegram.bot_token');
        $chatId = config('services.telegram.chat_id');

        if (empty($token) || empty($chatId)) {
            Log::warning('Telegram not configured: missing TELEGRAM_BOT_TOKEN or TELEGRAM_CHAT_ID');
            return false;
        }

        $url = "https://api.telegram.org/bot{$token}/sendMessage";

        try {
            $response = Http::asForm()->post($url, [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => true,
            ]);

            if (!$response->successful()) {
                Log::warning('Telegram send failed', ['status' => $response->status(), 'body' => $response->body()]);
                return false;
            }
            return true;
        } catch (\Throwable $e) {
            Log::warning('Telegram send exception: ' . $e->getMessage());
            return false;
        }
    }
}
