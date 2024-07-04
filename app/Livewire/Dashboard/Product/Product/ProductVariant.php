<?php

namespace App\Livewire\Dashboard\Product\Product;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ProductVariant extends Component
{

    public $product_colors, $product_size,
        $product_color_id, $product_size_id,
        $product_color_name, $product_size_name;

    public function addVariant()
    {
        if ($this->product_color_id || $this->product_size_id) {

            $this->dispatch('product-varient-add-to-cart', data: [
                'color_code' => $this->product_color_id,
                'color_name' => $this->product_color_id ? $this->product_color_name : '',
                'item_size' => $this->product_size_id,
                'item_size_name' => $this->product_size_id ? $this->product_size_name : '',
            ]);
        } else {
            session()->flash('error', 'Please select at least one varient');
        }
    }

    public function productColor()
    {
        return $this->product_colors = DB::table('INV_COLOR_INFO')
            ->orderBy('tran_mst_id', 'desc')
            ->get();
    }

    public function productSize()
    {
        return $this->product_size = DB::table('INV_ST_ITEM_SIZE')
            ->orderBy('item_size_code', 'desc')
            ->get();
    }
    public function mount()
    {
        $this->productSize();
        $this->productColor();
    }
    public function render()
    {
        return view('livewire.dashboard.product.product.product-variant');
    }
}