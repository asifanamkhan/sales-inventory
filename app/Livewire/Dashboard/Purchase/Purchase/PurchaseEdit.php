<?php

namespace App\Livewire\Dashboard\Purchase\Purchase;

use Livewire\Component;

class PurchaseEdit extends Component
{
    public $purchase_id;
    public function mount($purchase_id){
        $this->purchase_id = $purchase_id;
        // dd($this->purchase_id);
    }
    public function render()
    {
        return view('livewire.dashboard.purchase.purchase.purchase-edit')
                ->title('Purchase edit');
    }
}