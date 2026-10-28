<?php

declare(strict_types=1);

namespace App\Actions\Products;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CreateProductAction
{
    public function execute(array $data): Product
    {
        return DB::transaction(function () use ($data): Product {
            $product = Product::create($data);

            $product->prices()->create([
                'currency_id' => $data['currency_id'],
                'price' => $data['price'],
            ]);

            return $product;
        });
    }
}
