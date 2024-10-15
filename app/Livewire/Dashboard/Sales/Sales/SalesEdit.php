<?php

namespace App\Livewire\Dashboard\Sales\Sales;

use Livewire\Component;

class SalesEdit extends Component
{
    public $sale_id;
    public function mount($sale_id){
        $this->sale_id = $sale_id;
        // dd($this->sale_id);
    }
    public function render()
    {
        return view('livewire.dashboard.sales.sales.sales-edit')->title('Sales edit');
    }
}