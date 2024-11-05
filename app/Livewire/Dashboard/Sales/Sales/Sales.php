<?php

namespace App\Livewire\Dashboard\Sales\Sales;

use App\Service\Payment;
use Carbon\Carbon;
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
    public $grand_total = 0;
    public $rt_total = 0;
    public $paid_total = 0;
    public $due_total = 0;
    public $saleGrantAmt = 0;
    public $salePaidAmt = 0;
    public $saleRtAmt = 0;
    public $saleDueAmt = 0;
    public $selectRows = [];
    public $selectPageRows = false;

    public $searchMemo, $searchSupplier, $searchStatus,
        $searchPayStatus, $searchDate, $firstFilterDate, $lastFilterDate;


    #[Computed]
    #[On('sale-all')]
    public function resultSale()
    {
        $sales = DB::table('INV_SALES_MST as p');

        $sales
            ->orderBy('p.tran_mst_id', 'DESC')
            ->leftJoin('INV_CUSTOMER_INFO as s', function ($join) {
                $join->on('s.customer_id', '=', 'p.customer_id');
            })
            ->select(['p.*', 's.customer_name as p_name']);

        if ($this->search) {
            $sales
                ->orwhere(DB::raw('lower(p.memo_no)'), 'like', '%' . strtolower($this->search) . '%')
                ->orWhere('p.tot_payable_amt', 'like', '%' . $this->search . '%')
                ->orWhere('p.tot_paid_amt', 'like', '%' . $this->search . '%');
        }
        if ($this->searchMemo) {
            $sales->where(DB::raw('lower(p.memo_no)'), 'like', '%' . strtolower($this->searchMemo) . '%');
        }
        if ($this->searchSupplier) {
            $sales->where(DB::raw('lower(s.p_name)'), 'like', '%' . strtolower($this->searchSupplier) . '%');
        }

        if ($this->searchStatus) {
            $sales->where('p.status', $this->searchStatus);
        }
        if ($this->searchPayStatus) {
            $sales->where('p.payment_status', $this->searchPayStatus);
        }

        if ($this->firstFilterDate) {
            $sales->where('p.tran_date', '>=', $this->firstFilterDate);
        }

        if ($this->lastFilterDate) {
            $sales->where('p.tran_date', '<=', $this->lastFilterDate);
        }

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

    public function updatedSelectPageRows()
    {
        if ($this->selectPageRows) {
            $this->selectRows = $this->resultSale->pluck('tran_mst_id')->toArray();
        } else {
            $this->selectRows = [];
        }
    }
    #[On('sale-all')]
    public function grandCal(){
        $amt = DB::table('INV_SALES_MST as p')
            ->select(
                DB::raw('SUM(tot_payable_amt) AS tot_payable_amt'),
                DB::raw('SUM(tot_paid_amt) AS tot_paid_amt'),
                DB::raw('SUM(prt_amt) AS tot_prt_amt'),
                DB::raw('SUM(tot_due_amt) AS tot_due_amt'),
            )
            ->first();

        $this->saleRtAmt = $amt->tot_prt_amt;
        $this->saleGrantAmt = $amt->tot_payable_amt;
        $this->salePaidAmt = $amt->tot_paid_amt;
        $this->saleDueAmt = Payment::dueAmount($this->saleGrantAmt, $this->saleRtAmt, $this->salePaidAmt);
    }
    public function mount()
    {
        $this->grandCal();
    }
    public function render()
    {
        return view('livewire.dashboard.sales.sales.sales')->title('Sales');
    }
}