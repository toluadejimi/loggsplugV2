<?php

namespace App\Http\Controllers\Api;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Bought;
use App\Models\Category;
use App\Models\Deposit;
use App\Models\Frontend;
use App\Models\GatewayCurrency;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SupportTicket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppApiController extends Controller
{
    /**
     * Public: categories list (for nav/filter).
     */
    public function categories(): JsonResponse
    {
        $categories = Category::where('status', 1)->orderBy('name')->get(['id', 'name']);
        return response()->json(['data' => $categories]);
    }

    /**
     * Public: products by category with pagination and search.
     */
    public function products(Request $request): JsonResponse
    {
        $request->validate(['search' => 'nullable|string|max:100']);
        $search = $request->filled('search') ? trim($request->search) : null;

        $query = Category::where('status', 1)->with([
            'products' => function ($q) use ($search) {
                $q->active()->orderBy('id', 'DESC');
                if ($search) {
                    $q->where('name', 'LIKE', '%' . $search . '%');
                }
                return $q->with('productDetails')->take(5);
            },
        ]);

        if ($search) {
            $query->whereHas('products', fn($q) => $q->active()->where('name', 'LIKE', '%' . $search . '%'));
        }

        $categories = $query->orderBy('name')->paginate(10);
        $categoriesDrop = Category::where('status', 1)->orderBy('name')->get(['id', 'name']);

        // Product sliders
        Frontend::ensureProductsSliderDefaults();
        $sliders = Frontend::where('data_keys', 'products_slider.element')->orderBy('id')->get();

        $bought = Bought::latest()->take(20)->get(['user_name', 'item', 'amount', 'created_at']);

        return response()->json([
            'categories' => $categories,
            'categories_drop' => $categoriesDrop,
            'sliders' => $sliders,
            'bought' => $bought,
            'search' => $search,
        ]);
    }

    /**
     * Public: single product details + related.
     */
    public function productDetails(int $id): JsonResponse
    {
        $product = Product::active()
            ->whereHas('category', fn($q) => $q->where('status', 1))
            ->with('category', 'productDetails')
            ->findOrFail($id);

        $related = Product::active()
            ->whereHas('category', fn($q) => $q->where('status', 1))
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'product' => $product,
            'related_products' => $related,
        ]);
    }

    /**
     * Auth: category products (paginated).
     */
    public function categoryProducts(Request $request, int $id): JsonResponse
    {
        $category = Category::active()->findOrFail($id);
        $search = $request->filled('search') ? trim($request->search) : null;

        $query = Product::active()
            ->where('category_id', $category->id)
            ->with('productDetails')
            ->orderBy('id', 'DESC');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('description', 'LIKE', '%' . $search . '%');
            });
        }

        $products = $query->paginate(getPaginate(20));

        return response()->json([
            'category' => $category,
            'products' => $products,
        ]);
    }

    /**
     * Auth: current user.
     */
    public function user(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        $data = $user->toArray();
        $data['wallet'] = ['balance' => (float) ($user->balance ?? 0)];
        return response()->json(['user' => $data]);
    }

    /**
     * Auth: dashboard stats and recent deposits.
     */
    public function dashboard(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $widget = [
            'total_payments' => Deposit::where('user_id', $user->id)->successful()->sum('amount'),
            'total_orders' => Order::where('user_id', $user->id)->paid()->count(),
            'total_tickets' => SupportTicket::where('user_id', $user->id)->count(),
        ];

        $latestDeposits = Deposit::where('user_id', $user->id)
            ->with('gateway', 'order')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'widget' => $widget,
            'latest_deposits' => $latestDeposits,
        ]);
    }

    /**
     * Auth: orders list.
     */
    public function orders(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $orders = Order::where('user_id', $user->id)
            ->where('status', Status::ORDER_PAID)
            ->with('deposit', 'orderItems')
            ->orderBy('id', 'desc')
            ->paginate(getPaginate(15));

        $countOrder = Order::where('user_id', $user->id)->where('status', Status::ORDER_PAID)->count();
        $orderSum = Order::where('user_id', $user->id)->where('status', Status::ORDER_PAID)->sum('total_amount');

        return response()->json([
            'orders' => $orders,
            'count_order' => $countOrder,
            'order_sum' => $orderSum,
        ]);
    }

    /**
     * Auth: single order details.
     */
    public function orderDetails(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $order = Order::where('user_id', $user->id)->where('status', Status::ORDER_PAID)->findOrFail($id);
        $order->load('deposit', 'orderItems.product', 'orderItems.productDetail');

        return response()->json(['order' => $order]);
    }

    /**
     * Public: gateway currencies (for deposit).
     */
    public function gatewayCurrencies(): JsonResponse
    {
        $gateways = GatewayCurrency::all();
        return response()->json(['data' => $gateways]);
    }
}
