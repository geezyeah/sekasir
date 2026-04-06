<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $iceLepen = Shop::where('name', 'Ice Lepen')->first();
        $samiRemen = Shop::where('name', 'Sami Remen')->first();

        // Ice Lepen Products
        $flavors = ['Vanilla', 'Chocolate', 'Mix'];
        $types = [
            'Cone' => 8000,
            'Cup' => 10000,
        ];

        foreach ($flavors as $flavor) {
            foreach ($types as $type => $price) {
                Product::create([
                    'shop_id' => $iceLepen->id,
                    'name' => "Ice Cream {$flavor} ({$type})",
                    'price' => $price,
                    'type' => strtolower($type),
                    'is_seasonal' => false,
                ]);
            }
        }

        // Sami Remen Products
        $dimsumPackages = [
            ['name' => 'Dimsum 4 Pcs', 'price' => 18000, 'type' => 'package'],
            ['name' => 'Dimsum 6 Pcs', 'price' => 20000, 'type' => 'package'],
            ['name' => 'Dimsum 12 Pcs', 'price' => 24000, 'type' => 'package'],
            ['name' => 'Mentai Dimsum', 'price' => 22000, 'type' => 'package'],
        ];

        foreach ($dimsumPackages as $package) {
            Product::create([
                'shop_id' => $samiRemen->id,
                'name' => $package['name'],
                'price' => $package['price'],
                'type' => $package['type'],
                'is_seasonal' => false,
            ]);
        }
    }
}
