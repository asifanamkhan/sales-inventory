<?php

namespace App\Livewire\Dashboard\Requisition;

use App\Service\Payment;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class RequisitionList extends Component
{
    use WithPagination;

    public $search;
    public $pagination = 10;
    public $grand_total = 0;
    public $paid_total = 0;
    public $selectPageRows = false;

    public $searchMemo, $searchSupplier, $searchStatus,
        $searchPayStatus, $searchDate, $firstFilterDate, $lastFilterDate;


    #[Computed]
    #[On('requisition-all')]
    public function resultRequisition()
    {
        $requisitions = DB::table('INV_REQUISTION_MST as p');

        $requisitions
            ->orderBy('p.tran_mst_id', 'DESC')
            ->leftJoin('INV_SUPPLIER_INFO as s', function ($join) {
                $join->on('s.p_code', '=', 'p.p_code');
            })
            ->select(['p.*', 's.p_name']);

        if ($this->search) {
            $requisitions
                ->orwhere(DB::raw('lower(p.memo_no)'), 'like', '%' . strtolower($this->search) . '%')
                ->orWhere('p.tot_payable_amt', 'like', '%' . $this->search . '%')
                ->orWhere('p.tot_paid_amt', 'like', '%' . $this->search . '%');
        }
        if ($this->searchMemo) {
            $requisitions->where(DB::raw('lower(p.memo_no)'), 'like', '%' . strtolower($this->searchMemo) . '%');
        }
        if ($this->searchSupplier) {
            $requisitions->where(DB::raw('lower(s.p_name)'), 'like', '%' . strtolower($this->searchSupplier) . '%');
        }

        if ($this->searchStatus) {
            $requisitions->where('p.status', $this->searchStatus);
        }
        if ($this->searchPayStatus) {
            $requisitions->where('p.payment_status', $this->searchPayStatus);
        }

        if ($this->firstFilterDate) {
            $requisitions->where('p.tran_date', '>=', $this->firstFilterDate);
        }

        if ($this->lastFilterDate) {
            $requisitions->where('p.tran_date', '<=', $this->lastFilterDate);
        }


        // $p =   $requisitions->get();
        // dd($p);

        return $requisitions->paginate($this->pagination);
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


    public function mount()
    {

    }
    public function render()
    {
        return view('livewire.dashboard.requisition.requisition-list');
    }
}