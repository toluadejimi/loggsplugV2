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

class PaymentController extends Controller
{
    public function depositInsert(Request $request)
    {


        if ($request->payment == "wallet") {

            $last_order = Order::latest()->where('user_id', Auth::id())->first()->created_at ?? null;

            if ($last_order != null) {

                $createdAt = strtotime($last_order);
                $currentTime = time();
                $timeDifference = $currentTime - $createdAt;

                if ($timeDifference < 50) {

                    $notify = "Please wait for 10sec and try again";
                    return redirect('user/orders')->with('error', $notify);


                }

            }

            $qty = $request->qty;

            $product = Product::active()->whereHas('category', function ($category) {
                return $category->active();
            })->findOrFail($request->id);

            $unsoldProductDetails = $product->unsoldProductDetails;

            if ($unsoldProductDetails->count() < $qty) {
                return redirect('/products')->with('error', "Product sold out or not enough quantity left.");
            }

//            $alreadyBought = OrderItem::where('product_id', $product->id)
//                ->whereHas('order', function ($q) {
//                    $q->where('user_id', Auth::id());
//                })->exists();
//
//            if ($alreadyBought) {
//                return redirect('/products')->with('error', 'You have already purchased this product.');
//            }

            $amount = $product->price * $qty;
            $balance = Auth::user()->balance ?? 0;


            if ($balance < $amount) {
                return redirect('/products')->with('error', 'Insufficient funds. Fund your wallet first.');
            }


            $final_amo = $amount;


            if ($final_amo == 0 || $final_amo < $product->price) {

                $message = "This user" . Auth::user()->email . " is a big thief";
                send_notification2($message);
                return redirect('/products')->with('error', 'stop playing games and fund your wallet.');

            }


            if ($request->coupon_code != null) {
                $ck = CouponCode::where('coupon_code', $request->coupon_code)->first();
                if (!$ck) return back()->with('error', 'Coupon does not exist');
                if ($ck->status == 2) return back()->with('error', 'Coupon is not valid');

                $coupon_amount = ($ck->amount / 100) * $final_amo;
                $charge_amount = $final_amo - $coupon_amount;
            } else {
                $charge_amount = $final_amo;
            }

            User::where('id', Auth::id())->decrement('balance', $charge_amount);


            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $charge_amount,
                'product_id' => $product->id,
                'status' => 1,
            ]);


            foreach ($unsoldProductDetails->take($qty) as $detail) {
                $detail->update(['is_sold' => 1]);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_detail_id' => $detail->id,
                    'price' => $product->price,
                ]);
            }

            Order::where('id', $order->id)->update(['product_id' => $product->id]);

            $name = Product::where('id', $product->id)->first()->name ?? null;


            $ref = Referre::where('refrere', Auth::user()->username)->where('status', 0)->first();
            if ($ref) {
                $ref_amount = $ref->amount;
                User::where('username', $ref->referer)->increment('ref_wallet', $ref_amount);
                $ref->update(['status' => 1]);
            }

            Bought::create([
                'user_name' => Auth::user()->username,
                'qty' => $qty,
                'item' => $product->name,
                'amount' => $amount,
            ]);

            $message = "LOGS PLUG |" . Auth::user()->email . "| just bought | $qty | Product: $name | ₦" . number_format($charge_amount, 2) . "\n\nIP => " . $request->ip();
            send_notification2($message);

            return redirect('user/orders')->with('message', 'Order Purchased Successfully');


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
