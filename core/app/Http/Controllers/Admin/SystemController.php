<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use App\Lib\CurlRequest;
use App\Lib\FileManager;
use App\Models\UpdateLog;
use App\Rules\FileTypeValidate;
use App\Jobs\CreateAdminDatabaseBackupJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laramin\Utility\VugiChugi;

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

    public function migratePage()
    {
        $pageTitle = 'Database migrations';

        return view('admin.system.migrate', compact('pageTitle'));
    }

    public function migrateRun(Request $request)
    {
        try {
            Artisan::call('migrate', ['--force' => true]);
            $output = trim(Artisan::output());
            $notify[] = ['success', 'Migrations finished.'];
            if ($output !== '') {
                $request->session()->flash('migrate_cli_output', $output);
            }

            return back()->withNotify($notify);
        } catch (\Throwable $e) {
            $notify[] = ['error', 'Migration failed: ' . $e->getMessage()];

            return back()->withNotify($notify);
        }
    }

    public function systemServerInfo(){
        $currentPHP = phpversion();
        $pageTitle = 'Server Information';
        $serverDetails = $_SERVER;
        return view('admin.system.server',compact('pageTitle', 'currentPHP', 'serverDetails'));
    }

    public function databaseBackupPage()
    {
        $pageTitle = 'Database backup';
        $files = $this->listPreparedDatabaseBackups();
        $status = Cache::get('admin_db_backup_status');
        $queueDriver = config('queue.default');

        return view('admin.system.database_backup', compact('pageTitle', 'files', 'status', 'queueDriver'));
    }

    public function queueDatabaseBackup()
    {
        $current = Cache::get('admin_db_backup_status');
        if (is_array($current) && ($current['state'] ?? '') === 'running') {
            $notify[] = ['warning', 'A backup is already running. Wait until it finishes, then refresh.'];

            return back()->withNotify($notify);
        }

        $queue = config('queue.default');
        if (in_array($queue, ['database', 'redis', 'beanstalkd', 'sqs'], true)) {
            CreateAdminDatabaseBackupJob::dispatch();
            Cache::put('admin_db_backup_status', [
                'state' => 'queued',
                'message' => 'Waiting for queue worker. Run: php artisan queue:work --timeout=7200',
                'file' => null,
                'queued_at' => time(),
            ], 7200);
            $notify[] = ['success', 'Backup queued. A background worker must be running to process it (see instructions on this page). Refresh periodically until a file appears below.'];
        } else {
            Bus::dispatchAfterResponse(new CreateAdminDatabaseBackupJob());
            Cache::put('admin_db_backup_status', [
                'state' => 'queued',
                'message' => 'Will start after this response is sent (same server process).',
                'file' => null,
                'queued_at' => time(),
            ], 7200);
            $notify[] = ['success', 'Backup started in the background. This page should load quickly—wait and refresh until the new file appears. For very large databases, switch to QUEUE_CONNECTION=database and run a dedicated queue worker.'];
        }

        return back()->withNotify($notify);
    }

    public function databaseBackupStatus()
    {
        return response()->json([
            'status' => Cache::get('admin_db_backup_status'),
            'files' => $this->listPreparedDatabaseBackups(),
        ]);
    }

    public function downloadPreparedDatabaseBackup(string $file)
    {
        $base = basename($file);
        if (! preg_match('/^db_backup_[A-Za-z0-9_.-]+\.(sql|sqlite)$/', $base)) {
            abort(404);
        }

        $path = storage_path('app/backups/database/' . $base);
        if (! is_file($path)) {
            abort(404);
        }

        return response()->download($path, $base);
    }

    /**
     * @return array<int, array{name: string, size: int, mtime: int}>
     */
    protected function listPreparedDatabaseBackups(): array
    {
        $dir = storage_path('app/backups/database');
        if (! is_dir($dir)) {
            return [];
        }

        $out = [];
        foreach (glob($dir . DIRECTORY_SEPARATOR . 'db_backup_*') ?: [] as $full) {
            if (! is_file($full)) {
                continue;
            }
            $out[] = [
                'name' => basename($full),
                'size' => (int) filesize($full),
                'mtime' => (int) filemtime($full),
            ];
        }

        usort($out, static fn ($a, $b) => $b['mtime'] <=> $a['mtime']);

        return $out;
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
