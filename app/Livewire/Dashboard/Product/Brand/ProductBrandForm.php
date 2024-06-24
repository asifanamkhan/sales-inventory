<?php

namespace App\Livewire\Dashboard\Product\Brand;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class ProductBrandForm extends Component
{
    public $brand_name;
    public $product_brand;
    public $editForm = false;


    public function store()
    {

        $this->validate([
            'brand_name' => 'required',
        ]);

        DB::table('INV_ST_BRAND_INFO')->insert([
            'brand_name' => $this->brand_name,
            'user_id' => Auth::user()->id
        ]);

        $this->dispatch('refresh-product-brand');
        session()->flash('status', 'Product brand create successfully');

        $this->reset();
    }

    #[On('create-product-brand-modal')]
    public function refresh()
    {
        $this->reset();
        $this->resetValidation();
    }

    #[On('product-brand-edit-modal')]
    public function edit($id)
    {

        $this->refresh();
        $this->editForm = true;
        $this->product_brand = DB::table('INV_ST_BRAND_INFO')
            ->where('brand_code', $id)->first();

        $this->brand_name = $this->product_brand->brand_name;
    }

    public function update()
    {
        $validate = $this->validate([
            'brand_name' => 'required',
        ]);

        DB::table('INV_ST_BRAND_INFO')
            ->where('brand_code', $this->product_brand->brand_code)
            ->update($validate);

        $this->dispatch('refresh-product-brand');
        session()->flash('status', 'Product brand updated successfully');
    }
    public function render()
    {
        return view('livewire.dashboard.product.brand.product-brand-form');
    }
}
