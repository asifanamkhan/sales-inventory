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
        $branchs = DB::table('INV_ST_GROUP_INFO');


        if ($this->search) {
            $branchs
                ->where(DB::raw('lower(group_name)'), 'like', '%' . strtolower($this->search) . '%')
                ;
        }

        return $branchs->orderBy('st_group_id', 'DESC')
            ->paginate($this->pagination);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    #[On('refresh-product-group')]
    public function refreshBranch(){
        $this->resultGroup();
    }

    #[On('create-product-group-modal')]
    public function modalCreateTitle(){
        $this->modal_title = 'Create';
    }

    #[On('product-group-edit-modal')]
    public function modalEditTitle(){
        $this->modal_title = 'Update';
    }

    public function render()
    {
        return view('livewire.dashboard.product.group.product-group');
    }
}
