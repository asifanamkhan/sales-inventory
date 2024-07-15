<?php

namespace App\Livewire\Dashboard\Product\Brand;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class ProductBrand extends Component
{
    use WithPagination;

    public $modal_title;
    public $event = 'product-brand';
    public $search;
    public $pagination = 10;


    #[Computed]
    public function resultBrand()
    {
        $brands = DB::table('INV_ST_BRAND_INFO');


        if ($this->search) {
            $brands
                ->where(DB::raw('lower(brand_name)'), 'like', '%' . strtolower($this->search) . '%');
        }

        return $brands->orderBy('brand_code', 'DESC')
            ->paginate($this->pagination);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    #[On('refresh-product-brand')]
    public function refreshBrand()
    {
        $this->resultBrand();
    }

    #[On('create-product-brand-modal')]
    public function modalCreateTitle()
    {
        $this->modal_title = 'Create new product brand';
    }

    #[On('product-brand-edit-modal')]
    public function modalEditTitle()
    {
        $this->modal_title = 'Update product brand';
    }

    public function render()
    {
        return view('livewire.dashboard.product.brand.product-brand')->title('Brand');
    }
}