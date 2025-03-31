<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ResponsiveNavLink extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public bool $active = false
    ) {}

    /**
     * Get the view / contents that represents the component.
     */
    public function render()
    {
        return view('components.responsive-nav-link');
    }
} 