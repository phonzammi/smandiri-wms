<?php

namespace App\Livewire\SalesmanIncentive;

use App\Models\Salesman;
use Livewire\Component;

class SalesmanIncentiveComponent extends Component
{
    public function render()
    {
        $salesman = Salesman::all();
        return view('livewire.salesman-incentive.salesman-incentive-component', compact("salesman"));
    }
}
