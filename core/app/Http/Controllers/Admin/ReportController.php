<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationLog;
use App\Models\Order;
use App\Models\User;
use App\Models\UserLogin;
use Illuminate\Http\Request;
use App\Constants\Status;
use App\Models\Product;
use App\Models\ProductDetail;

class ReportController extends Controller
{
    public function loginHistory(Request $request)
    {
        $pageTitle = 'User Login History';
        $loginLogs = UserLogin::orderBy('id','desc')->searchable(['user:username'])->with('user')->paginate(getPaginate());
        return view('admin.reports.logins', compact('pageTitle', 'loginLogs'));
    }

    public function loginIpHistory($ip)
    {
        $pageTitle = 'Login by - ' . $ip;
        $loginLogs = UserLogin::where('user_ip',$ip)->orderBy('id','desc')->with('user')->paginate(getPaginate());
        return view('admin.reports.logins', compact('pageTitle', 'loginLogs','ip'));
    }

    public function notificationHistory(Request $request){
        $pageTitle = 'Notification History';
        $logs = NotificationLog::orderBy('id','desc')->searchable(['user:username'])->with('user')->paginate(getPaginate());
        return view('admin.reports.notification_history', compact('pageTitle','logs'));
    }

    public function emailDetails($id){
        $pageTitle = 'Email Details';
        $email = NotificationLog::findOrFail($id);
        return view('admin.reports.email_details', compact('pageTitle','email'));
    }

    public function orderHistory(Request $request)
    {
        $pageTitle = 'Order History';

        $query = Order::query()
            ->with(['user:id,username', 'deposit:id,order_id,trx', 'orderItems'])
            ->orderByDesc('id');

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('username', 'like', "%{$search}%");
                })
                    ->orWhereHas('deposit', function ($depositQuery) use ($search) {
                        $depositQuery->where('trx', 'like', "%{$search}%");
                    });
            });
        }

        $orders = $query->paginate(10);

        return view('admin.reports.order_history', compact('pageTitle', 'orders'));
    }


    public function orderHistoryFind(Request $request){

        $pageTitle = 'Order History';

        $get_user = User::where('email', $request->email)->first() ?? null;

        if(!$get_user){
            $notify[] = ['error', 'User not found'];
            return back()->withNotify($notify);
        }

        $orders = Order::latest()->where('user_id', $get_user->id)
            ->orderBy('id','desc')
            ->with('user', 'deposit', 'orderItems')
            ->paginate(50);

        return view('admin.reports.order_history', compact('pageTitle', 'orders'));
    }

    public function orderDetails($id){
        $order = Order::findOrFail($id);
        $pageTitle = "Order Details ";

        $items = @$order->orderItems->pluck('product_detail_id')->toArray() ?? [];
        $accounts = ProductDetail::whereIn('id', $items)->searchable(['details'])->orderBy('id', 'DESC')->paginate(getPaginate());

        return view('admin.product.account',compact('pageTitle', 'accounts', 'order'));
    }

}


