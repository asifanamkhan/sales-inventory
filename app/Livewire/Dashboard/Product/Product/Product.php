<?php

namespace App\Livewire\Dashboard\Product\Product;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Product extends Component
{
    use WithPagination;

    public $search;
    public $pagination = 10;


    #[Computed]
    public function resultProduct()
    {

         $products = DB::table('INV_ST_GROUP_ITEM as p')
            ->whereIn('st_group_item_id', function($query) {
                $query->select(DB::raw('MIN(st_group_item_id)'))
                    ->from('INV_ST_GROUP_ITEM')
                    ->groupBy('u_code');
            })
            ->orderBy('u_code', 'DESC')
            ->leftJoin('INV_ST_BRAND_INFO as b', function ($join) {
                $join->on('b.brand_code', '=', 'p.brand_code');
            })
            ->leftJoin('INV_CATAGORIES_INFO as c', function ($join) {
                $join->on('c.tran_mst_id', '=', 'p.catagories_id');
            })
            ->leftJoin('INV_ST_GROUP_INFO as g', function ($join) {
                $join->on('g.st_group_id', '=', 'p.group_code');
            })
            ->select(['p.u_code', 'b.brand_name', 'c.catagories_name', 'p.item_name', 'p.photo', 'p.variant_type','g.group_name']);

        if ($this->search) {
            $products
                ->where(DB::raw('lower(p.item_name)'), 'like', '%' . strtolower($this->search) . '%')
                ->orWhere(DB::raw('lower(p.group_name)'), 'like', '%' . strtolower($this->search) . '%')
                ->orWhere(DB::raw('lower(b.brand_name)'), 'like', '%' . strtolower($this->search) . '%')
                ->orWhere(DB::raw('lower(c.catagories_name)'), 'like', '%' . strtolower($this->search) . '%');
        }
        // $p =   $products->get();
        // dd($p);

        return $products->paginate($this->pagination);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPagination()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.dashboard.product.product.product')
            ->title('Product');
    }
}