<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use App\Lib\CurlRequest;
use App\Lib\FileManager;
use App\Models\UpdateLog;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laramin\Utility\VugiChugi;
use PDO;

class SystemController extends Controller
{
    public function systemInfo(){
        $laravelVersion = app()->version();
        $timeZone = config('app.timezone');
        $pageTitle = 'Application Information';
        return view('admin.system.info',compact('pageTitle', 'laravelVersion','timeZone'));
    }

    public function optimize(){
        $pageTitle = 'Clear System Cache';
        return view('admin.system.optimize',compact('pageTitle'));
    }

    public function optimizeClear(){
        Artisan::call('optimize:clear');
        $notify[] = ['success','Cache cleared successfully'];
        return back()->withNotify($notify);
    }

    public function systemServerInfo(){
        $currentPHP = phpversion();
        $pageTitle = 'Server Information';
        $serverDetails = $_SERVER;
        return view('admin.system.server',compact('pageTitle', 'currentPHP', 'serverDetails'));
    }

    /**
     * Download a SQL (or SQLite file) backup of the default database. Admin only.
     */
    public function downloadDatabaseBackup()
    {
        $connection = config('database.default');
        $config = config("database.connections.{$connection}");

        if (! is_array($config) || empty($config['driver'])) {
            $notify[] = ['error', 'Database connection is not configured.'];
            return back()->withNotify($notify);
        }

        $driver = $config['driver'];
        $stamp = date('Y-m-d_His');
        $baseName = 'db_backup_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $connection) . '_' . $stamp;

        try {
            if ($driver === 'mysql') {
                return $this->downloadMysqlBackup($config, $baseName . '.sql');
            }
            if ($driver === 'sqlite') {
                return $this->downloadSqliteBackup($config, $baseName . '.sqlite');
            }
            if ($driver === 'pgsql') {
                return $this->downloadPgsqlBackup($config, $baseName . '.sql');
            }
        } catch (\Throwable $e) {
            $notify[] = ['error', 'Backup failed: ' . $e->getMessage()];
            return back()->withNotify($notify);
        }

        $notify[] = ['error', 'Backup is not implemented for driver: ' . $driver];
        return back()->withNotify($notify);
    }

    protected function downloadMysqlBackup(array $config, string $downloadName)
    {
        $defaultsFile = $this->mysqlClientDefaultsFile($config);
        if ($defaultsFile === null) {
            $notify[] = ['error', 'Could not create temporary credentials file for backup.'];
            return back()->withNotify($notify);
        }

        $tmpSql = tempnam(sys_get_temp_dir(), 'dbdump_');
        if ($tmpSql === false) {
            @unlink($defaultsFile);
            $notify[] = ['error', 'Could not create temporary file for backup.'];
            return back()->withNotify($notify);
        }

        try {
            $ok = $this->runMysqldumpToFile($config, $defaultsFile, $tmpSql);
            if (! $ok) {
                @unlink($tmpSql);
                $tmpSql = tempnam(sys_get_temp_dir(), 'dbdump_');
                if ($tmpSql === false) {
                    $notify[] = ['error', 'mysqldump failed and PHP fallback could not create a temp file.'];
                    return back()->withNotify($notify);
                }
                $this->dumpMysqlTablesWithPhp($tmpSql);
            }
        } catch (\Throwable $e) {
            @unlink($tmpSql);
            @unlink($defaultsFile);
            throw $e;
        } finally {
            @unlink($defaultsFile);
        }

        return response()->download($tmpSql, $downloadName)->deleteFileAfterSend(true);
    }

