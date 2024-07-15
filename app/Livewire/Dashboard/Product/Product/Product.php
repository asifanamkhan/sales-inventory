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
        $products = DB::table('INV_ST_GROUP_ITEM as p');

        $products
            ->distinct('u_code')
            ->orderBy('u_code', 'DESC')
            ->leftJoin('INV_ST_BRAND_INFO as b', function ($join) {
                $join->on('b.brand_code', '=', 'p.brand_code');
            })
            ->leftJoin('INV_CATAGORIES_INFO as c', function ($join) {
                $join->on('c.tran_mst_id', '=', 'p.catagories_id');
            })
            ->select(['p.u_code', 'b.brand_name', 'c.catagories_name', 'p.item_name', 'p.photo']);

        if ($this->search) {
            $products
                ->where(DB::raw('lower(p.item_name)'), 'like', '%' . strtolower($this->search) . '%');
        }
        // $p =   $products->get();
        // dd($p);

        return $products->paginate($this->pagination);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.dashboard.product.product.product')->title('Product');
    }
}