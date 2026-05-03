<?php

namespace App\Http\Controllers\Gateway;

use App\Models\Bought;
use App\Models\PaymentPoint;
use App\Models\Referre;
use App\Models\User;
use App\Models\Order;
use App\Models\Deposit;
use App\Models\Product;
use App\Constants\Status;
use App\Models\OrderItem;
use App\Lib\FormProcessor;
use App\Models\CouponCode;
use Illuminate\Http\Request;
use App\Models\ProductDetail;
use App\Models\GatewayCurrency;
use App\Models\AdminNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function depositInsert(Request $request)
    {


        if ($request->payment == 'wallet') {
            $qtyRaw = $request->input('qty', 1);
            if (is_array($qtyRaw)) {
                $qtyRaw = $qtyRaw[0] ?? 1;
            }
            $qty = max(1, min(10000, (int) $qtyRaw));
            $productId = (int) $request->input('id');

            try {
                return DB::transaction(function () use ($request, $qty, $productId) {
                    $userId = Auth::id();
                    $user = User::where('id', $userId)->lockForUpdate()->firstOrFail();

                    $product = Product::active()
                        ->whereHas('category', fn ($category) => $category->active())
                        ->where('id', $productId)
                        ->lockForUpdate()
                        ->firstOrFail();

                    $details = ProductDetail::query()
                        ->where('product_id', $product->id)
                        ->where('is_sold', Status::NO)
                        ->orderBy('id')
                        ->lockForUpdate()
                        ->limit($qty)
                        ->get();

                    if ($details->count() < $qty) {
                        return redirect('/products')->with('error', 'Product sold out or not enough quantity left.');
                    }

                    $lineTotal = (float) $product->price * $qty;
                    if ($lineTotal <= 0) {
                        return redirect('/products')->with('error', 'Invalid product pricing.');
                    }

                    $chargeAmount = $lineTotal;
                    if ($request->filled('coupon_code')) {
                        $ck = CouponCode::where('coupon_code', $request->coupon_code)->lockForUpdate()->first();
                        if (! $ck) {
                            return back()->with('error', 'Coupon does not exist');
                        }
                        if ((int) $ck->status === 2) {
                            return back()->with('error', 'Coupon is not valid');
                        }
                        $couponAmount = ((float) $ck->amount / 100) * $lineTotal;
                        $chargeAmount = $lineTotal - $couponAmount;
                        if ($chargeAmount <= 0) {
                            return back()->with('error', 'Invalid coupon for this order.');
                        }
                    }

                    if ((float) $user->balance < (float) $chargeAmount) {
                        return redirect('/products')->with('error', 'Insufficient funds. Fund your wallet first.');
                    }

                    User::where('id', $userId)->decrement('balance', $chargeAmount);

                    $order = Order::create([
                        'user_id' => $userId,
                        'total_amount' => $chargeAmount,
                        'product_id' => $product->id,
                        'status' => 1,
                    ]);

                    foreach ($details as $detail) {
                        $detail->update(['is_sold' => Status::YES]);
                        OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                            'product_detail_id' => $detail->id,
                            'price' => $product->price,
                        ]);
                    }

                    $name = $product->name;

                    $ref = Referre::where('refrere', $user->username)->where('status', 0)->lockForUpdate()->first();
                    if ($ref) {
                        $refAmount = $ref->amount;
                        $referer = User::where('username', $ref->referer)->lockForUpdate()->first();
                        if ($referer) {
                            User::where('id', $referer->id)->increment('ref_wallet', $refAmount);
                        }
                        $ref->update(['status' => 1]);
                    }

                    Bought::create([
                        'user_name' => $user->username,
                        'qty' => $qty,
                        'item' => $product->name,
                        'amount' => $lineTotal,
                    ]);

                    $message = 'LOGS PLUG |' . $user->email . "| just bought | $qty | Product: $name | ₦" . number_format($chargeAmount, 2) . "\n\nIP => " . $request->ip();
                    send_notification2($message);

                    return redirect('user/orders')->with('message', 'Order Purchased Successfully');
                });
            } catch (\Throwable $e) {
                Log::error('wallet purchase failed', ['exception' => $e->getMessage(), 'user_id' => Auth::id()]);

                return redirect('/products')->with('error', 'Purchase could not be completed. Please try again.');
            }
        }


        $get_payment = GatewayCurrency::where('method_code', $request->gateway)->first();
        if ($get_payment) {
            $payment = $get_payment->payment;
        } else {
            $payment = $request->payment;
        }




        if ($payment == "enkpay") {

            if ($request->amount < 1000) {
                $notify = "Amount can not be less than 1000";
                return back()->with('error', $notify);
            }


            if ($request->amount > 5000000) {
                $notify = "Amount can not be more than 100,000";
                return back()->with('error', $notify);
            }


            $data = new Deposit();
            $data->user_id = Auth::id();
            //$data->order_id = $order->id;
            $data->method_code = $request->gateway;
            $data->method_currency = "NGN";
            $data->amount = $request->amount;
            $data->charge = 0;
            $data->rate = 0;
            $data->final_amo = $request->amount;
            $data->btc_amo = 0;
            $data->btc_wallet = "";
            $data->trx = getTrx();
            $data->save();


            session()->put('Track', $data->trx);
            return to_route('user.deposit.confirm');

        }


        if ($payment == "point") {


            if ($request->amount < 1000) {
                $notify = "Amount can not be less than 1000";
                return back()->with('error', $notify);
            }


            if ($request->amount > 5000000) {
                $notify = "Amount can not be more than 100,000";
                return back()->with('error', $notify);
            }




            if (Auth::user()->name == null &&  Auth::user()->phone = null) {

                $request->validate([
                    'name' => 'required',
                    'phone' => 'required|max:11|min:11',
                ]);

                User::where('id', Auth::id())->update(['name' => $request->name, 'phone' => $request->phone]);
            }




            $email = Auth::user()->email;
            $get_account = PaymentPoint::where('email', $email)->first() ?? null;

            if ($get_account != null) {
                $data2['account_no'] = $get_account->account_no;
                $data2['bank_name'] = $get_account->bank_name;
                $data2['account_name'] = $get_account->account_name;

                $data2['amount'] = $request->amount + 100;





                $data = new Deposit();
                $data->user_id = Auth::id();
                $data->method_code = $request->gateway;
                $data->method_currency = "NGN";
                $data->amount = $request->amount;
                $data->charge = 0;
                $data->rate = 0;
                $data->final_amo = $request->amount;
                $data->btc_amo = 0;
                $data->btc_wallet = "";
                $data->trx = getTrx();
                $data->trx_no = $get_account->account_no;
                $data->save();

                return view('templates.basic.user.point', $data2);

            }

            $key = env('PALMPAYKEY');
            $key_sec = env('PALSEC');
            $databody = array(
                "email" => $email,
                "account_name" => $request->name ?? Auth::user()->name,
                "key" => $key,
//                "bankCode" => [20946],
//                "businessId" => "9b9897b2f0cb974b9bcc740232d738eba3ccfcfb"
            );




            $post_data = json_encode($databody);
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://web.sprintpay.online/api/generate-virtual-account',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 20,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $post_data,
                CURLOPT_HTTPHEADER => array(
                    "api-key: $key",
                    'Content-Type: application/json',
                    "Authorization: Bearer $key_sec"
                ),
            ));

            $var = curl_exec($curl);
            curl_close($curl);
            $var = json_decode($var);
            $status = $var->status ?? null;
            $error = $var->message ?? null;

            {

}

            if ($status != false) {




            $pay = new PaymentPoint();
            $pay->account_no = $var->data->account_number;
            $pay->account_name = $var->data->account_name;
            $pay->bank_name = $var->data->bank_name;
            $pay->email = $email;
            $pay->save();


                $data = new Deposit();
                $data->user_id = Auth::id();
                $data->method_code = $request->gateway;
                $data->method_currency = "NGN";
                $data->amount = $request->amount;
                $data->charge = 0;
                $data->rate = 0;
                $data->final_amo = $request->amount;
                $data->btc_amo = 0;
                $data->btc_wallet = "";
                $data->trx = getTrx();
                $data->trx_no = $var->data->account_number;
                $data->save();


            $data2['account_no'] = $var->data->account_number;
            $data2['bank_name'] =  $var->data->bank_name;
            $data2['account_name'] =$var->data->account_name;

                $data2['amount'] = $request->amount + 100;

                return view('templates.basic.user.point', $data2);

        }

            return back()->with('error', "$error");