    /**
     * @return string|null Absolute path to a temporary my.cnf-style file (0600), or null on failure
     */
    protected function mysqlClientDefaultsFile(array $config): ?string
    {
        $path = tempnam(sys_get_temp_dir(), 'my_cnf_');
        if ($path === false) {
            return null;
        }

        $password = (string) ($config['password'] ?? '');
        $escaped = str_replace(['\\', '"', "\n", "\r"], ['\\\\', '\\"', '\\n', '\\r'], $password);
        $lines = ["[client]"];

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
        $stderr = isset($pipes[2]) && is_resource($pipes[2]) ? stream_get_contents($pipes[2]) : '';
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

    /**
     * Slower fallback when mysqldump is unavailable; streams rows via PDO.
     */
    protected function dumpMysqlTablesWithPhp(string $outputPath): void
    {
        $pdo = DB::connection()->getPdo();
        $database = DB::connection()->getDatabaseName();

        $fp = fopen($outputPath, 'wb');
        if ($fp === false) {
            throw new \RuntimeException('Cannot open output file for backup.');
        }

        fwrite($fp, "-- PHP-generated MySQL backup " . date('c') . "\n");
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

    protected function downloadSqliteBackup(array $config, string $downloadName)
    {
        $path = $config['database'] ?? '';
        if ($path === ':memory:') {
            $notify[] = ['error', 'In-memory SQLite cannot be downloaded.'];
            return back()->withNotify($notify);
        }

        if ($path === '' || ! is_file($path)) {
            $alt = database_path(basename((string) $path));
            if (is_file($alt)) {
                $path = $alt;
            }
        }

        if (! is_file($path) || ! is_readable($path)) {
            $notify[] = ['error', 'SQLite database file was not found or is not readable.'];
            return back()->withNotify($notify);
        }

        return response()->download($path, $downloadName);
    }

    protected function downloadPgsqlBackup(array $config, string $downloadName)
    {
        $database = $config['database'] ?? '';
        $host = $config['host'] ?? '127.0.0.1';
        $port = (string) ($config['port'] ?? 5432);
        $user = $config['username'] ?? 'postgres';

        if ($database === '') {
            $notify[] = ['error', 'PostgreSQL database name is missing.'];
            return back()->withNotify($notify);
        }

        $tmpSql = tempnam(sys_get_temp_dir(), 'pgdump_');
        if ($tmpSql === false) {
            $notify[] = ['error', 'Could not create temporary file for backup.'];
            return back()->withNotify($notify);
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
            1 => ['file', $tmpSql, 'wb'],
            2 => ['pipe', 'w'],
        ];

        $process = proc_open($cmd, $descriptorspec, $pipes, null, $env, ['bypass_shell' => true]);
        if (! is_resource($process)) {
            @unlink($tmpSql);
            $notify[] = ['error', 'Could not run pg_dump.'];
            return back()->withNotify($notify);
        }

        if (isset($pipes[0]) && is_resource($pipes[0])) {
            fclose($pipes[0]);
        }
        $stderr = isset($pipes[2]) && is_resource($pipes[2]) ? stream_get_contents($pipes[2]) : '';
        if (isset($pipes[2]) && is_resource($pipes[2])) {
            fclose($pipes[2]);
        }

        $code = proc_close($process);
        if ($code !== 0 || ! is_file($tmpSql) || filesize($tmpSql) === 0) {
            @unlink($tmpSql);
            $notify[] = ['error', 'pg_dump failed: ' . Str::limit(trim($stderr), 500)];
            return back()->withNotify($notify);
        }

        return response()->download($tmpSql, $downloadName)->deleteFileAfterSend(true);
    }

    public function systemUpdate() {
        $pageTitle = 'System Updates';
        $updates = UpdateLog::orderBy('id','desc')->paginate(getPaginate());
        return view('admin.system.update',compact('pageTitle','updates'));
    }

    public function updateUpload(Request $request) {

        if (gs()->system_customized) {
            $notify[]=['error','The system already customized. You can\'t update the project.'];
            return back()->withNotify($notify);
        }

        $request->validate([
            'purchase_code' => 'required',
            'envato_username'        => 'required',
            'file'                   => ['required', new FileTypeValidate(['zip'])],
        ]);

        if(!extension_loaded('zip')){
            $notify[]=['error','zip Extension is required to install the template'];
            return back()->withNotify($notify);
        }

        $location = 'core/temp';

        //Upload the zip file
        try {
            $fileName = fileUploader($request->file, $location);
        } catch (\Exception $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }

        $rand    = Str::random(10);
        $dir     = base_path('temp/' . $rand);
        $extract = $this->extractZip(base_path('temp/' . $fileName), $dir);

        if ($extract == false) {
            $this->removeDir($dir);
            $notify[] = ['error', 'Something went wrong to extract'];
            return back()->withNotify($notify);
        }

        //get config file
        if (!file_exists($dir . '/config.json')) {
            $this->removeDir($dir);
            $notify[] = ['error', 'Config file not found'];
            return back()->withNotify($notify);
        }

        $getConfig = file_get_contents($dir . '/config.json');
        $config    = json_decode($getConfig);

        $this->removeFile($location . '/' . $fileName);

        $param['code']    = $request->purchase_code;
        $param['url']     = env("APP_URL");
        $param['email']    = auth()->guard('admin')->user()->email;
        $param['user']    = $request->envato_username;
        $param['product'] = systemDetails()['name'];
        $reqRoute         = VugiChugi::lcLabSbm();
        $response         = CurlRequest::curlPostContent($reqRoute, $param);
        $response         = json_decode($response);

        if ($response->error == 'error') {
            $this->removeDir($dir);
            $general = gs();
            $general->maintenance_mode = 9;
            $general->save();
            $notify[] = ['error', $response->message];
            return back()->withNotify($notify);
        }

        $mainFile = $dir . '/update.zip';
        if (!file_exists($mainFile)) {
            $this->removeDir($dir);
            $notify[] = ['error', 'Main file not found'];
            return back()->withNotify($notify);
        }

        //move file
        $extract = $this->extractZip(base_path('temp/' . $rand . '/update.zip'), base_path('../'));
        if ($extract == false) {
            $notify[] = ['error', 'Something went wrong to extract'];
            return back()->withNotify($notify);
        }

        //Execute database
        if (file_exists($dir . '/update.sql')) {
            $sql = file_get_contents($dir . '/update.sql');
            DB::unprepared($sql);
        }

        $updateLog = new UpdateLog();
        $updateLog->version = $config->version;
        $updateLog->update_log = $config->changes;
        $updateLog->save();

        $this->removeDir($dir);

        $notify[] = ['success', 'Template uploaded successfully'];
        return back()->withNotify($notify);
    }

    protected function extractZip($file, $extractTo)
    {
        $zip = new \ZipArchive;
        $res = $zip->open($file);
        if ($res != true) {
            return false;
        }
        $res = $zip->extractTo($extractTo);
        $zip->close();
        return true;
    }

    protected function removeFile($path)
    {
        $fileManager = new FileManager();
        $fileManager->removeFile($path);
    }

    protected function removeDir($location)
    {
        $fileManager = new FileManager();
        $fileManager->removeDirectory($location);
    }
}
