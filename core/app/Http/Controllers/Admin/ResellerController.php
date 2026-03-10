<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Reseller;
use App\Models\User;
use Illuminate\Http\Request;

class ResellerController extends Controller
{
    public function index()
    {
        $pageTitle = 'Resellers';
        $resellers = Reseller::with('user:id,username,email,balance')
            ->orderBy('id', 'desc')
            ->paginate(getPaginate());
        $emptyMessage = 'No resellers yet.';
        return view('admin.resellers.index', compact('pageTitle', 'resellers', 'emptyMessage'));
    }

    public function create()
    {
        $pageTitle = 'Add Reseller';
        $users = User::active()->whereDoesntHave('reseller')->orderBy('username')->get(['id', 'username', 'email']);
        return view('admin.resellers.create', compact('pageTitle', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'admin_discount_percent' => 'nullable|numeric|min:0|max:99.99',
            'business_name' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email',
        ]);

        $user = User::findOrFail($request->user_id);
        if ($user->reseller) {
            $notify[] = ['error', 'This user is already a reseller.'];
            return back()->withNotify($notify);
        }

        Reseller::create([
            'user_id' => $user->id,
            'api_key' => Reseller::generateApiKey(),
            'admin_discount_percent' => $request->admin_discount_percent ?? 0,
            'status' => Status::ENABLE,
            'business_name' => $request->business_name,
            'contact_email' => $request->contact_email ?? $user->email,
        ]);

        $notify[] = ['success', 'Reseller created. API key is shown on the reseller list.'];
        return to_route('admin.resellers.index')->withNotify($notify);
    }

    public function edit(int $id)
    {
        $reseller = Reseller::with('user')->findOrFail($id);
        $pageTitle = 'Edit Reseller: ' . ($reseller->user->username ?? $reseller->id);
        return view('admin.resellers.edit', compact('pageTitle', 'reseller'));
    }

    public function update(Request $request, int $id)
    {
        $reseller = Reseller::findOrFail($id);

        $request->validate([
            'admin_discount_percent' => 'nullable|numeric|min:0|max:99.99',
            'status' => 'required|in:0,1',
            'business_name' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email',
        ]);

        $reseller->update([
            'admin_discount_percent' => $request->admin_discount_percent ?? 0,
            'status' => (int) $request->status,
            'business_name' => $request->business_name,
            'contact_email' => $request->contact_email,
        ]);

        $notify[] = ['success', 'Reseller updated.'];
        return back()->withNotify($notify);
    }

    public function revokeKey(int $id)
    {
        $reseller = Reseller::findOrFail($id);
        $reseller->revokeApiKey();
        $notify[] = ['success', 'API key revoked. Reseller cannot use the API until you regenerate a key.'];
        return back()->withNotify($notify);
    }

    public function regenerateKey(int $id)
    {
        $reseller = Reseller::findOrFail($id);
        $newKey = $reseller->regenerateApiKey();
        $notify[] = ['success', 'New API key generated. Show the reseller this key once; it will not be shown again: ' . $newKey];
        return back()->withNotify($notify)->with('new_api_key', $newKey);
    }

    public function orders(int $id)
    {
        $reseller = Reseller::with('user')->findOrFail($id);
        $pageTitle = 'Reseller Orders: ' . ($reseller->user->username ?? $reseller->id);
        $orders = $reseller->orders()->with('user:id,username', 'orderItems.product')->latest()->paginate(getPaginate());
        $emptyMessage = 'No orders yet.';
        return view('admin.resellers.orders', compact('pageTitle', 'reseller', 'orders', 'emptyMessage'));
    }
}
