<?php

namespace App\Livewire\Dashboard\Product\Group;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class ProductGroup extends Component
{
    use WithPagination;

    public $modal_title;
    public $event = 'product-group';
    public $search;
    public $pagination = 10;


    #[Computed]
    public function resultGroup()
    {
        $groups = DB::table('INV_ST_GROUP_INFO');


        if ($this->search) {
            $groups
                ->where(DB::raw('lower(group_name)'), 'like', '%' . strtolower($this->search) . '%');
        }

        return $groups->orderBy('st_group_id', 'DESC')
            ->paginate($this->pagination);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    #[On('refresh-product-group')]
    public function refreshGroup()
    {
        $this->resultGroup();
    }

    #[On('create-product-group-modal')]
    public function modalCreateTitle()
    {
        $this->modal_title = 'Create new product group';
    }

    #[On('product-group-edit-modal')]
    public function modalEditTitle()
    {
        $this->modal_title = 'Update product group';
    }

    public function render()
    {
        return view('livewire.dashboard.product.group.product-group');
    }
}