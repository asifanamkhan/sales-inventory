<?php

namespace App\Livewire\Dashboard\Product\PricingList;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class PricingList extends Component
{
    use WithPagination;

    public $search;
    public $event = 'pricing-list';
    public $pagination = 10;


    #[On('refresh-product-varient-list')]
    #[Computed]
    public function resultProduct()
    {
        $products = DB::table('VW_INV_ITEM_DETAILS as p')
            ->leftJoin('VW_INV_ITEM_STOCK_QTY as c', function ($join) {
                $join->on('c.st_group_item_id', '=', 'p.st_group_item_id');
            })
            ->orderBy('p.item_code', 'DESC')
            ->select(['p.*','c.max_ch_qty','c.stock_qty']);

        if ($this->search) {
            $products
                ->where(DB::raw('lower(c.item_name)'), 'like', '%' . strtolower($this->search) . '%')
                ->orWhere(DB::raw('lower(c.item_code)'), 'like', '%' . strtolower($this->search) . '%');
        }


        return $products->paginate($this->pagination);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        return view('livewire.dashboard.product.pricing-list.pricing-list')->title('product pricing list');
    }
}