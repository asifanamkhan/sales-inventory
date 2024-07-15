<?php

namespace App\Livewire\Dashboard\Sales\SalesReturn;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class SalesReturn extends Component
{
    use WithPagination;

    public $search;
    public $pagination = 10;


    #[Computed]
    public function resultSaleReturn()
    {
        $purchases = DB::table('INV_SALES_RET_MST as p');

        $purchases
            ->orderBy('p.tran_mst_id', 'DESC')
            ->leftJoin('INV_CUSTOMER_INFO as s', function ($join) {
                $join->on('s.customer_id', '=', 'p.customer_id');
            })
            ->select(['p.*','s.customer_name']);

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
        return view('livewire.dashboard.sales.sales-return.sales-return')->title('Sales return');
    }
}