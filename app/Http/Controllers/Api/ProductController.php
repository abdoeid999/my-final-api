<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController
{
    public function index(Request $request): JsonResponse
    {
        $query = Product::query();

        if ($request->filled('featured')) {
            $featured = filter_var($request->string('featured')->toString(), FILTER_VALIDATE_BOOLEAN);
            $query->where('featured', $featured);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->string('category')->toString());
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('minPrice')) {
            $query->where('price', '>=', (float) $request->string('minPrice')->toString());
        }

        if ($request->filled('maxPrice')) {
            $query->where('price', '<=', (float) $request->string('maxPrice')->toString());
        }

        $products = $query
            ->orderByDesc('featured')
            ->orderBy('id')
            ->get();

        return response()->json([
            'data' => $products,
        ]);
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json([
            'data' => $product,
        ]);
    }
}

