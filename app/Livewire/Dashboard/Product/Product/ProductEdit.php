<?php

namespace App\Livewire\Dashboard\Product\Product;

use Livewire\Component;

class ProductEdit extends Component
{
    public $product_u_code;
    public function mount($product_id){
        $this->product_u_code = $product_id;
    }
    public function render()
    {
        return view('livewire.dashboard.product.product.product-edit')->title('Product edit');
    }
}