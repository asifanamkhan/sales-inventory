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
        $products = DB::table('INV_ST_GROUP_ITEM');

        if ($this->search) {
            $products
                ->where(DB::raw('lower(item_name)'), 'like', '%' . strtolower($this->search) . '%');
        }

        return $products->orderBy('st_group_item_id', 'DESC')
            ->paginate($this->pagination);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.dashboard.product.product.product');
    }
}
