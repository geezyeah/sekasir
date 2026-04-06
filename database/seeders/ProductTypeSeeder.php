<?php

namespace Database\Seeders;

use App\Models\ProductType;
use Illuminate\Database\Seeder;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'cone',
            'cup',
            'bowl',
            'box',
            'package',
        ];

        foreach ($types as $type) {
            ProductType::firstOrCreate(['name' => $type]);
        }
    }
}
