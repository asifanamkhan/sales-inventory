<?php

namespace App\Livewire\Dashboard\Product\Color;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class ProductColor extends Component
{
    use WithPagination;

    public $modal_title;
    public $event = 'product-color';
    public $search;
    public $pagination = 10;


    #[Computed]
    public function resultColor()
    {
        $colors = DB::table('INV_COLOR_INFO');

        if ($this->search) {
            $colors
                ->where(DB::raw('lower(color_name)'), 'like', '%' . strtolower($this->search) . '%');
        }

        return $colors->orderBy('tran_mst_id', 'DESC')
            ->paginate($this->pagination);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    #[On('refresh-product-color')]
    public function refreshColor()
    {
        $this->resultcolor();
    }

    #[On('create-product-color-modal')]
    public function modalCreateTitle()
    {
        $this->modal_title = 'Create product color';
    }

    #[On('product-color-edit-modal')]
    public function modalEditTitle()
    {
        $this->modal_title = 'Update product color';
    }
    public function render()
    {
        return view('livewire.dashboard.product.color.product-color');
    }
}
