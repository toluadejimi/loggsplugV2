<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    /**
     * Check if a user exists by email (e.g. before funding).
     * POST/GET /api/e-check
     */
    public function e_check(Request $request)
    {
        Log::channel('single')->info('Webhook incoming: e-check', [
            'payload' => $request->all(),
            'ip'      => $request->ip(),
            'method'  => $request->method(),
        ]);

        $get_user = User::where('email', $request->email)->first();

        if ($get_user === null) {
            Log::channel('single')->info('Webhook e-check result: user not found', ['email' => $request->email]);
            return response()->json([
                'status'  => false,
                'message' => 'No user found, please check email and try again',
            ]);
        }

        Log::channel('single')->info('Webhook e-check result: success', ['user_id' => $get_user->id, 'username' => $get_user->username]);
        return response()->json([
            'status' => true,
            'user'  => $get_user->username,
        ]);
    }

    /**
     * Webhook: credit user balance and record deposit.
     * POST/GET /api/e-fund
     * Body/query: email, amount, order_id
     */
    public function e_fund(Request $request)
    {
        Log::channel('single')->info('Webhook incoming: e-fund', [
            'payload' => $request->all(),
            'ip'      => $request->ip(),
            'method'  => $request->method(),
        ]);

        $get_user = User::where('email', $request->email)->first();

        if ($get_user === null) {
            Log::channel('single')->info('Webhook e-fund result: user not found', ['email' => $request->email]);
            return response()->json([
                'status'  => false,
                'message' => 'No user found, please check email and try again',
            ]);
        }

        $amount = (float) $request->amount;
        if ($amount <= 0) {
            Log::channel('single')->info('Webhook e-fund result: invalid amount', ['amount' => $request->amount]);
            return response()->json([
                'status'  => false,
                'message' => 'Invalid amount',
            ]);
        }

        User::where('email', $request->email)->increment('balance', $amount);

        $get_depo = Deposit::where('trx', $request->order_id)->first();
        if ($get_depo === null) {
            $trx = new Deposit();
            $trx->trx = $request->order_id;
            $trx->status = 1;
            $trx->user_id = $get_user->id;
            $trx->amount = $amount;
            $trx->method_code = 250;
            $trx->save();
        } else {
            Deposit::where('trx', $request->order_id)->update(['status' => 1]);
        }

        $amountFormatted = number_format($amount, 2);
        Log::channel('single')->info('Webhook e-fund result: success', [
            'user_id' => $get_user->id,
            'order_id' => $request->order_id,
            'amount' => $amount,
        ]);

        return response()->json([
            'status'  => true,
            'message' => "NGN $amountFormatted has been successfully added to your wallet",
        ]);
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
