<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AppBrand extends Component
{
    public array $images = [

    ];
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'HTML'
        <a href="/dashboard" wire:navigate>
            <!-- Visible when expanded -->
            <div {{ $attributes->class(["hidden-when-collapsed"]) }}>
                <div class="flex justify-center items-center space-x-3">
                    <img src="/images/L.png" alt="NCCS Logo" class="h-16 w-auto " />
                    <span class="font-bold text-2xl bg-gradient-to-r from-purple-500 to-pink-400 bg-clip-text text-transparent">
                         Learning Portal
                    </span>
                </div>
            </div>

            <!-- Shown when collapsed -->
            <div class="display-when-collapsed hidden mx-auto mt-4 mb-2">
                <x-icon name="s-cube" class="w-8 h-8 text-purple-500" />
            </div>
        </a>
    HTML;
    }
}



