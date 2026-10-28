<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);

        $now = now();

        Currency::insert([
            [
                'name' => 'Dólar Estadounidense',
                'symbol' => 'USD',
                'exchange_rate' => 1.0000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Euro',
                'symbol' => 'EUR',
                'exchange_rate' => 0.9200,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Bolívar',
                'symbol' => 'VES',
                'exchange_rate' => 45.5000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Peso Colombiano',
                'symbol' => 'COP',
                'exchange_rate' => 3900.0000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Libra Esterlina',
                'symbol' => 'GBP',
                'exchange_rate' => 0.7900,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $usdCurrencyId = Currency::where('symbol', 'USD')->value('id');

        $products = [
            [
                'name' => 'Laptop Dell Precision 5540',
                'description' => 'Workstation portátil de alto rendimiento para desarrollo y diseño.',
                'price' => 1200.00,
                'tax_cost' => 120.00,
                'manufacturing_cost' => 800.00,
            ],
            [
                'name' => 'Karategui Tokaido',
                'description' => 'Uniforme tradicional para karate de alta resistencia y corte japonés.',
                'price' => 150.00,
                'tax_cost' => 15.00,
                'manufacturing_cost' => 60.00,
            ],
            [
                'name' => 'Servidor VPS Contabo',
                'description' => 'Servidor privado virtual con almacenamiento NVMe para despliegue de aplicaciones.',
                'price' => 85.00,
                'tax_cost' => 8.50,
                'manufacturing_cost' => 30.00,
            ],
            [
                'name' => 'Kit Arrastre Moto Owen',
                'description' => 'Kit completo de piñón, corona y cadena reforzada para motocicleta.',
                'price' => 45.00,
                'tax_cost' => 4.50,
                'manufacturing_cost' => 15.00,
            ],
            [
                'name' => 'Licencia Cursor IDE',
                'description' => 'Suscripción anual para el editor de código potenciado por inteligencia artificial.',
                'price' => 240.00,
                'tax_cost' => 24.00,
                'manufacturing_cost' => 0.00,
            ],
        ];

        foreach ($products as $data) {
            $product = Product::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'price' => $data['price'],
                'currency_id' => $usdCurrencyId,
                'tax_cost' => $data['tax_cost'],
                'manufacturing_cost' => $data['manufacturing_cost'],
            ]);

            $product->prices()->create([
                'currency_id' => $usdCurrencyId,
                'price' => $data['price'],
            ]);
        }

        $token = $user->createToken('admin-token')->plainTextToken;
        $this->command->info('✅ Base de datos sembrada con éxito.');
        $this->command->warn('🔐 Credenciales de prueba:');
        $this->command->line('Email: admin@test.com');
        $this->command->line('Password: password');
        $this->command->warn('🔑 Token Bearer de acceso directo (Cópialo para Swagger):');
        $this->command->line($token);
    }
}
