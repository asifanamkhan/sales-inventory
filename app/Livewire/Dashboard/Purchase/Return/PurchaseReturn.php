<?php

namespace App\Livewire\Dashboard\Purchase\Return;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class PurchaseReturn extends Component
{
    use WithPagination;

    public $search;
    public $pagination = 10;
    public $purchaseGrantAmt = 0;
    public $purchasePaidAmt = 0;
    public $purchaseDueAmt = 0;
    public $rt_total = 0;
    public $received_total = 0;
    public $due_total = 0;
    public $searchDate, $firstFilterDate, $lastFilterDate;


    #[Computed]
    #[On('purchase-return-all')]
    public function resultPurchaseReturn()
    {
        $purchases = DB::table('INV_PURCHASE_RET_MST as p');

        $purchases
            ->orderBy('p.tran_mst_id', 'DESC')
            ->leftJoin('INV_SUPPLIER_INFO as s', function ($join) {
                $join->on('s.p_code', '=', 'p.p_code');
            })
            ->select(['p.*','s.p_name']);

            if ($this->search) {
                $purchases
                    ->where(DB::raw('lower(p.memo_no)'), 'like', '%' . strtolower($this->search) . '%');
            }

            if ($this->firstFilterDate) {
                $purchases->where('p.tran_date', '>=', $this->firstFilterDate);
            }

            if ($this->lastFilterDate) {
                $purchases->where('p.tran_date', '<=', $this->lastFilterDate);
            }
            // $p =   $purchases->get();
            // dd($p);

        return $purchases->paginate($this->pagination);
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
        $amt = DB::table('INV_PURCHASE_RET_MST as p')
            ->select(
                DB::raw('SUM(tot_payable_amt) AS tot_payable_amt'),
                DB::raw('SUM(tot_paid_amt) AS tot_paid_amt'),
                DB::raw('SUM(tot_due_amt) AS tot_due_amt'),
            )
            ->first();

        $this->purchaseGrantAmt = $amt->tot_payable_amt;
        $this->purchasePaidAmt = $amt->tot_paid_amt;
        $this->purchaseDueAmt = ($this->purchaseGrantAmt - $this->purchasePaidAmt);
    }
    public function render()
    {
        return view('livewire.dashboard.purchase.return.purchase-return')->title('Purchase return');
    }
}