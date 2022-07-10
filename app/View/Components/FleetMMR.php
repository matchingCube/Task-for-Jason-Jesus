<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FleetMMR extends Component
{
    public $fleets = null;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($fleets)
    {
        $this->fleets = $fleets;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.fleet-mmr');
    }
}
