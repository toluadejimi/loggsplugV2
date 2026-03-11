<?php

namespace App\Http\Controllers\Api;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Bought;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ResellerApiController extends Controller
{
    /**
     * Serve product image by ID (public, no auth).
     * GET /api/reseller/product-image/{id}
     * Used so reseller sites can display images without 403 from hotlink protection on /assets/...
     */
    public function productImage(int $id): BinaryFileResponse|JsonResponse
    {
        $product = Product::active()->find($id);
        if (!$product || empty($product->image)) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $path = public_path('assets/images/product/' . $product->image);
        if (!is_file($path) || !is_readable($path)) {
            return response()->json(['message' => 'Image not found'], 404);
        }
        $mime = match (strtolower(pathinfo($path, PATHINFO_EXTENSION))) {
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            default => 'image/jpeg',
        };
        return response()->file($path, [
            'Content-Type' => $mime,
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    /**
     * List products with reseller price (base price minus admin discount %).
     * GET /api/reseller/products
     */
    public function products(Request $request): JsonResponse
    {
        $reseller = $request->attributes->get('reseller');

        $products = Product::active()
            ->whereHas('category', fn ($q) => $q->where('status', Status::ENABLE))
            ->with('category:id,name')
            ->orderBy('name')
            ->get()
            ->map(function ($product) use ($reseller) {
                $basePrice = (float) $product->price;
                $resellerPrice = $reseller->resellerPrice($basePrice);
                $inStock = $product->unsoldProductDetails()->count();
                $imageUrl = null;
                if (!empty($product->image)) {
                    $imageUrl = url('/api/reseller/product-image/' . $product->id);
                }
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'category' => $product->category->name ?? null,
                    'image_url' => $imageUrl,
                    'base_price' => $basePrice,
                    'reseller_price' => $resellerPrice,
                    'in_stock' => $inStock,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    /**
     * Place order as reseller. Charges reseller's wallet at reseller price; returns delivered accounts.
     * POST /api/reseller/order
     * Body: product_id, qty, customer_email (optional)
     */
    public function placeOrder(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer',
            'qty' => 'required|integer|min:1|max:100',
        ]);

        $reseller = $request->attributes->get('reseller');
        $user = $reseller->user;
        $productId = (int) $request->input('product_id');

        $product = Product::active()
            ->whereHas('category', fn ($q) => $q->where('status', Status::ENABLE))
            ->find($productId);

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found.'], 404);
        }

        $qty = (int) $request->input('qty');
        $unsoldDetails = $product->unsoldProductDetails;

        if ($unsoldDetails->count() < $qty) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock. Available: ' . $unsoldDetails->count(),
            ], 422);
        }

        $resellerCostEach = $reseller->resellerPrice((float) $product->price);
        $totalCharge = round($resellerCostEach * $qty, 2);
        $balance = (float) ($user->balance ?? 0);

        if ($balance < $totalCharge) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient reseller balance. Required: ' . $totalCharge . ', Balance: ' . $balance,
            ], 422);
        }

        User::where('id', $user->id)->decrement('balance', $totalCharge);

        $order = Order::create([
            'user_id' => $user->id,
            'reseller_id' => $reseller->id,
            'product_id' => $product->id,
            'total_amount' => $totalCharge,
            'status' => Status::ORDER_PAID,
        ]);

        $delivered = [];
        foreach ($unsoldDetails->take($qty) as $detail) {
            $detail->update(['is_sold' => Status::YES]);
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_detail_id' => $detail->id,
                'price' => $resellerCostEach,
            ]);
            $delivered[] = [
                'id' => $detail->id,
                'details' => $detail->details ?? '',
            ];
        }

        Order::where('id', $order->id)->update(['product_id' => $product->id]);

        Bought::create([
            'user_name' => $user->username,
            'qty' => $qty,
            'item' => $product->name,
            'amount' => $totalCharge,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order completed.',
            'order_id' => $order->id,
            'charged' => $totalCharge,
            'delivered' => $delivered,
        ]);
    }

    /**
     * Report an order to the main site (so admin can replace product).
     * POST /api/reseller/report-order
     * Body: order_id (main site order id), reason
     */
    public function reportOrder(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => 'required|integer',
            'reason' => 'nullable|string|max:2000',
        ]);

        $reseller = $request->attributes->get('reseller');
        $orderId = (int) $request->input('order_id');
        $reason = trim((string) $request->input('reason', ''));

        $order = Order::where('id', $orderId)
            ->where('reseller_id', $reseller->id)
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found or not owned by this reseller.'], 404);
        }

        $order->reported_at = now();
        $order->report_reason = $reason ?: null;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Order reported. Main site admin can now replace the product.',
        ]);
    }

    /**
     * Reseller balance and info.
     * GET /api/reseller/me
     */
    public function me(Request $request): JsonResponse
    {
        $reseller = $request->attributes->get('reseller');
        $user = $reseller->user;

        return response()->json([
            'success' => true,
            'data' => [
                'username' => $user->username,
                'email' => $user->email,
                'balance' => (float) ($user->balance ?? 0),
                'business_name' => $reseller->business_name,
                'admin_discount_percent' => (float) $reseller->admin_discount_percent,
            ],
        ]);
    }
}
