<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Reseller;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResellerController extends Controller
{
    public function index()
    {
        $pageTitle = 'Reseller';
        $user = auth()->user();
        $reseller = $user->reseller;

        if ($reseller && $reseller->isActive()) {
            return view($this->activeTemplate . 'user.reseller.dashboard', compact('pageTitle', 'user', 'reseller'));
        }

        return view($this->activeTemplate . 'user.reseller.become', compact('pageTitle', 'user'));
    }

    /**
     * Generate or regenerate API key. Creates reseller if user has none; otherwise regenerates key.
     */
    public function generateKey(Request $request)
    {
        $user = auth()->user();
        $reseller = $user->reseller;

        if ($reseller) {
            $newKey = $reseller->regenerateApiKey();
            $notify[] = ['success', 'A new API key has been generated. Your previous key no longer works. Copy and save it below.'];
            return back()->withNotify($notify);
        }

        Reseller::create([
            'user_id' => $user->id,
            'api_key' => Reseller::generateApiKey(),
            'admin_discount_percent' => 0,
            'status' => Status::ENABLE,
            'business_name' => $user->username,
            'contact_email' => $user->email,
        ]);

        $notify[] = ['success', 'Your reseller account and API key have been created. You can now use the API and download your reseller website.'];
        return to_route('user.reseller.index')->withNotify($notify);
    }

    /**
     * API documentation page for resellers.
     */
    public function apiDocs()
    {
        $pageTitle = 'Reseller API Documentation';
        $user = auth()->user();
        $reseller = $user->reseller;
        $baseUrl = rtrim(url('/api'), '/');
        return view($this->activeTemplate . 'user.reseller.api-docs', compact('pageTitle', 'user', 'reseller', 'baseUrl'));
    }

    public function proInstallSubmit(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'contact_name'  => 'required|string|max:255',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string|max:50',
            'message'      => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();
        $reseller = $user->reseller;

        $text = "🛠 <b>Pro Installation Request (20,000)</b>\n\n";
        $text .= "User: " . ($user->fullname ?? $user->username) . " (ID: {$user->id})\n";
        $text .= "Email: {$user->email}\n\n";
        $text .= "Business: " . $request->input('business_name') . "\n";
        $text .= "Contact: " . $request->input('contact_name') . "\n";
        $text .= "Email: " . $request->input('contact_email') . "\n";
        $text .= "Phone: " . ($request->input('contact_phone') ?: '—') . "\n\n";
        if ($request->filled('message')) {
            $text .= "Message: " . $request->input('message') . "\n";
        }

        TelegramService::sendMessage($text);

        $notify[] = ['success', 'Your Pro installation request has been sent. We will contact you for payment (20,000) and setup.'];
        return back()->withNotify($notify);
    }

    /**
     * Web installation (20k) request — same as pro install; sends to Telegram.
     */
    public function becomeResellerSubmit(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'contact_name'  => 'required|string|max:255',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string|max:50',
            'message'       => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();

        $text = "🛠 <b>Web Installation Request (20,000)</b>\n\n";
        $text .= "User: " . ($user->fullname ?? $user->username) . " (ID: {$user->id})\n";
        $text .= "Email: {$user->email}\n\n";
        $text .= "Business: " . $request->input('business_name') . "\n";
        $text .= "Contact: " . $request->input('contact_name') . "\n";
        $text .= "Email: " . $request->input('contact_email') . "\n";
        $text .= "Phone: " . ($request->input('contact_phone') ?: '—') . "\n\n";
        if ($request->filled('message')) {
            $text .= "Message: " . $request->input('message') . "\n";
        }

        TelegramService::sendMessage($text);

        $notify[] = ['success', 'Your Web installation request has been sent. We will contact you for payment (20,000) and setup on your server.'];
        return back()->withNotify($notify);
    }

}
