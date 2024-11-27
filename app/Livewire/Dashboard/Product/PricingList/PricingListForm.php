<?php

namespace App\Livewire\Dashboard\Product\PricingList;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class PricingListForm extends Component
{
    public $product, $productVarient;
    public $state = [];

    #[On('pricing-list-add')]
    public function productSelect($id)
    {
        $this->product = DB::table('VW_INV_ITEM_DETAILS as p')
            ->where('st_group_item_id', $id)
            ->first();

        $this->state['item_code'] = $this->product->item_code;
        $this->state['pr_rate'] = $this->product->pr_rate;
        $this->state['dp_rate'] = $this->product->dp_rate;
        $this->state['rp_rate'] = $this->product->rp_rate;
        $this->state['mrp_rate'] = $this->product->mrp_rate;
        $this->state['vat_rate'] = $this->product->vat_rate;
        $this->state['vat_amt'] = $this->product->vat_amt;
        if($this->product->vat_rate){
            $this->state['vat_apply'] = true;
        }else{
            $this->state['vat_apply'] = false;
        }
    }

    public function vat_calculation()
    {
        if (@$this->state['mrp_rate'] && @$this->state['vat_rate']) {
            $this->state['vat_amt'] = ((float)$this->state['mrp_rate'] / 100) * (float)$this->state['vat_rate'] ?? 0;
        }
    }

    #[On('save_form')]
    public function save()
    {

        Validator::make($this->state, [
            'item_code' => 'required',
            'pr_rate' => 'required',
            'mrp_rate' => 'required',
        ])->validate();


        $item_exist = DB::table('INV_PRICE_SCHEDULE_MST')
            ->where('item_code', $this->state['item_code'])
            ->exists();

        if ($item_exist) {

            DB::table('INV_PRICE_SCHEDULE_MST')
                ->where('item_code', $this->state['item_code'])
                ->update($this->state);

            session()->flash('status', 'Pricing list updated successfully');
            $this->reset();
            $this->dispatch('refresh-product-varient-list');

        } else {

            DB::table('INV_PRICE_SCHEDULE_MST')
                ->insert($this->state);

            session()->flash('status', 'Pricing list added successfully');

            $this->reset();
            $this->dispatch('refresh-product-varient-list');

        }
    }

    public function vatApply()
    {
        if ($this->state['vat_apply'] == true) {
            $this->state['vat_apply'] = 1;
        } else {
            $this->state['vat_apply'] = 0;
            $this->state['vat_amt'] =  '';
            $this->state['vat_rate'] =  '';
        }
    }


    public function mount()
    {
        $this->state['vat_rate'] = '';
        $this->state['vat_apply'] = false;
    }

    public function render()
    {
        // $this->productList();
        return view('livewire.dashboard.product.pricing-list.pricing-list-form');
    }
}