//
//            $data['account_no'] =  "NotAvailabe";
//            $data['bank_name'] = "NotAvailabe";
//            $data['account_name'] = "Notavailabe";
//            $data['amount'] = 0;
//
//            return view('templates.basic.user.point', $data);



    }


}

public
function depositConfirm(request $request)
{


    $track = session()->get('Track');
    $deposit = Deposit::where('trx', $track)->where('status', Status::PAYMENT_INITIATE)->orderBy('id', 'DESC')->with('gateway')->firstOrFail();

    if ($deposit->method_code >= 1000) {
        return to_route('user.deposit.manual.confirm');
    }


    // if($deposit->method_code == 250){

    // }

    $dirName = $deposit->gateway->alias;
    $new = __NAMESPACE__ . '\\' . $dirName . '\\ProcessController';

    $data = $new::process($deposit);
    $data = json_decode($data);


    if (isset($data->error)) {
        $notify[] = ['error', $data->message];
        return to_route(gatewayRedirectUrl())->withNotify($notify);
    }
    if (isset($data->redirect)) {
        return redirect($data->redirect_url);
    }

    // for Stripe V3
    if (@$data->session) {
        $deposit->btc_wallet = $data->session->id;
        $deposit->save();
    }

    $pageTitle = 'Payment Confirm';
    return view($this->activeTemplate . $data->view, compact('data', 'pageTitle', 'deposit'));
}


