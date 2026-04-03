<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use PDO;

class DatabaseBackupService
{
    /**
     * Write a full backup of the default DB connection to $outputPath (file must not exist or be writable).
     *
     * @throws \RuntimeException
     */
    public function writeDefaultConnectionBackupTo(string $outputPath): void
    {
        $connection = config('database.default');
        $config = config("database.connections.{$connection}");

        if (! is_array($config) || empty($config['driver'])) {
            throw new \RuntimeException('Database connection is not configured.');
        }

        $driver = $config['driver'];

        if ($driver === 'mysql') {
            $this->writeMysqlBackup($config, $outputPath);

            return;
        }
        if ($driver === 'sqlite') {
            $this->writeSqliteBackup($config, $outputPath);

            return;
        }
        if ($driver === 'pgsql') {
            $this->writePgsqlBackup($config, $outputPath);

            return;
        }

        throw new \RuntimeException('Backup is not implemented for driver: ' . $driver);
    }

    protected function writeMysqlBackup(array $config, string $outputPath): void
    {
        $defaultsFile = $this->mysqlClientDefaultsFile($config);
        if ($defaultsFile === null) {
            throw new \RuntimeException('Could not create temporary credentials file for backup.');
        }

        $tmpSql = $outputPath . '.wip';
        if (is_file($tmpSql)) {
            @unlink($tmpSql);
        }

        try {
            $ok = $this->runMysqldumpToFile($config, $defaultsFile, $tmpSql);
            if (! $ok) {
                @unlink($tmpSql);
                $this->dumpMysqlTablesWithPhp($tmpSql);
            }
            if (! is_file($tmpSql) || filesize($tmpSql) === 0) {
                throw new \RuntimeException('MySQL backup produced an empty file.');
            }
            if (! @rename($tmpSql, $outputPath)) {
                throw new \RuntimeException('Could not finalize backup file.');
            }
        } finally {
            @unlink($defaultsFile);
            if (is_file($tmpSql)) {
                @unlink($tmpSql);
            }
        }
    }

    protected function mysqlClientDefaultsFile(array $config): ?string
    {
        $path = tempnam(sys_get_temp_dir(), 'my_cnf_');
        if ($path === false) {
            return null;
        }

        $password = (string) ($config['password'] ?? '');
        $escaped = str_replace(['\\', '"', "\n", "\r"], ['\\\\', '\\"', '\\n', '\\r'], $password);
        $lines = ['[client]'];

        if (! empty($config['unix_socket'])) {
            $lines[] = 'socket=' . $config['unix_socket'];
        } else {
            $lines[] = 'host=' . ($config['host'] ?? '127.0.0.1');
            $lines[] = 'port=' . (int) ($config['port'] ?? 3306);
        }

        $lines[] = 'user=' . ($config['username'] ?? 'root');
        $lines[] = 'password="' . $escaped . '"';

        if (file_put_contents($path, implode("\n", $lines) . "\n") === false) {
            @unlink($path);

            return null;
        }
        chmod($path, 0600);

        return $path;
    }

    protected function runMysqldumpToFile(array $config, string $defaultsFile, string $outputPath): bool
    {
        $database = $config['database'] ?? '';
        if (! is_string($database) || $database === '') {
            return false;
        }

        $cmd = [
            'mysqldump',
            '--defaults-extra-file=' . $defaultsFile,
            '--single-transaction',
            '--skip-lock-tables',
            '--routines',
            '--no-tablespaces',
            $database,
        ];

        $descriptorspec = [
            0 => ['pipe', 'r'],
            1 => ['file', $outputPath, 'wb'],
            2 => ['pipe', 'w'],
        ];

        $process = proc_open($cmd, $descriptorspec, $pipes, null, null, ['bypass_shell' => true]);
        if (! is_resource($process)) {
            return false;
        }

        if (isset($pipes[0]) && is_resource($pipes[0])) {
            fclose($pipes[0]);
        }
        if (isset($pipes[2]) && is_resource($pipes[2])) {
            fclose($pipes[2]);
        }

        $code = proc_close($process);
        if ($code !== 0) {
            @unlink($outputPath);

            return false;
        }

        if (! is_file($outputPath) || filesize($outputPath) === 0) {
            @unlink($outputPath);

            return false;
        }

        return true;
    }

