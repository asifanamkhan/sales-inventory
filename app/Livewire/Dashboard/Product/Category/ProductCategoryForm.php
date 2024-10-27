<?php

namespace App\Livewire\Dashboard\Product\Category;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Validator;

class ProductCategoryForm extends Component
{
    use WithFileUploads;

    public $catagories_name;
    public $product_category;
    public $groups;
    public $editForm = '';
    public $state = [];


    public function store()
    {
        Validator::make($this->state, [
            'catagories_name' => 'required',
            'group_name' => 'required',
            'photo' => 'nullable|mimes:jpg,bmp,png,svg,jpeg|max:1024'
        ])->validate();

        if (@$this->state['photo']) {
            $this->state['photo'] = $this->state['photo']->store('upload/product-category');
        }

        $this->state['user_name'] = Auth::user()->id;
        DB::table('INV_CATAGORIES_INFO')->insert($this->state);

        $this->dispatch('refresh-product-category');
        session()->flash('status', 'Product category create successfully');

        $this->reset();
        $this->state['photo'] = '';
    }

    #[On('create-product-category-modal')]
    public function refresh()
    {
        $this->reset();
        $this->resetValidation();
    }

    #[On('product-category-edit-modal')]
    public function edit($id)
    {
        $this->refresh();
        $this->editForm = true;
        $this->product_category = (array)DB::table('INV_CATAGORIES_INFO')
            ->where('tran_mst_id', $id)->first();

        $this->state = $this->product_category;
        $this->state['old_photo'] = $this->product_category['photo'];
        $this->state['photo'] = '';
    }

    public function update()
    {
        Validator::make($this->state, [
            'catagories_name' => 'required',
            'group_name' => 'required',
            'photo' => 'mimes:jpg,bmp,png|max:1024|nullable'
        ])->validate();

        if (@$this->state['photo']) {
            $this->state['photo'] = $this->state['photo']->store('upload');
        } else {
            $this->state['photo'] = $this->state['old_photo'];
        }

        unset($this->state['old_photo']);

        DB::table('INV_CATAGORIES_INFO')
            ->where('tran_mst_id', $this->product_category['tran_mst_id'])
            ->update($this->state);

        $this->state['old_photo'] = $this->state['photo'];
        $this->state['photo'] = '';

        $this->dispatch('refresh-product-category');
        session()->flash('status', 'Product category updated successfully');
    }

    public function productGroup()
    {
        $this->groups = DB::table('INV_ST_GROUP_INFO')
            ->orderBy('st_group_id', 'desc')
            ->get();
    }

    public function render()
    {
        $this->productGroup();
        return view('livewire.dashboard.product.category.product-category-form');
    }
}