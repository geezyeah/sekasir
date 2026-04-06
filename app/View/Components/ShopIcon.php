<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ShopIcon extends Component
{
    public function __construct(
        public string $shopName,
        public ?string $class = 'text-base'
    ) {}

    public function render(): View|Closure|string
    {
        $icon = $this->shopName === 'Ice Lepen' 
            ? '<i class="fas fa-ice-cream ' . $this->class . '" style="color: #c41e3a;"></i>'
            : '<i class="fas fa-bowl-food ' . $this->class . '" style="color: #f39c12;"></i>';
        
        return $icon;
    }
}
