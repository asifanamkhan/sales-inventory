<?php

namespace App\Livewire\Dashboard\Product\Group;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class ProductGroupForm extends Component
{
    public $group_name;
    public $product_group;
    public $editForm = false;


    public function store(){

        $this->validate([
            'group_name' => 'required',
        ]);

        DB::table('INV_ST_GROUP_INFO')->insert([
            'group_name' => $this->group_name,
            'user_name' => Auth::user()->name
        ]);

        $this->dispatch('refresh-product-group');
        session()->flash('status', 'Product group create successfully');

        $this->reset();
    }

    #[On('create-product-group-modal')]
    public function refresh(){
        $this->reset();
    }

    #[On('product-group-edit-modal')]
    public function edit($id){

        $this->editForm = true;
        $this->product_group = DB::table('INV_ST_GROUP_INFO')
            ->where('st_group_id', $id)->first();

        $this->group_name = $this->product_group->group_name;

    }

    public function update() {
        $validate = $this->validate([
            'group_name' => 'required',
        ]);

        DB::table('INV_ST_GROUP_INFO')
            ->where('st_group_id', $this->product_group->st_group_id)
            ->update($validate);

        $this->dispatch('refresh-product-group');
        session()->flash('status', 'Product group updated successfully');

    }
    public function render()
    {
        return view('livewire.dashboard.product.group.product-group-form');
    }
}
