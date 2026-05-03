<?php

namespace App\Console\Commands;

use App\Services\TelegramService;
use Illuminate\Console\Command;

class TelegramTestCommand extends Command
{
    protected $signature = 'telegram:test';

    protected $description = 'Send a test message using TELEGRAM_BOT_TOKEN + TELEGRAM_CHAT_ID';

    public function handle(): int
    {
        $msg = 'LOGS PLUG Telegram test ' . date('c');

        $ok = TelegramService::sendMessage($msg);
        if ($ok) {
            $this->info('Telegram: OK (check your chat).');

            return self::SUCCESS;
        }

        $this->warn('Telegram: failed — set TELEGRAM_BOT_TOKEN and TELEGRAM_CHAT_ID in .env, then php artisan config:clear');
        $this->warn('If TLS fails on this host, try TELEGRAM_HTTP_VERIFY=false');

        return self::FAILURE;
    }
}
