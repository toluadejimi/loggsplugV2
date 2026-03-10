<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Deposit;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * Check if a user exists by email (e.g. before funding).
     * POST/GET /api/e-check
     */
    public function e_check(Request $request)
    {
        $get_user = User::where('email', $request->email)->first();

        if ($get_user === null) {
            return response()->json([
                'status'  => false,
                'message' => 'No user found, please check email and try again',
            ]);
        }

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
        $get_user = User::where('email', $request->email)->first();

        if ($get_user === null) {
            return response()->json([
                'status'  => false,
                'message' => 'No user found, please check email and try again',
            ]);
        }

        $amount = (float) $request->amount;
        if ($amount <= 0) {
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
