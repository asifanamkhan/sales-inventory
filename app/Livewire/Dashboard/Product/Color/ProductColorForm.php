<?php

namespace App\Livewire\Dashboard\Product\Color;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class ProductColorForm extends Component
{
    public $color_name;
    public $product_color;
    public $editForm = false;


    public function store()
    {

        $this->validate([
            'color_name' => 'required',
        ]);

        DB::table('INV_COLOR_INFO')->insert([
            'color_name' => $this->color_name,
            'user_name' => Auth::user()->id
        ]);

        $this->dispatch('refresh-product-color');
        session()->flash('status', 'Product color create successfully');

        $this->reset();
    }

    #[On('create-product-color-modal')]
    public function refresh()
    {
        $this->reset();
        $this->resetValidation();
    }

    #[On('product-color-edit-modal')]
    public function edit($id)
    {

        $this->refresh();
        $this->editForm = true;
        $this->product_color = DB::table('INV_COLOR_INFO')
            ->where('tran_mst_id', $id)->first();

        $this->color_name = $this->product_color->color_name;
    }

    public function update()
    {
        $validate = $this->validate([
            'color_name' => 'required',
        ]);

        DB::table('INV_COLOR_INFO')
            ->where('tran_mst_id', $this->product_color->tran_mst_id)
            ->update($validate);

        $this->dispatch('refresh-product-color');
        session()->flash('status', 'Product color updated successfully');
    }
    public function render()
    {
        return view('livewire.dashboard.product.color.product-color-form');
    }
}
