<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function store(CheckoutRequest $request): JsonResponse
    {
        $cart = $this->getCartOrFail($request);
        $cart->load(['items.product']);

        if ($cart->items->isEmpty()) {
            return response()->json([
                'message' => 'Cart is empty.',
            ], 422);
        }

        // Validate stock one last time (simulated).
        foreach ($cart->items as $item) {
            if (! $item->product) {
                return response()->json(['message' => 'Cart contains invalid product.'], 422);
            }

            if ($item->quantity > $item->product->stock) {
                return response()->json([
                    'message' => 'Not enough stock for one of the cart items.',
                ], 422);
            }
        }

        $total = $cart->items->sum(fn($i) => (float) $i->product->price * $i->quantity);

        $orderNumber = 'ORD-' . Str::upper(Str::random(12));

        $order = DB::transaction(function () use ($request, $cart, $orderNumber, $total) {
            $order = Order::create([
                'cart_id' => $cart->id,
                'order_number' => $orderNumber,
                'customer_name' => $request->string('name')->toString(),
                'customer_email' => $request->string('email')->toString(),
                'customer_address' => $request->string('address')->toString(),
                'status' => 'paid',
                'total' => round((float) $total, 2),
            ]);

            foreach ($cart->items as $item) {
                /** @var Product $product */
                $product = $item->product;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_price' => (float) $product->price,
                    'quantity' => $item->quantity,
                    'line_total' => round((float) $product->price * $item->quantity, 2),
                ]);
            }

            // Clear cart after successful checkout.
            $cart->items()->delete();

            return $order;
        });

        return response()->json([
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'message' => 'Order placed successfully!',
            'total' => (float) $order->total,
        ]);
    }

    private function getCartOrFail(Request $request): Cart
    {
        $token = (string) $request->header('X-Cart-Token', '');
        if ($token === '') {
            abort(400, 'Missing X-Cart-Token header.');
        }

        $cart = Cart::where('token', $token)->first();
        if (! $cart) {
            // Keep message consistent and avoid 404s from leaking.
            abort(422, 'Cart not found. Add items to your cart first.');
        }

        return $cart;
    }
}