    protected function dumpMysqlTablesWithPhp(string $outputPath): void
    {
        $pdo = DB::connection()->getPdo();
        $database = DB::connection()->getDatabaseName();

        $fp = fopen($outputPath, 'wb');
        if ($fp === false) {
            throw new \RuntimeException('Cannot open output file for backup.');
        }

        fwrite($fp, '-- PHP-generated MySQL backup ' . date('c') . "\n");
        fwrite($fp, "SET NAMES utf8mb4;\nSET FOREIGN_KEY_CHECKS=0;\n\n");

        $dbQuoted = str_replace('`', '``', $database);
        $stmt = $pdo->query('SHOW TABLES FROM `' . $dbQuoted . '`');
        $tables = $stmt ? $stmt->fetchAll(PDO::FETCH_COLUMN) : [];

        foreach ($tables as $table) {
            if (! is_string($table) || ! preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
                continue;
            }

            $t = str_replace('`', '``', $table);
            $createRow = $pdo->query('SHOW CREATE TABLE `' . $t . '`')->fetch(PDO::FETCH_ASSOC);
            if (! $createRow || empty($createRow['Create Table'])) {
                continue;
            }

            fwrite($fp, "DROP TABLE IF EXISTS `" . $t . "`;\n");
            fwrite($fp, $createRow['Create Table'] . ";\n\n");

            $select = $pdo->query('SELECT * FROM `' . $t . '`');
            if (! $select) {
                continue;
            }

            while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                $cols = array_keys($row);
                $colList = implode('`,`', array_map(static fn ($c) => str_replace('`', '``', $c), $cols));
                $vals = [];
                foreach ($row as $v) {
                    if ($v === null) {
                        $vals[] = 'NULL';
                    } else {
                        $vals[] = $pdo->quote((string) $v);
                    }
                }
                $valList = implode(',', $vals);
                fwrite($fp, 'INSERT INTO `' . $t . '` (`' . $colList . '`) VALUES (' . $valList . ");\n");
            }
            fwrite($fp, "\n");
        }

        fwrite($fp, "SET FOREIGN_KEY_CHECKS=1;\n");
        fclose($fp);
    }

    protected function writeSqliteBackup(array $config, string $outputPath): void
    {
        $path = $config['database'] ?? '';
        if ($path === ':memory:') {
            throw new \RuntimeException('In-memory SQLite cannot be backed up to file.');
        }

        if ($path === '' || ! is_file($path)) {
            $alt = database_path(basename((string) $path));
            if (is_file($alt)) {
                $path = $alt;
            }
        }

        if (! is_file($path) || ! is_readable($path)) {
            throw new \RuntimeException('SQLite database file was not found or is not readable.');
        }

        $wip = $outputPath . '.wip';
        if (! @copy($path, $wip)) {
            throw new \RuntimeException('Could not copy SQLite database file.');
        }
        if (! @rename($wip, $outputPath)) {
            @unlink($wip);
            throw new \RuntimeException('Could not finalize SQLite backup file.');
        }
    }

    protected function writePgsqlBackup(array $config, string $outputPath): void
    {
        $database = $config['database'] ?? '';
        $host = $config['host'] ?? '127.0.0.1';
        $port = (string) ($config['port'] ?? 5432);
        $user = $config['username'] ?? 'postgres';

        if ($database === '') {
            throw new \RuntimeException('PostgreSQL database name is missing.');
        }

        $wip = $outputPath . '.wip';
        if (is_file($wip)) {
            @unlink($wip);
        }

        $env = [];
        foreach ($_SERVER as $k => $v) {
            if (is_string($k) && is_string($v)) {
                $env[$k] = $v;
            }
        }
        $env['PGPASSWORD'] = (string) ($config['password'] ?? '');

        $cmd = [
            'pg_dump',
            '-h', $host,
            '-p', $port,
            '-U', $user,
            '-d', $database,
            '--no-owner',
            '-F', 'p',
        ];

        $descriptorspec = [
            0 => ['pipe', 'r'],
            1 => ['file', $wip, 'wb'],
            2 => ['pipe', 'w'],
        ];

        $process = proc_open($cmd, $descriptorspec, $pipes, null, $env, ['bypass_shell' => true]);
        if (! is_resource($process)) {
            throw new \RuntimeException('Could not run pg_dump.');
        }

        if (isset($pipes[0]) && is_resource($pipes[0])) {
            fclose($pipes[0]);
        }
        $stderr = isset($pipes[2]) && is_resource($pipes[2]) ? stream_get_contents($pipes[2]) : '';
        if (isset($pipes[2]) && is_resource($pipes[2])) {
            fclose($pipes[2]);
        }

        $code = proc_close($process);
        if ($code !== 0 || ! is_file($wip) || filesize($wip) === 0) {
            @unlink($wip);
            throw new \RuntimeException('pg_dump failed: ' . mb_substr(trim($stderr), 0, 500));
        }

        if (! @rename($wip, $outputPath)) {
            @unlink($wip);
            throw new \RuntimeException('Could not finalize PostgreSQL backup file.');
        }
    }
}
