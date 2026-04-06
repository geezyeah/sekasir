<?php

namespace Database\Seeders;

use App\Models\Shop;
use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        Shop::create(['name' => 'Ice Lepen']);
        Shop::create(['name' => 'Sami Remen']);
    }
}