public
static function userDataUpdate($deposit, $isManual = null)
{

    if ($deposit->status == Status::PAYMENT_INITIATE || $deposit->status == Status::PAYMENT_PENDING) {
        $deposit->status = Status::PAYMENT_SUCCESS;
        $deposit->save();

        $user = User::find($deposit->user_id);
        $email = User::where('id', $deposit->user_id)->first()->email;
        User::where('id', $deposit->user_id)->increment('balance', $deposit->amount);

        $message = "LOGS PLUG |" . $email . "|" . number_format($deposit->amount, 2) . "| has been manually funded by Admin";
        send_notification2($message);
        send_notification($message);


        if (!$isManual) {
            $adminNotification = new AdminNotification();
            $adminNotification->user_id = $user->id;
            $adminNotification->title = 'Payment successful via ' . $deposit->gatewayCurrency()->name;
            $adminNotification->click_url = urlPath('admin.deposit.successful');
            $adminNotification->save();
        }

        notify($user, $isManual ? 'DEPOSIT_APPROVE' : 'DEPOSIT_COMPLETE', [
            'method_name' => $deposit->gatewayCurrency()->name,
            'method_currency' => $deposit->method_currency,
            'method_amount' => showAmount($deposit->final_amo),
            'amount' => showAmount($deposit->amount),
            'charge' => showAmount($deposit->charge),
            'rate' => showAmount($deposit->rate),
            'trx' => $deposit->trx,
        ]);


    }
}

public
function manualDepositConfirm()
{
    $track = session()->get('Track');
    $data = Deposit::with('gateway')->where('status', Status::PAYMENT_INITIATE)->where('trx', $track)->first();
    if (!$data) {
        return to_route(gatewayRedirectUrl());
    }
    if ($data->method_code > 999) {

        $pageTitle = 'Payment Confirm';
        $method = $data->gatewayCurrency();
        $gateway = $method->method;
        return view($this->activeTemplate . 'user.payment.manual', compact('data', 'pageTitle', 'method', 'gateway'));
    }
    abort(404);
}

public
function manualDepositUpdate(Request $request)
{
    $track = session()->get('Track');

    $data = Deposit::with('gateway')->where('status', Status::PAYMENT_INITIATE)->where('trx', $track)->first();
    if (!$data) {
        return to_route(gatewayRedirectUrl());
    }
    $gatewayCurrency = $data->gatewayCurrency();
    $gateway = $gatewayCurrency->method;
    $formData = $gateway->form->form_data;


    if ($request->receipt == null) {
        return back()->with('error', "Payment receipt is required");
    }

    $file = $request->file('receipt');
    $receipt_fileName = date("ymis") . $file->getClientOriginalName();
    $directory = date("Y") . "/" . date("m") . "/" . date("d");
    $path = getFilePath('verify') . '/' . $directory;
    $request->receipt->move($path, $receipt_fileName);
    $url = url('') . "/" . $path . "/" . $receipt_fileName;


    Deposit::where('trx', $track)->update([
        'status' => Status::PAYMENT_PENDING,
        'url' => $url,
    ]);


    $email = User::where('id', $data->user->id)->first()->email;

    $adminNotification = new AdminNotification();
    $adminNotification->user_id = $data->user->id;
    $adminNotification->title = 'Payment request from ' . $data->user->username;
    $adminNotification->click_url = $url;
    $adminNotification->save();

    notify($data->user, 'DEPOSIT_REQUEST', [
        'method_name' => $data->gatewayCurrency()->name,
        'method_currency' => $data->method_currency,
        'method_amount' => showAmount($data->final_amo),
        'amount' => showAmount($data->amount),
        'charge' => showAmount($data->charge),
        'rate' => showAmount($data->rate),
        'trx' => $data->trx
    ]);


//        $message = "LOGS PLUG |".  $email . "| wants to fund ". number_format($data->amount, 2).  "| check admin to confirm";
//        send_notification2($message);
//        send_notification($message);

    $notify = "You have payment request is successful, you will be credited soon";
    return redirect('/user/deposit/new')->with('message', $notify);

}


}
