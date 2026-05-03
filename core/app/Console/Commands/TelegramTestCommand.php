<?php

namespace App\Console\Commands;

use App\Services\TelegramService;
use Illuminate\Console\Command;

class TelegramTestCommand extends Command
{
    protected $signature = 'telegram:test';

    protected $description = 'Send a test message using TELEGRAM_BOT_TOKEN / TELEGRAM_CHAT_ID (and optional _2)';

    public function handle(): int
    {
        $msg = 'LOGS PLUG Telegram test ' . date('c');

        $this->line('Primary (TELEGRAM_BOT_TOKEN + TELEGRAM_CHAT_ID)…');
        $ok1 = TelegramService::sendMessage($msg . ' [primary]', 'primary');
        $this->info($ok1 ? 'Primary: OK' : 'Primary: failed (see storage/logs)');

        $this->line('Secondary (TELEGRAM_BOT_TOKEN_2 + TELEGRAM_CHAT_ID_2, or fallback to primary)…');
        $ok2 = TelegramService::sendMessage($msg . ' [secondary]', 'secondary');
        $this->info($ok2 ? 'Secondary: OK' : 'Secondary: failed (see storage/logs)');

        if (! $ok1 && ! $ok2) {
            $this->warn('Set TELEGRAM_BOT_TOKEN and TELEGRAM_CHAT_ID in .env. If Telegram TLS fails on this host, try TELEGRAM_HTTP_VERIFY=false');

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
