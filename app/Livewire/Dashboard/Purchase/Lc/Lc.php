<?php

namespace App\Livewire\Dashboard\Purchase\Lc;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Lc extends Component
{
    use WithPagination;

    public $search;
    public $pagination = 10;
    public $grand_total = 0;
    public $paid_total = 0;
    public $lcGrantAmt = 0;
    public $lcPaidAmt = 0;
    public $selectRows = [];
    public $selectPageRows = false;


    #[Computed]
    #[On('lc-all')]
    public function resultLC()
    {
        $lcs = DB::table('INV_LC_DETAILS as p');

        $lcs
            ->orderBy('p.tran_mst_id', 'DESC')
            ->select(['p.*',]);

        if ($this->search) {
            $lcs
                ->orwhere(DB::raw('lower(p.memo_no)'), 'like', '%' . strtolower($this->search) . '%')
                ->orWhere('p.lc_amount', 'like', '%' . $this->search . '%')
                ->orWhere('p.issue_date', 'like', '%' . $this->search . '%')
                ->orWhere('p.applicant', 'like', '%' . $this->search . '%');
        }
        // $p =   $lcs->get();
        // dd($p);

        return $lcs->paginate($this->pagination);
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
        // $amt = DB::table('INV_LC_DETAILS as p')
        //     ->select(
        //         DB::raw('SUM(lc_amount) AS lc_amount'),
        //     )
        //     ->first();


        // $this->lcGrantAmt = $amt->lc_amount;
    }
    public function render()
    {
        return view('livewire.dashboard.purchase.lc.lc');
    }
}