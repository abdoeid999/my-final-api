<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $cart = $this->getOrCreateCart($request);
        $cart->load(['items.product']);

        $items = $cart->items
            ->map(function (CartItem $item) {
                return [
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'product' => [
                        'id' => $item->product->id,
                        'name' => $item->product->name,
                        'slug' => $item->product->slug,
                        'price' => (float) $item->product->price,
                        'image_url' => $item->product->image_url,
                        'category' => $item->product->category,
                        'stock' => $item->product->stock,
                    ],
                    'line_total' => round((float) $item->product->price * $item->quantity, 2),
                ];
            })
            ->values();

        $subtotal = $items->sum(fn($i) => (float) $i['line_total']);

        return response()->json([
            'items' => $items,
            'subtotal' => round((float) $subtotal, 2),
            'currency' => 'USD',
        ]);
    }

    public function store(StoreCartItemRequest $request): JsonResponse
    {
        $cart = $this->getOrCreateCart($request);

        /** @var Product $product */
        $product = Product::findOrFail($request->integer('product_id'));

        $quantityToAdd = $request->integer('quantity');
        $current = $cart->items()->where('product_id', $product->id)->first();
        $newQuantity = ($current?->quantity ?? 0) + $quantityToAdd;

        if ($newQuantity > $product->stock) {
            return response()->json([
                'message' => 'Not enough stock for this quantity.',
            ], 422);
        }

        if ($current) {
            $current->quantity = $newQuantity;
            $current->save();
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $newQuantity,
            ]);
        }

        return $this->index($request);
    }

    public function update(Product $product, UpdateCartItemRequest $request): JsonResponse
    {
        $cart = $this->getOrCreateCart($request);

        if ($request->integer('quantity') > $product->stock) {
            return response()->json([
                'message' => 'Not enough stock for this quantity.',
            ], 422);
        }

        $item = $cart->items()->where('product_id', $product->id)->first();
        if (! $item) {
            $item = $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $request->integer('quantity'),
            ]);
        } else {
            $item->quantity = $request->integer('quantity');
            $item->save();
        }

        return $this->index($request);
    }

    public function destroy(Product $product, Request $request): JsonResponse
    {
        $cart = $this->getOrCreateCart($request);

        $cart->items()->where('product_id', $product->id)->delete();

        return $this->index($request);
    }

    private function getOrCreateCart(Request $request): Cart
    {
        $token = (string) $request->header('X-Cart-Token', '');
        if ($token === '') {
            abort(400, 'Missing X-Cart-Token header.');
        }

        $cart = Cart::firstOrCreate(
            ['token' => $token],
            []
        );

        return $cart;
    }
}

