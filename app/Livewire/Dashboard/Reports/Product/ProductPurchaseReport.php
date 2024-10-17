<?php

namespace App\Livewire\Dashboard\Reports\Product;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;

class ProductPurchaseReport extends Component
{
    public $products, $ledgers = [];
    public $state = [];

    public function productsAll()
    {
        return $this->products = DB::table('VW_INV_ITEM_DETAILS')
            ->orderBy('st_group_item_id', 'DESC')
            ->get();
    }

    public function search()
    {
        Validator::make($this->state, [
            'st_group_item_id' => 'required|numeric',
        ], [
            'st_group_item_id' => 'product name is required'
        ])->validate();

        $this->ledgers = DB::table('VW_INV_CUSTOMER_PAYMENT_LEDGER')
            ->where('st_group_item_id', $this->state['st_group_item_id'])
            ->get();

    }

    public function mount()
    {
        $this->productsAll();

    }
    public function render()
    {
        return view('livewire.dashboard.reports.product.product-purchase-report');
    }
}