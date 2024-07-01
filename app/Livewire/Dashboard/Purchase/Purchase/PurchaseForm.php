<?php

namespace App\Livewire\Dashboard\Purchase\Purchase;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PurchaseForm extends Component
{
    public $state = [];
    public $suppliers,$war_houses,$productsearch;
    public $resultProducts = [];
    public $purchaseCart = [];
    public $searchSelect = -1;
    public $countProduct = 0;


    public function suppliersAll()
    {
        return $this->suppliers = DB::table('INV_SUPPLIER_INFO')
            ->orderBy('p_code', 'DESC')
            ->get();
    }

    public function wirehouseAll()
    {
        return $this->war_houses = DB::table('INV_WAREHOUSE_INFO')
            ->orderBy('war_id', 'DESC')
            ->get(['war_id','war_name']);
    }

    public function updatedProductsearch()
    {
        if($this->productsearch){
            $this->resultProducts = DB::table('INV_ST_GROUP_ITEM as p')
            ->where(DB::raw('lower(p.item_name)'), 'like', '%' . strtolower($this->productsearch) . '%')
            ->leftJoin('INV_ST_ITEM_SIZE as s', function ($join) {
                $join->on('s.item_size_code', '=', 'p.item_size');
            })
            ->leftJoin('INV_COLOR_INFO as c', function ($join) {
                $join->on('c.tran_mst_id', '=', 'p.color_code');
            })
            ->get(['item_name','c.color_name','s.item_size_name'])
            ->toArray();

            $this->searchSelect = -1;
        }else{
            $this->resetProductSearch();
        }

        $this->countProduct = count($this->resultProducts);
    }

    public function mount()
    {
        $this->state['tran_date'] = Carbon::now()->toDateString();
    }

    public function render()
    {
        $this->suppliersAll();
        $this->wirehouseAll();
        return view('livewire.dashboard.purchase.purchase.purchase-form');
    }

    //search increment decrement start
    public function decrementHighlight() {
        if($this->searchSelect > 0){
            $this->searchSelect --;
        }
    }
    public function incrementHighlight() {

        if($this->searchSelect < ($this->countProduct - 1)){
            $this->searchSelect ++;
        }
    }
    public function selectAccount(){

        $search = @$this->resultProducts[$this->searchSelect]->item_name;
        if($search){
            $this->productsearch = '';
            $this->resetProductSearch();

            $this->purchaseCart[] = [
                'item_name' => $search,
                'qty' => 1,
            ];
            // dd($this->purchaseCart);
        }
    }

    public function hideDropdown(){
        $this->resetProductSearch();
    }

    public function resetProductSearch(){
        $this->searchSelect = -1;
        $this->resultProducts = [];
    }


    //search increment decrement end
}