<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Wallet funding webhook (e-fund, Enkpay, etc.)
    |--------------------------------------------------------------------------
    |
    | Option A — shared secret:
    |   WALLET_FUND_WEBHOOK_SECRET + header X-Wallet-Webhook-Secret or JSON field webhook_secret
    |
    | Option B — Enkpay-style (JSON body + Authorization), e.g.:
    |   Authorization: Bearer sk_live_...
    |   { "amount", "email", "order_id", "key": "wk_live_...", ... }
    |   Set WALLET_FUND_WEBHOOK_AUTHORIZATION to the exact header value (including "Bearer ").
    |   Set WALLET_FUND_WEBHOOK_WEBKEY to the same value as JSON "key" (defaults to WEBKEY).
    |
    */
    'fund_webhook_secret' => env('WALLET_FUND_WEBHOOK_SECRET', ''),

    'fund_webhook_authorization' => env('WALLET_FUND_WEBHOOK_AUTHORIZATION', ''),

    'fund_webhook_webkey' => env('WALLET_FUND_WEBHOOK_WEBKEY', env('WEBKEY', '')),
];
