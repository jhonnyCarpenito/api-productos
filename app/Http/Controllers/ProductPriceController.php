<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductPriceController extends Controller
{
    public function index(Product $product): JsonResponse
    {
        return response()->json($product->prices);
    }

    public function store(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'currency_id' => ['required', 'exists:currencies,id'],
            'price' => ['required', 'numeric'],
        ]);

        $productPrice = $product->prices()->create($validated);

        return response()->json($productPrice, 201);
    }
}
