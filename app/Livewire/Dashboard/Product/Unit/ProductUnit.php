<?php

namespace App\Livewire\Dashboard\Product\Unit;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class ProductUnit extends Component
{
    use WithPagination;

    public $modal_title;
    public $event = 'product-unit';
    public $search;
    public $pagination = 10;


    #[Computed]
    public function resultUnit()
    {
        $units = DB::table('INV_ST_UNIT_CONVERT');


        if ($this->search) {
            $units
                ->where(DB::raw('lower(unit_name)'), 'like', '%' . strtolower($this->search) . '%');
        }

        return $units->orderBy('st_unit_convert_id', 'DESC')
            ->paginate($this->pagination);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    #[On('refresh-product-unit')]
    public function refreshUnit()
    {
        $this->resultunit();
    }

    #[On('create-product-unit-modal')]
    public function modalCreateTitle()
    {
        $this->modal_title = 'Create new product unit';
    }

    #[On('product-unit-edit-modal')]
    public function modalEditTitle()
    {
        $this->modal_title = 'Update product unit';
    }

    public function render()
    {
        return view('livewire.dashboard.product.unit.product-unit')->title('Unit');
    }
}
