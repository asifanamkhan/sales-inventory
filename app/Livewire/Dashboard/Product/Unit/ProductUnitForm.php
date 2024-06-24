<?php

namespace App\Livewire\Dashboard\Product\Unit;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class ProductUnitForm extends Component
{
    public $unit_name;
    public $product_unit;
    public $editForm = false;


    public function store()
    {

        $this->validate([
            'unit_name' => 'required',
        ]);

        DB::table('INV_ST_UNIT_CONVERT')->insert([
            'unit_name' => $this->unit_name,
            'user_name' => Auth::user()->id
        ]);

        $this->dispatch('refresh-product-unit');
        session()->flash('status', 'Product unit create successfully');

        $this->reset();
    }

    #[On('create-product-unit-modal')]
    public function refresh()
    {
        $this->reset();
        $this->resetValidation();
    }

    #[On('product-unit-edit-modal')]
    public function edit($id)
    {
        $this->refresh();
        $this->editForm = true;
        $this->product_unit = DB::table('INV_ST_UNIT_CONVERT')
            ->where('st_unit_convert_id', $id)->first();

        $this->unit_name = $this->product_unit->unit_name;
    }

    public function update()
    {
        $validate = $this->validate([
            'unit_name' => 'required',
        ]);

        DB::table('INV_ST_UNIT_CONVERT')
            ->where('st_unit_convert_id', $this->product_unit->st_unit_convert_id)
            ->update($validate);

        $this->dispatch('refresh-product-unit');
        session()->flash('status', 'Product unit updated successfully');
    }
    public function render()
    {
        return view('livewire.dashboard.product.unit.product-unit-form');
    }
}
