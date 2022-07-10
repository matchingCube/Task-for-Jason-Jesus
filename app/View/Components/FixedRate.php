<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FixedRate extends Component
{
    public $rate = null;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($rate)
    {
        $this->rate = $rate;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.fixed-rate');
    }
}
