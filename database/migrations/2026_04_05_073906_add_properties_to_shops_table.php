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
        Schema::table('shops', function (Blueprint $table) {
            $table->json('properties')->nullable();
        });

        // Set default properties for existing shops
        // Ice Lepen - Deep red from logo with creme text
        DB::table('shops')->where('name', 'Ice Lepen')->update([
            'properties' => json_encode([
                'bg_color' => '#A31F1F',
                'text_color' => '#F5E6D3',
                'primary_color' => '#A31F1F',
            ])
        ]);

        // Dimsum shop - Warm brown/orange color
        DB::table('shops')->where('name', '!=', 'Ice Lepen')->update([
            'properties' => json_encode([
                'bg_color' => '#8B4513',
                'text_color' => '#F5E6D3',
                'primary_color' => '#8B4513',
            ])
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn('properties');
        });
    }
};
