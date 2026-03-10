<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Illuminate\Support\Facades\Mail;

class ExportMySQLData extends Command
{
    protected $signature = 'mysql:export';

    protected $description = 'Export MySQL data and send via email';

    public function handle()
    {
        // MySQL Database Information
        $db_host = config('database.connections.mysql.host');
        $db_user = config('database.connections.mysql.username');
        $db_pass = config('database.connections.mysql.password');
        $db_name = config('database.connections.mysql.database');

        // File Information
        $date = now()->format('Y-m-d');
        $outputFile = storage_path("app/mysql_backup_{$date}.sql");

        // Export MySQL database
        $command = "mysqldump --host={$db_host} --user={$db_user} --password={$db_pass} {$db_name} > {$outputFile}";
        exec($command);

        // Email the exported file
        $emailTo = 'toluadejimi@gmail.com';
        $emailSubject = 'MySQL Backup - ' . $date;

        Mail::raw('Backup attached.', function ($message) use ($emailTo, $emailSubject, $outputFile) {
            $message->to($emailTo)
                ->subject($emailSubject)
                ->attach($outputFile);
        });

        $message = "File Sent for fadded";
        send_notification($message);

        // Delete the exported file after sending email
        unlink($outputFile);

        $this->info('MySQL data exported and sent via email successfully.');
    }
}
