<?php

namespace App\Livewire\Dashboard\Purchase\Purchase;

use Livewire\Component;

class PurchaseCreate extends Component
{
    public function render()
    {
        return view('livewire.dashboard.purchase.purchase.purchase-create')->title('Purchase create');
    }
}