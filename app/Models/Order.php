<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'shop_id',
        'shift_id',
        'total_amount',
        'payment_type',
        'cash_received',
        'change_amount',
        'formatted_id',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'cash_received' => 'decimal:2',
            'change_amount' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFormattedIdAttribute()
    {
        $date = $this->created_at->format('Ymd');
        $randomChars = strtoupper(substr(md5($this->id . 'order'), 0, 5));
        $orderNumber = str_pad($this->id % 10000, 4, '0', STR_PAD_LEFT);
        
        return $date . $randomChars . $orderNumber;
    }
}
