<?php

namespace App\Http\Controllers;

use ZipArchive;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ResellerSiteDownloadController extends Controller
{
    /**
     * Download the reseller mini-site as a ZIP.
     * All files under resources/reseller-site are included.
     * Optional query: ?api_key=xxx to pre-fill config with that key (adds/overwrites config.php).
     */
    public function __invoke()
    {
        $templatePath = resource_path('reseller-site');
        if (!is_dir($templatePath)) {
            abort(404, 'Reseller site template not found.');
        }

        $apiKey = request()->query('api_key', '');
        $baseUrl = rtrim(config('app.url', 'https://your-platform.com'), '/');

        $zip = new ZipArchive();
        $tempFile = tempnam(sys_get_temp_dir(), 'reseller-site');
        if ($zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            abort(500, 'Could not create zip.');
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($templatePath, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            if (!$item->isFile()) {
                continue;
            }
            $fullPath = $item->getPathname();
            $relativePath = substr($fullPath, strlen($templatePath) + 1);
            $relativePath = str_replace('\\', '/', $relativePath);

            if ($relativePath === 'config.php') {
                continue;
            }
            if (strpos($relativePath, 'data/') === 0) {
                continue;
            }

            $content = file_get_contents($fullPath);
            if ($relativePath === 'config.sample.php' && $apiKey !== '') {
                $content = str_replace("'your_api_key_here'", "'" . addslashes($apiKey) . "'", $content);
            }
            $zip->addFromString($relativePath, $content);
        }

        if ($apiKey !== '') {
            $configContent = "<?php\n";
            $configContent .= "define('RESELLER_API_KEY', '" . addslashes($apiKey) . "');\n";
            $configContent .= "define('API_BASE_URL', '" . addslashes($baseUrl) . "');\n";
            $configContent .= "define('MARKUP_PERCENT', 10);\n";
            $configContent .= "define('SITE_TITLE', 'My Reseller Store');\n";
            $configContent .= "define('BUSINESS_NAME', 'My Reseller Store');\n";
            $configContent .= "define('LOGO_URL', '');\n";
            $configContent .= "define('DB_PATH', __DIR__ . '/data/reseller.sqlite');\n";
            $configContent .= "define('SPRINTPAY_ENABLED', false);\n";
            $configContent .= "define('SPRINTPAY_MERCHANT_ID', '');\n";
            $configContent .= "define('SPRINTPAY_CALLBACK_URL', '');\n";
            $zip->addFromString('config.php', $configContent);
        }

        $zip->close();

        $content = file_get_contents($tempFile);
        @unlink($tempFile);

        return response($content, 200, [
            'Content-Type' => 'application/zip',
            'Content-Disposition' => 'attachment; filename="reseller-site.zip"',
        ]);
    }
}
