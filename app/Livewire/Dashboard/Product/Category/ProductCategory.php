<?php

namespace App\Livewire\Dashboard\Product\Category;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class ProductCategory extends Component
{
    use WithPagination;

    public $modal_title;
    public $event = 'product-category';
    public $search;
    public $pagination = 10;


    #[Computed]
    public function resultCategory()
    {
        $categorys = DB::table('INV_CATAGORIES_INFO');


        if ($this->search) {
            $categorys
                ->where(DB::raw('lower(catagories_name)'), 'like', '%' . strtolower($this->search) . '%')
                ;
        }

        return $categorys->orderBy('tran_mst_id', 'DESC')
            ->paginate($this->pagination);
    }


    public function updatingSearch()
    {
        $this->resetPage();
    }

    #[On('refresh-product-category')]
    public function refreshresultCategory(){
        $this->resultCategory();
    }


    #[On('product-category-edit-modal')]
    public function modalEditTitle(){
        $this->modal_title = 'Update';
    }
    public function render()
    {
        return view('livewire.dashboard.product.category.product-category')->title('Product category');
    }
}