<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductPriceController extends Controller
{
    public function index(Product $product): JsonResponse
    {
        return response()->json($product->prices);
    }

    public function store(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            /**
             * ID de la divisa (1: USD, 2: EUR, 3: VES, 4: COP, 5: GBP).
             * @example 2
             */
            'currency_id' => [
                'required',
                'exists:currencies,id',
                Rule::unique('product_prices', 'currency_id')->where('product_id', $product->id),
            ],
            'price' => ['required', 'numeric', 'min:0'],
        ], [
            'currency_id.unique' => 'Este producto ya tiene un precio registrado en la divisa seleccionada.',
        ]);

        $productPrice = $product->prices()->create($validated);

        return response()->json($productPrice, 201);
    }
}
