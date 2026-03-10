<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Forwards SprintPay (and similar) callbacks to the reseller-site fund_callback script.
 * The reseller site lives in resources/reseller-site/ and is not under public/, so
 * this route makes fund_callback.php reachable at e.g. /reseller-site/fund_callback.php
 */
class ResellerFundCallbackController extends Controller
{
    public function __invoke(Request $request)
    {
        $base = resource_path('reseller-site');
        $script = $base . '/fund_callback.php';
        if (!is_file($script)) {
            return response('Callback script not found.', 404);
        }

        $_GET = $request->query->all();
        $_POST = $request->all();

        define('RESELLER_CALLBACK_VIA_LARAVEL', true);
        require $script;

        $code = isset($__reseller_callback_code) ? (int) $__reseller_callback_code : 200;
        $body = isset($__reseller_callback_body) ? $__reseller_callback_body : json_encode(['status' => true, 'message' => 'OK']);

        return response($body, $code, [
            'Content-Type' => 'application/json; charset=utf-8',
        ]);
    }
}
