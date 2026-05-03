<?php

namespace App\Services;

use App\Constants\Status;
use App\Models\Deposit;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WalletFundWebhookService
{
    /**
     * Authenticate wallet webhooks: optional shared secret, or Enkpay-style Authorization + body key.
     */
    public function assertAuthorized(Request $request): ?JsonResponse
    {
        $secret = trim((string) config('wallet.fund_webhook_secret', ''));
        if ($secret !== '') {
            $provided = (string) ($request->header('X-Wallet-Webhook-Secret') ?: $request->input('webhook_secret', ''));
            if ($provided === '' || ! hash_equals($secret, $provided)) {
                Log::warning('wallet webhook: bad shared secret', ['ip' => $request->ip()]);

                return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
            }

            return null;
        }

        $authExpect = trim((string) config('wallet.fund_webhook_authorization', ''));
        $keyExpect = trim((string) config('wallet.fund_webhook_webkey', ''));

        if ($authExpect !== '' || $keyExpect !== '') {
            $authHeader = (string) $request->header('Authorization', '');
            $authOk = $authExpect === '' || hash_equals($authExpect, $authHeader);

            $bodyKey = (string) $request->input('key', '');
            $keyOk = $keyExpect === '' || hash_equals($keyExpect, $bodyKey);

            if ($authOk && $keyOk) {
                return null;
            }

            Log::warning('wallet webhook: bad Enkpay-style auth or key', ['ip' => $request->ip()]);

            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        }

        if (app()->environment('production')) {
            return response()->json([
                'status' => false,
                'message' => 'Server misconfiguration: set WALLET_FUND_WEBHOOK_SECRET, or set WALLET_FUND_WEBHOOK_AUTHORIZATION (exact Authorization header value) and/or WALLET_FUND_WEBHOOK_WEBKEY (must match JSON key).',
            ], 503);
        }

        return null;
    }

    /**
     * Idempotent credit: same order_id only increases balance once.
     */
    public function fund(Request $request): JsonResponse
    {
        if ($deny = $this->assertAuthorized($request)) {
            return $deny;
        }

        $email = $request->input('email');
        $orderId = (string) $request->input('order_id', '');
        $amount = (float) $request->input('amount');

        if (! is_string($email) || $email === '' || $orderId === '' || $amount <= 0) {
            return response()->json(['status' => false, 'message' => 'Invalid parameters'], 422);
        }

        try {
            $didCredit = false;

            $response = DB::transaction(function () use ($email, $orderId, $amount, &$didCredit) {
                $deposit = Deposit::where('trx', $orderId)->lockForUpdate()->first();

                if ($deposit && (int) $deposit->status === Status::PAYMENT_SUCCESS) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Already processed',
                    ]);
                }

                $user = User::where('email', $email)->lockForUpdate()->first();
                if ($user === null) {
                    return response()->json([
                        'status' => false,
                        'message' => 'No user found, please check email and try again',
                    ], 404);
                }

                User::where('id', $user->id)->increment('balance', $amount);

                if ($deposit) {
                    $deposit->status = Status::PAYMENT_SUCCESS;
                    $deposit->amount = $amount;
                    $deposit->user_id = $user->id;
                    $deposit->save();
                } else {
                    $row = new Deposit();
                    $row->trx = $orderId;
                    $row->status = Status::PAYMENT_SUCCESS;
                    $row->user_id = $user->id;
                    $row->amount = $amount;
                    $row->method_code = 250;
                    $row->method_currency = 'NGN';
                    $row->charge = 0;
                    $row->rate = 0;
                    $row->final_amo = $amount;
                    $row->btc_amo = 0;
                    $row->btc_wallet = '';
                    $row->save();
                }

                $didCredit = true;

                $formatted = number_format($amount, 2);

                return response()->json([
                    'status' => true,
                    'message' => "NGN {$formatted} has been successfully added to your wallet",
                ]);
            });

            if ($didCredit && function_exists('send_notification')) {
                $formatted = number_format($amount, 2);
                $line = 'LOGS PLUG | wallet webhook | ' . $email . ' | +' . $formatted . ' NGN | order ' . $orderId;
                send_notification($line);
                if (function_exists('send_notification2')) {
                    send_notification2($line);
                }
            }

            return $response;
        } catch (\Throwable $e) {
            Log::error('wallet webhook fund failed', ['exception' => $e->getMessage()]);

            return response()->json(['status' => false, 'message' => 'Processing failed'], 500);
        }
    }

    public function check(Request $request): JsonResponse
    {
        if ($deny = $this->assertAuthorized($request)) {
            return $deny;
        }

        $email = $request->input('email');
        if (! is_string($email) || $email === '') {
            return response()->json(['status' => false, 'message' => 'Invalid email'], 422);
        }

        $user = User::where('email', $email)->first();
        if ($user === null) {
            return response()->json([
                'status' => false,
                'message' => 'No user found, please check email and try again',
            ]);
        }

        return response()->json([
            'status' => true,
            'user' => $user->username,
        ]);
    }
}
