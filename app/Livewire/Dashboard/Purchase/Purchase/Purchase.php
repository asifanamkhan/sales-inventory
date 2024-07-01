<?php

namespace App\Livewire\Dashboard\Purchase\Purchase;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Purchase extends Component
{
    use WithPagination;

    public $search;
    public $pagination = 10;


    #[Computed]
    public function resultPurchase()
    {
        $purchases = DB::table('INV_PURCHASE_MST as p');

        $purchases
            ->orderBy('tran_mst_id', 'DESC')

            ->select(['tran_mst_id','status','memo_no','tran_date','p_code']);

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
        return view('livewire.dashboard.purchase.purchase.purchase');
    }
}
