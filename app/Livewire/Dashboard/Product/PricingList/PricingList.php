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
        $products = DB::table('INV_PRICE_SCHEDULE_MST as p');

        $products
            ->orderBy('item_mst_id', 'DESC')
            ->leftJoin('INV_ST_GROUP_ITEM as c', function ($join) {
                $join->on('c.st_group_item_id', '=', 'p.item_code');
            })
            ->leftJoin('INV_ST_ITEM_SIZE as s', function ($join) {
                $join->on('s.item_size_code', '=', 'c.item_size');
            })
            ->leftJoin('INV_COLOR_INFO as cl', function ($join) {
                $join->on('cl.tran_mst_id', '=', 'c.color_code');
            })
            ->select(['p.*','c.item_name','cl.color_name','s.item_size_name']);

            if ($this->search) {
                $products
                    ->where(DB::raw('lower(c.item_name)'), 'like', '%' . strtolower($this->search) . '%');
            }
            

        return $products->paginate($this->pagination);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        return view('livewire.dashboard.product.pricing-list.pricing-list');
    }
}