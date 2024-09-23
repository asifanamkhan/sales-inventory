<?php

namespace App\Livewire\Dashboard\Purchase\Purchase;

use App\Service\Payment;
use Carbon\Carbon;
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
    public $grand_total = 0;
    public $rt_total = 0;
    public $paid_total = 0;
    public $due_total = 0;
    public $purchaseGrantAmt = 0;
    public $purchasePaidAmt = 0;
    public $purchaseRtAmt = 0;
    public $purchaseDueAmt = 0;
    public $selectRows = [];
    public $selectPageRows = false;

    public $searchMemo, $searchSupplier, $searchStatus,
        $searchPayStatus, $searchDate, $firstFilterDate, $lastFilterDate;


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
            ->select(['p.*', 's.p_name', 'sr.tot_payable_amt as rt_amt']);

        if ($this->search) {
            $purchases
                ->orwhere(DB::raw('lower(p.memo_no)'), 'like', '%' . strtolower($this->search) . '%')
                ->orWhere('p.tot_payable_amt', 'like', '%' . $this->search . '%')
                ->orWhere('p.tot_paid_amt', 'like', '%' . $this->search . '%');
        }
        if ($this->searchMemo) {
            $purchases->where(DB::raw('lower(p.memo_no)'), 'like', '%' . strtolower($this->searchMemo) . '%');
        }
        if ($this->searchSupplier) {
            $purchases->where(DB::raw('lower(s.p_name)'), 'like', '%' . strtolower($this->searchSupplier) . '%');
        }

        if ($this->searchStatus) {
            $purchases->where('p.status', $this->searchStatus);
        }
        if ($this->searchPayStatus) {
            $purchases->where('p.payment_status', $this->searchPayStatus);
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

    public function updatedSelectPageRows()
    {
        if ($this->selectPageRows) {
            $this->selectRows = $this->resultPurchase->pluck('tran_mst_id')->toArray();
        } else {
            $this->selectRows = [];
        }
    }

    public function mount()
    {
        $amt = DB::table('INV_PURCHASE_MST as p')
            ->select(DB::raw('SUM(tot_payable_amt) AS tot_payable_amt'), DB::raw('SUM(tot_paid_amt) AS tot_paid_amt'))
            ->first();

        $this->purchaseRtAmt = DB::table('INV_PURCHASE_RET_MST as p')
            ->sum('tot_payable_amt');

        $this->purchaseGrantAmt = $amt->tot_payable_amt;
        $this->purchasePaidAmt = $amt->tot_paid_amt;
        $this->purchaseDueAmt = Payment::dueAmount($this->purchaseGrantAmt, $this->purchaseRtAmt, $this->purchasePaidAmt);
    }

    public function render()
    {
        return view('livewire.dashboard.purchase.purchase.purchase')
            ->title('Purchase');
    }
}