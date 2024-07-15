<?php

namespace App\Livewire\Dashboard\Sales\SalesReturn;

use Livewire\Component;

class SalesReturnCreate extends Component
{
    public function render()
    {
        return view('livewire.dashboard.sales.sales-return.sales-return-create')->title('Sales return create');
    }
}