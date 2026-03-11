<?php

namespace App\Http\Controllers;

use ZipArchive;

class ResellerSiteDownloadController extends Controller
{
    /**
     * Download the reseller mini-site as a ZIP.
     * Optional query: ?api_key=xxx to pre-fill the config with that key.
     */
    public function __invoke()
    {
        $templatePath = resource_path('reseller-site');
        if (!is_dir($templatePath)) {
            abort(404, 'Reseller site template not found.');
        }

        $apiKey = request()->query('api_key', '');
        $files = [
            'config.sample.php', 'index.php', 'README.txt', 'create_includes.php',
            'init_db.php', 'auth_helpers.php', 'admin_helpers.php',
            'login.php', 'register.php', 'profile.php', 'wallet.php', 'fund.php', 'fund_callback.php', 'logout.php',
            'order_details.php', 'my_orders.php',
        ];
        $includesFiles = ['includes/head.php', 'includes/header.php', 'includes/footer.php'];

        $zip = new ZipArchive();
        $tempFile = tempnam(sys_get_temp_dir(), 'reseller-site');
        if ($zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            abort(500, 'Could not create zip.');
        }

        foreach ($files as $name) {
            $fullPath = $templatePath . '/' . $name;
            if (!is_file($fullPath)) {
                continue;
            }
            $content = file_get_contents($fullPath);
            if ($name === 'config.sample.php' && $apiKey !== '') {
                $content = str_replace("'your_api_key_here'", "'" . addslashes($apiKey) . "'", $content);
            }
            $zip->addFromString($name, $content);
        }

        foreach ($includesFiles as $name) {
            $fullPath = $templatePath . '/' . $name;
            if (!is_file($fullPath)) {
                continue;
            }
            $zip->addFromString($name, file_get_contents($fullPath));
        }
        // If API key provided, add ready-to-use config.php (reseller sets API_BASE_URL, BUSINESS_NAME, LOGO_URL)
        if ($apiKey !== '') {
            $configContent = "<?php\n";
            $configContent .= "define('RESELLER_API_KEY', '" . addslashes($apiKey) . "');\n";
            $configContent .= "define('API_BASE_URL', 'https://your-platform.com'); // Change to your platform URL\n";
            $configContent .= "define('MARKUP_PERCENT', 10);\n";
            $configContent .= "define('SITE_TITLE', 'My Reseller Store');\n";
            $configContent .= "define('BUSINESS_NAME', 'My Reseller Store'); // Set your business name\n";
            $configContent .= "define('LOGO_URL', ''); // Optional: URL or path to your logo image\n";
            $configContent .= "define('DB_PATH', __DIR__ . '/data/reseller.sqlite'); // End-user accounts & wallets\n";
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
