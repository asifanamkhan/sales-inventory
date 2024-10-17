<?php

namespace App\Livewire\Dashboard\Reports\Product;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ProductPurchaseReturnReport extends Component
{
    public $products, $branchs, $catagories, $ledgers = [];
    public $state = [];

    public function productsAll()
    {
        return $this->products = DB::table('VW_INV_ITEM_DETAILS')
            ->orderBy('st_group_item_id', 'DESC')
            ->get();
    }

    public function branchAll()
    {
        return $this->branchs = DB::table('INV_BRANCH_INFO')
            ->orderBy('branch_id', 'DESC')
            ->get();
    }

    public function catagoriesAll()
    {
        return $this->catagories = DB::table('INV_CATAGORIES_INFO')
            ->orderBy('tran_mst_id', 'DESC')
            ->get();
    }

    public function search()
    {

        $query = DB::table('VW_PRODUCT_PURCHASE_RETURN');
        if(@$this->state['start_date']){
            $query->where('return_date', '>=', $this->state['start_date']);
        }
        if(@$this->state['end_date']){
            $query->where('return_date', '<=', $this->state['end_date']);
        }
        if(@$this->state['st_group_item_id']){
            $query->where('st_group_item_id', $this->state['st_group_item_id']);
        }
        if(@$this->state['branch_id']){
            $query->where('branch_id', $this->state['branch_id']);
        }
        if(@$this->state['catagories_id']){
            $query->where('catagories_id', $this->state['catagories_id']);
        }

        $this->ledgers = $query->get();

        // dd($this->ledgers);

    }

    public function mount()
    {
        $this->productsAll();
        $this->branchAll();
        $this->catagoriesAll();
        $this->state['branch_id'] = '';
        $this->state['catagories_id'] = '';
        $this->state['st_group_item_id'] = '';
        $this->state['start_date'] = '';
        $this->state['end_date'] = '';


    }
    public function render()
    {
        return view('livewire.dashboard.reports.product.product-purchase-return-report');
    }
}