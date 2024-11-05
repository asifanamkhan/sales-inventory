<?php

namespace App\Livewire\Dashboard\Product\Product;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\File;

class ProductForm extends Component
{
    public $product_groups, $product_group_id,
        $product_categories,
        $product_brands, $product_brand_id,
        $product_units, $product_unit_id,$product_u_code;

    public $edit_select = [];
    public $editCategory = 0;

    public $photos = [];
    public $editPhotos = [];
    public $variant_type = 1;
    public $description;
    public $variant_cart = [];
    public $state = [];

    public function productGroup()
    {
        return $this->product_groups = DB::table('INV_ST_GROUP_INFO')
            ->orderBy('st_group_id', 'desc')
            ->get();
    }

    #[On('product_group_change')]
    public function product_group_change($id)
    {
        $this->state['group_code'] = $id;
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


    // ------------- product create end ----------------

    #[On('product-varient-add-to-cart')]
    public function product_varient_add_to_cart($data){
        $this->variant_cart[] = $data;
    }

    public function save()
    {

        Validator::make($this->state, [
            'group_code' => 'required',
            'catagories_id' => 'required',
            'item_name' => 'required',
        ])->validate();

        $this->state['u_code'] = time().'-'.mt_rand(1000,9999);
        if(!@$this->state['unit_id']){
            $this->state['unit_id'] = 2;
        }
        if(count($this->photos)){
            foreach($this->photos as $photo){
                $file_name = $this->state['u_code'].'-'.mt_rand().'.'.$photo['extension'];
                Storage::putFileAs('upload/product', new File($photo['path']), $file_name);
                $allFile[] = $file_name;
            }
            $this->state['photo'] = json_encode($allFile);
        }


        if ($this->product_u_code) {
            $this->state['u_code'] = $this->product_u_code;
            
            foreach ($this->variant_cart as $cart) {
                $cart = (array)$cart;
                // dd($product_exist);
                if(@$cart['st_group_item_id']){
                    $this->state['item_size'] = $cart['item_size'];
                    $this->state['color_code'] = $cart['color_code'];
                    $this->state['variant_description'] = $cart['variant_description'];

                    DB::table('INV_ST_GROUP_ITEM')
                        ->where('st_group_item_id', $cart['st_group_item_id'])
                        ->update($this->state);

                }else{
                    $this->state['item_size'] = $cart['item_size'];
                    $this->state['color_code'] = $cart['color_code'];
                    $this->state['variant_description'] = $cart['variant_description'];
                    DB::table('INV_ST_GROUP_ITEM')->insert($this->state);
                }
            }

        }else{
            if(count($this->variant_cart) > 0){
                foreach($this->variant_cart as $cart){
                    $this->state['item_size'] = $cart['item_size'];
                    $this->state['color_code'] = $cart['color_code'];
                    $this->state['variant_description'] = $cart['variant_description'];
                    DB::table('INV_ST_GROUP_ITEM')->insert($this->state);
                }

            }else{
                DB::table('INV_ST_GROUP_ITEM')->insert($this->state);
            }
        }

        session()->flash('status', 'New product create successfully. You can find it at product list');

        $this->reset();

        return $this->redirect(route('product'), navigate:true);
    }

    // ------------- product create end ----------------



    // ------------- product edit start ----------------

    public function editImgRemove($key) {
        unset($this->editPhotos[$key]);
    }
    public function variant_cart_remove($key) {
        unset($this->variant_cart[$key]);
    }

    // ------------- product edit end ----------------

    public function mount($product_u_code){
        if($product_u_code){
            $this->product_u_code = $product_u_code;
            $product_edit = (array)DB::table('INV_ST_GROUP_ITEM')
                ->where('u_code', $product_u_code)
                ->first([
                    'item_name','group_code','unit_id','model',
                    'catagories_id','description','photo','brand_code','variant_type',
                ]);

           if($product_edit['variant_type'] == 2){
                $product_edit_varient = DB::table('INV_ST_GROUP_ITEM as p')
                    ->where('u_code', $product_u_code)
                    ->leftJoin('INV_ST_ITEM_SIZE as s', function ($join) {
                        $join->on('s.item_size_code', '=', 'p.item_size');
                    })
                    ->leftJoin('INV_COLOR_INFO as c', function ($join) {
                        $join->on('c.tran_mst_id', '=', 'p.color_code');
                    })
                    ->get(['p.item_size','p.color_code','p.variant_description','c.color_name','s.item_size_name','p.st_group_item_id'])
                    ->toArray();

                $this->variant_cart = $product_edit_varient;
                $this->variant_type = 2;

           }

            if($product_edit['photo']){
                $editPhotos = json_decode($product_edit['photo']);
                $this->editPhotos = $editPhotos;
            }


            $this->state = $product_edit;
            $this->edit_select['edit_group_id'] = $product_edit['group_code'];
            $this->edit_select['edit_category_id'] = $product_edit['catagories_id'];
            $this->edit_select['edit_unit_id'] = $product_edit['unit_id'];
            $this->edit_select['edit_brand_id'] = $product_edit['brand_code'];
            $this->editCategory = 1;

        }else{
            $this->editCategory = 0;
        }

        $this->productGroup();
        $this->productBrand();
        $this->productUnit();
    }

    public function render()
    {
        return view('livewire.dashboard.product.product.product-form');
    }
}