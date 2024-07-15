<?php

namespace App\Livewire\Dashboard\ProductDamage;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;

class ProductDamage extends Component
{
    use WithPagination;
    #[Title('Product damage')]

    public $search;
    public $pagination = 10;


    #[Computed]
    public function resultDamage()
    {
        $purchases = DB::table('INV_REJECT_MST as p');

        $purchases
            ->orderBy('p.tran_mst_id', 'DESC')
            ->select(['p.*']);

        if ($this->search) {
            $purchases
                ->where(DB::raw('lower(p.memo_no)'), 'like', '%' . strtolower($this->search) . '%');
        }
        // $p =   $purchases->get();
        // dd($p);

        return $purchases->paginate($this->pagination);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        return view('livewire.dashboard.product-damage.product-damage');
    }
}