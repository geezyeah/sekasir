<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update Ice Lepen properties
        DB::table('shops')->where('name', 'Ice Lepen')->update([
            'properties' => json_encode([
                'bg_color' => '#A31F1F',
                'text_color' => '#F5E6D3',
                'primary_color' => '#A31F1F',
                'logo_path' => '/images/logos/ice-lepen-logo.jpg',
            ])
        ]);

        // Update Dimsum shop properties
        DB::table('shops')->where('name', '!=', 'Ice Lepen')->update([
            'properties' => json_encode([
                'bg_color' => '#8B4513',
                'text_color' => '#F5E6D3',
                'primary_color' => '#8B4513',
                'logo_path' => '/images/logos/dimsum-logo.svg',
            ])
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to previous properties without logo_path
        DB::table('shops')->where('name', 'Ice Lepen')->update([
            'properties' => json_encode([
                'bg_color' => '#A31F1F',
                'text_color' => '#F5E6D3',
                'primary_color' => '#A31F1F',
            ])
        ]);

        DB::table('shops')->where('name', '!=', 'Ice Lepen')->update([
            'properties' => json_encode([
                'bg_color' => '#8B4513',
                'text_color' => '#F5E6D3',
                'primary_color' => '#8B4513',
            ])
        ]);
    }
};
