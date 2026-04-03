<?php

namespace App\Jobs;

use App\Services\DatabaseBackupService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CreateAdminDatabaseBackupJob implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 7200;

    public int $tries = 1;

    public function uniqueId(): string
    {
        return 'admin-database-backup';
    }

    public function handle(DatabaseBackupService $backupService): void
    {
        $dir = storage_path('app/backups/database');
        if (! is_dir($dir) && ! @mkdir($dir, 0750, true) && ! is_dir($dir)) {
            throw new \RuntimeException('Could not create backup directory.');
        }

        $connection = config('database.default');
        $config = config("database.connections.{$connection}");
        $driver = is_array($config) ? ($config['driver'] ?? 'mysql') : 'mysql';
        $ext = $driver === 'sqlite' ? '.sqlite' : '.sql';

        $stamp = date('Y-m-d_His');
        $safeConn = preg_replace('/[^a-zA-Z0-9_-]/', '_', (string) $connection);
        $baseName = 'db_backup_' . $safeConn . '_' . $stamp . '_' . Str::lower(Str::random(6));
        $finalPath = $dir . DIRECTORY_SEPARATOR . $baseName . $ext;

        Cache::put('admin_db_backup_status', [
            'state' => 'running',
            'message' => 'Writing backup…',
            'file' => null,
            'started_at' => time(),
        ], 86400);

        try {
            $backupService->writeDefaultConnectionBackupTo($finalPath);
        } catch (\Throwable $e) {
            $this->markFailed($e);
            throw $e;
        }

        Cache::put('admin_db_backup_status', [
            'state' => 'done',
            'message' => 'Backup completed.',
            'file' => basename($finalPath),
            'finished_at' => time(),
        ], 86400);
    }

    public function failed(?\Throwable $exception): void
    {
        $this->markFailed($exception);
    }

    protected function markFailed(?\Throwable $exception): void
    {
        Cache::put('admin_db_backup_status', [
            'state' => 'failed',
            'message' => $exception ? $exception->getMessage() : 'Backup job failed.',
            'file' => null,
            'finished_at' => time(),
        ], 86400);
    }
}
