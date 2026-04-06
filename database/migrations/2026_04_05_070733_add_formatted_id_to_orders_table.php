<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Order;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('formatted_id')->nullable()->unique();
        });

        // Populate formatted_id for existing orders
        $orders = Order::all();
        foreach ($orders as $order) {
            $date = $order->created_at->format('Ymd');
            $randomChars = strtoupper(substr(md5($order->id . 'order'), 0, 5));
            $orderNumber = str_pad($order->id % 10000, 4, '0', STR_PAD_LEFT);
            $formattedId = $date . $randomChars . $orderNumber;

            $order->update(['formatted_id' => $formattedId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('formatted_id');
        });
    }
};
