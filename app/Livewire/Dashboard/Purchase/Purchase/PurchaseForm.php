<?php

namespace App\Livewire\Dashboard\Purchase\Purchase;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PurchaseForm extends Component
{
    public $state = [];
    public $suppliers, $war_houses, $productsearch;
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
            ->get(['war_id', 'war_name']);
    }

    public function updatedProductsearch()
    {
        if ($this->productsearch) {

            $result = DB::table('INV_ST_GROUP_ITEM as p')
                ->where('barcode', $this->productsearch)
                ->leftJoin('INV_ST_ITEM_SIZE as s', function ($join) {
                    $join->on('s.item_size_code', '=', 'p.item_size');
                })
                ->leftJoin('INV_COLOR_INFO as c', function ($join) {
                    $join->on('c.tran_mst_id', '=', 'p.color_code');
                })
                ->get(['p.st_group_item_id','p.item_name', 'c.color_name', 's.item_size_name'])
                ->toArray();

            if ($result) {
                $this->resultProducts = $result;
                $this->resultAppend(0);

            } else {

                $this->resultProducts = DB::table('INV_ST_GROUP_ITEM as p')
                    ->where(DB::raw('lower(p.item_name)'), 'like', '%' . strtolower($this->productsearch) . '%')
                    ->leftJoin('INV_ST_ITEM_SIZE as s', function ($join) {
                        $join->on('s.item_size_code', '=', 'p.item_size');
                    })
                    ->leftJoin('INV_COLOR_INFO as c', function ($join) {
                        $join->on('c.tran_mst_id', '=', 'p.color_code');
                    })
                    ->get(['p.st_group_item_id','p.item_name', 'c.color_name', 's.item_size_name'])
                    ->toArray();
            }

            $this->searchSelect = -1;
        } else {
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
    public function decrementHighlight()
    {
        if ($this->searchSelect > 0) {
            $this->searchSelect--;
        }
    }
    public function incrementHighlight()
    {

        if ($this->searchSelect < ($this->countProduct - 1)) {
            $this->searchSelect++;
        }
    }
    public function selectAccount()
    {
        $this->resultAppend($this->searchSelect);
    }

    public function searchRowSelect($pk)
    {
        $this->resultAppend($pk);
    }

    public function resultAppend($key)
    {
        $search = @$this->resultProducts[$key]->st_group_item_id;
        if ($search) {
            $pricing = DB::table('INV_PRICE_SCHEDULE_MST')
                    ->where('item_code', $search)
                    ->first();

            if($pricing){

                $line_total = (float)$pricing->mrp_rate + @$pricing->vat_amt ?? 0;

                $this->purchaseCart[] = [
                    'item_name' => @$this->resultProducts[$key]->item_name,
                    'color_name' => @$this->resultProducts[$key]->color_name,
                    'item_size_name' => @$this->resultProducts[$key]->item_size_name,
                    'mrp_rate' => $pricing->mrp_rate,
                    'vat_amt' => $pricing->vat_amt,
                    'line_total' => $line_total,
                    'qty' => 1,
                    'discount' => 0,
                ];

                $this->productsearch = '';
                $this->resetProductSearch();

            }else{
                $this->resetProductSearch();
                session()->flash('error', 'Pricing has not added to selected product');
            }


            // dd($this->purchaseCart);
        }
    }

    public function hideDropdown()
    {
        $this->resetProductSearch();
    }

    public function resetProductSearch()
    {
        $this->searchSelect = -1;
        $this->resultProducts = [];
    }

    public function calculation($key){
        $qty = (float)$this->purchaseCart[$key]['qty'] ?? 0;
        $mrp_rate = (float)$this->purchaseCart[$key]['mrp_rate'] ?? 0;
        $discount = (float)$this->purchaseCart[$key]['discount'] ?? 0;
        $vat = (float)$this->purchaseCart[$key]['vat_amt'] ?? 0;

        $this->purchaseCart[$key]['line_total'] = number_format(((($qty * $mrp_rate) + $vat) -  $discount),2);
    }


    //search increment decrement end
}