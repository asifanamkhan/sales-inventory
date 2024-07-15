<?php

namespace App\Livewire\Dashboard\ProductDamage;

use Livewire\Component;

class ProductDamageEdit extends Component
{
    public function render()
    {
        return view('livewire.dashboard.product-damage.product-damage-edit')->title('Product damage edit');
    }
}