<?php

namespace App\Http\Controllers\Api;

use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController
{
    public function __construct(protected ProductService $service) {}

    public function index(Request $request): JsonResponse
    {
        $filters = [
            'featured' => $request->boolean('featured'),
            'category' => $request->string('category')->toString(),
            'search' => $request->string('search')->toString(),
        ];

        $products = $this->service->getProducts($filters);

        return response()->json([
            'status' => 'success',
            'data' => $products,
        ]);
    }

    public function show($id): JsonResponse
    {
        $product = $this->service->getProduct($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $product,
        ]);
    }

    public function categories(): JsonResponse
    {
        $categories = $this->service->getCategories();

        return response()->json([
            'status' => 'success',
            'data' => $categories,
        ]);
    }
}


