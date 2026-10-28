<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $usd = Currency::where('symbol', 'USD')->firstOrFail();

        $product = Product::create([
            'name' => 'Laptop Pro 15',
            'description' => 'High performance laptop for development',
            'price' => 1200.00,
            'currency_id' => $usd->id,
            'tax_cost' => 120.00,
            'manufacturing_cost' => 700.00,
        ]);

        $product->prices()->create([
            'currency_id' => $usd->id,
            'price' => 1200.00,
        ]);
    }
}
