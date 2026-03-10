<?php
/**
 * Reseller Mini-Site Config
 * Copy this file to config.php and fill in your details.
 */

// Your API key (from the platform admin or your reseller dashboard)
define('RESELLER_API_KEY', 'rsl_9LMpUisC9wMaBNLakhI1FfhKVDbBMf9w6hPZN6N9R8XQPzpQ');

// Platform API base URL (no trailing slash). Example: https://loggsplug.online
define('API_BASE_URL', 'http://localhost:9090');

// Your selling margin in percent (added on top of your cost). Example: 15 = 15% markup
define('MARKUP_PERCENT', 10);

// Business name and logo (shown in header). Logo can be a URL or path to image.
define('SITE_TITLE', 'My Reseller Store');
define('BUSINESS_NAME', 'My Reseller Store');
define('LOGO_URL', ''); // e.g. https://yoursite.com/logo.png or /images/logo.png

// Optional: SQLite path for end-user accounts and wallets (leave empty to disable login/wallet)
define('DB_PATH', __DIR__ . '/data/reseller.sqlite');

// Optional: SprintPay for wallet funding (leave empty to hide Fund Wallet)
define('SPRINTPAY_ENABLED', false);
define('SPRINTPAY_MERCHANT_ID', '');
define('SPRINTPAY_CALLBACK_URL', ''); // Full URL to fund_callback.php on your server
