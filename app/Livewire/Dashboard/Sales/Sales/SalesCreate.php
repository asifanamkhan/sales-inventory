<?php

namespace App\Livewire\Dashboard\Sales\Sales;

use Livewire\Component;

class SalesCreate extends Component
{
    public function render()
    {
        return view('livewire.dashboard.sales.sales.sales-create')->title('Sales create');
    }
}