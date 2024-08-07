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
            ->orderBy('p.tran_mst_id', 'DESC')
            ->leftJoin('INV_SUPPLIER_INFO as s', function ($join) {
                $join->on('s.p_code', '=', 'p.p_code');
            })
            ->leftJoin('INV_PURCHASE_RET_MST as sr', function ($join) {
                $join->on('sr.ref_memo_no', '=', 'p.memo_no');
            })
            ->select(['p.*','s.p_name','sr.tot_payable_amt as rt_amt']);

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
        return view('livewire.dashboard.purchase.purchase.purchase')->title('Purchase');
    }
}