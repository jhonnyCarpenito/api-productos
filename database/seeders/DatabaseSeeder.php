<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);

        Currency::create([
            'name' => 'Dólar Estadounidense',
            'symbol' => 'USD',
            'exchange_rate' => 1.0000,
        ]);

        Currency::create([
            'name' => 'Euro',
            'symbol' => 'EUR',
            'exchange_rate' => 0.9200,
        ]);

        Currency::create([
            'name' => 'Bolívar',
            'symbol' => 'VES',
            'exchange_rate' => 45.5000,
        ]);

        $this->call(ProductSeeder::class);
    }
}
