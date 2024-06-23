<?php

namespace App\Livewire\Dashboard\Hrm\Supplier;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Supplier extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search;
    public $pagination = 10;

    #[Computed]
    public function resultSupplier()
    {
        $branchs = DB::table('INV_SUPPLIER_INFO');


        if ($this->search) {
            $branchs
                ->where(DB::raw('lower(p_name)'), 'like', '%' . strtolower($this->search) . '%')
                ;
        }

        return $branchs->orderBy('p_code', 'DESC')
            ->paginate($this->pagination);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    #[On('refresh-branches')]
    public function refreshSupplier(){
        $this->resultSupplier();
    }
    public function render()
    {
        return view('livewire.dashboard.hrm.supplier.supplier');
    }
}
