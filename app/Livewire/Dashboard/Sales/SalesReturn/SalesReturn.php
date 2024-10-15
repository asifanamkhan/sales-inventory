<?php

namespace App\Livewire\Dashboard\Sales\SalesReturn;

use Carbon\Carbon;
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
    public $saleGrantAmt = 0;
    public $salePaidAmt = 0;
    public $saleDueAmt = 0;
    public $rt_total = 0;
    public $paid_total = 0;
    public $due_total = 0;
    public $searchDate, $firstFilterDate, $lastFilterDate;


    #[Computed]
    #[On('sale-return-all')]
    public function resultSaleReturn()
    {
        $sales = DB::table('INV_SALES_RET_MST as p');

        $sales
            ->orderBy('p.tran_mst_id', 'DESC')
            ->leftJoin('INV_CUSTOMER_INFO as s', function ($join) {
                $join->on('s.customer_id', '=', 'p.customer_id');
            })
            ->select(['p.*','s.customer_name as p_name']);

            if ($this->search) {
                $sales
                    ->where(DB::raw('lower(p.memo_no)'), 'like', '%' . strtolower($this->search) . '%');
            }

            if ($this->firstFilterDate) {
                $sales->where('p.tran_date', '>=', $this->firstFilterDate);
            }

            if ($this->lastFilterDate) {
                $sales->where('p.tran_date', '<=', $this->lastFilterDate);
            }
            // $p =   $sales->get();
            // dd($p);

        return $sales->paginate($this->pagination);
    }

    public function dateFilter()
    {
        $dates = explode('-', $this->searchDate);
        $this->firstFilterDate = Carbon::parse($dates[0])->format('Y-m-d');
        $this->lastFilterDate = Carbon::parse($dates[1])->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount(){
        $amt = DB::table('INV_SALES_RET_MST as p')
            ->select(
                DB::raw('SUM(tot_payable_amt) AS tot_payable_amt'),
                DB::raw('SUM(tot_paid_amt) AS tot_paid_amt'),
                DB::raw('SUM(tot_due_amt) AS tot_due_amt'),
            )
            ->first();

        $this->saleGrantAmt = $amt->tot_payable_amt;
        $this->salePaidAmt = $amt->tot_paid_amt;
        $this->saleDueAmt = ($this->saleGrantAmt - $this->salePaidAmt);
    }
    public function render()
    {
        return view('livewire.dashboard.sales.sales-return.sales-return')->title('Sales return');
    }
}