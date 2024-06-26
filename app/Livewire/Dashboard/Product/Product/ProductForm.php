<?php

namespace App\Livewire\Dashboard\Product\Product;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class ProductForm extends Component
{
    public $product_groups, $product_group_id,
        $product_categories, $product_category_id,
        $product_brands, $product_brand_id,
        $product_units, $product_unit_id;

    public $photos = [];
    public $varient = false;
    public $varientkey;
    public $description;

    public function productGroup()
    {
        return $this->product_groups = DB::table('INV_ST_GROUP_INFO')
            ->orderBy('st_group_id', 'desc')
            ->get();
    }

    #[On('product_group_change')]
    public function product_group_change($id)
    {
        $this->product_group_id = $id;
        $this->product_categories = DB::table('INV_CATAGORIES_INFO')
            ->where('group_name', $id)
            ->get();
        $this->dispatch('product-categories-as-group', categories: $this->product_categories);
    }

    public function productBrand()
    {
        return $this->product_brands = DB::table('INV_ST_BRAND_INFO')
            ->orderBy('brand_code','desc')
            ->get();
    }

    public function productUnit()
    {
        return $this->product_units = DB::table('INV_ST_UNIT_CONVERT')
            ->orderBy('st_unit_convert_id','desc')
            ->get();
    }

    public function updatedVarientkey($value){
        if($value){
            $this->varient = true;
        }else{
            $this->varient = false;
        }
    }

    public function save()
    {
        dd($this->photos);
    }

    public function render()
    {
        $this->productGroup();
        $this->productBrand();
        $this->productUnit();
        return view('livewire.dashboard.product.product.product-form');
    }
}
