<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $fillable = ['name', 'properties'];

    protected $casts = [
        'properties' => 'array',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function shifts()
    {
        return $this->hasMany(Shift::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function authorizedEmployees()
    {
        return $this->belongsToMany(User::class, 'shop_user');
    }

    public function getProperty($key, $default = null)
    {
        return $this->properties[$key] ?? $default;
    }
}
