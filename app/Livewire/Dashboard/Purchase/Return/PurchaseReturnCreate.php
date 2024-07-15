<?php

namespace App\Livewire\Dashboard\Purchase\Return;

use Livewire\Component;

class PurchaseReturnCreate extends Component
{
    public function render()
    {
        return view('livewire.dashboard.purchase.return.purchase-return-create')->title('Purchase return create');
    }
}