<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $activeShift = $user->activeShift;
                
                if ($activeShift) {
                    $shop = $activeShift->shop;
                    $properties = $shop->properties ?? [
                        'bg_color' => '#ffffff',
                        'text_color' => '#1f2937',
                        'primary_color' => '#4f46e5',
                    ];
                    
                    $view->with('shopProperties', $properties)
                         ->with('activeShop', $shop);
                } else {
                    $view->with('shopProperties', null)
                         ->with('activeShop', null);
                }
            }
        });
    }
}
