<?php

namespace App\Livewire\Dashboard\Product\PricingList;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class PricingListForm extends Component
{
    public $products,$productVarient;
    public $state= [];


    public function productList(){
        $this->products = DB::table('INV_ST_GROUP_ITEM')
            ->distinct('u_code')
            ->orderBy('u_code', 'DESC')
            ->get(['u_code','item_name']);

    }

    #[On('product_change_on_pricing_list')]
    public function product_details($id){

        $this->productVarient = DB::table('INV_ST_GROUP_ITEM as p')
            ->where('u_code', $id)
            ->orderBy('u_code', 'DESC')
            ->leftJoin('INV_ST_ITEM_SIZE as s', function ($join) {
                $join->on('s.item_size_code', '=', 'p.item_size');
            })
            ->leftJoin('INV_COLOR_INFO as cl', function ($join) {
                $join->on('cl.tran_mst_id', '=', 'p.color_code');
            })
            ->select(['p.*','cl.color_name','s.item_size_name'])
            ->get();

        $this->dispatch('product-varient-list-as-product', productVarient: $this->productVarient);
    }

    public function vat_calculation(){
        if(@$this->state['mrp_rate'] && @$this->state['vat_rate']){
            $this->state['vat_amt'] = ((float)$this->state['mrp_rate'] /100) * (float)$this->state['vat_rate'] ?? 0;
        }
    }

    public function save(){

        Validator::make($this->state, [
            'item_code' => 'required',
            'pr_rate' => 'required',
            'mrp_rate' => 'required',
        ])->validate();



        $item_exist = DB::table('INV_PRICE_SCHEDULE_MST')
                    ->where('item_code', $this->state['item_code'])
                    ->exists();

        if($item_exist){
            session()->flash('error', 'This product alrady has the priceing list. check it form the list');
            $this->dispatch('refresh-product-varient-list-as-product');

        }else{

            DB::table('INV_PRICE_SCHEDULE_MST')->insert($this->state);
            session()->flash('status', 'Pricing list added successfully');
            $this->reset();
            $this->dispatch('refresh-product-varient-list-as-product');
            $this->dispatch('refresh-product-varient-list');
        }

    }

    public function render()
    {
        $this->productList();
        $this->state['vat_rate'] = 0;
        return view('livewire.dashboard.product.pricing-list.pricing-list-form');
    }
}