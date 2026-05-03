<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\WalletFundWebhookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    /**
     * Check if a user exists by email (e.g. before funding).
     * POST/GET /api/e-check
     */
    public function e_check(Request $request, WalletFundWebhookService $walletWebhooks)
    {
        Log::channel('single')->info('Webhook incoming: e-check', [
            'payload' => $request->all(),
            'ip'      => $request->ip(),
            'method'  => $request->method(),
        ]);

        return $walletWebhooks->check($request);
    }

    /**
     * Webhook: credit user balance and record deposit.
     * POST/GET /api/e-fund
     * Body/query: email, amount, order_id
     */
    public function e_fund(Request $request, WalletFundWebhookService $walletWebhooks)
    {
        Log::channel('single')->info('Webhook incoming: e-fund', [
            'payload' => $request->all(),
            'ip'      => $request->ip(),
            'method'  => $request->method(),
        ]);

        return $walletWebhooks->fund($request);
    }

    /**
     * Return username for an email.
     * POST/GET /api/verify-username
     */
    public function verify_username(Request $request)
    {
        Log::channel('single')->info('Webhook incoming: verify-username', [
            'payload' => $request->only('email'),
            'ip'      => $request->ip(),
            'method'  => $request->method(),
        ]);

        $get_user = User::where('email', $request->email)->first();

        if ($get_user === null) {
            return response()->json([
                'username' => 'Not Found, Please try again',
            ]);
        }

        return response()->json([
            'username' => $get_user->username,
        ]);
    }
}
