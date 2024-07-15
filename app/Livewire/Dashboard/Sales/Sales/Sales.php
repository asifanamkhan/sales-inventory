<?php

namespace App\Livewire\Dashboard\Sales\Sales;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Sales extends Component
{
    use WithPagination;

    public $search;
    public $pagination = 10;


    #[Computed]
    public function resultSale()
    {
        $sales = DB::table('INV_SALES_MST as p');

        $sales
            ->orderBy('p.tran_mst_id', 'DESC')
            ->leftJoin('INV_CUSTOMER_INFO as s', function ($join) {
                $join->on('s.customer_id', '=', 'p.customer_id');
            })
            ->select(['p.*','s.customer_name']);

            if ($this->search) {
                $sales
                    ->where(DB::raw('lower(p.memo_no)'), 'like', '%' . strtolower($this->search) . '%');
            }
            // $p =   $sales->get();
            // dd($p);

        return $sales->paginate($this->pagination);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        return view('livewire.dashboard.sales.sales.sales')->title('Sales');
    }
}