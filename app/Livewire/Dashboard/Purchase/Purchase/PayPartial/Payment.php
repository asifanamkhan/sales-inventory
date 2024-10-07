<?php

namespace App\Livewire\Dashboard\Purchase\Purchase\PayPartial;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

class Payment extends Component
{
    use WithPagination;

    public $search;
    public $pagination = 10;
    public $payment_methods,$purchase_mst,$purchaseRtAmt;
    public $purchase_id;
    public $paymentState = [];

    public function paymentMethodAll()
    {
        return $this->payment_methods = DB::table('ACC_PAYMENT_MODE')
            ->get(['p_mode_id', 'p_mode_name']);
    }
    public function render()
    {
        return view('livewire.dashboard.purchase.purchase.pay-partial.payment');
    }

    #[On('purchase-payment')]
    public function purchasePayment($id){
        $this->purchase_id = $id;

        $this->purchase_mst = DB::table('INV_PURCHASE_MST as p')
            ->where('p.tran_mst_id', $this->purchase_id)
            ->first(['p.memo_no','p.tot_payable_amt','p.tot_paid_amt','p.tot_due_amt']);

        $this->purchaseRtAmt = DB::table('INV_PURCHASE_RET_MST as p')
                ->where('p.ref_memo_no', $this->purchase_mst->memo_no)
                ->first();

    }

    #[Computed]
    public function resultPayments()
    {
        $payments = DB::table('ACC_PAYMENT_INFO as p')
        ->where('p.tran_mst_id', $this->purchase_id)
        ->leftJoin('ACC_PAYMENT_MODE as pm', function ($join) {
            $join->on('pm.p_mode_id', '=', 'p.pay_mode');
        });

        $payments
            ->orderBy('p.tran_mst_id', 'DESC')
            ->select([
                'p.*','pm.p_mode_name'
            ]);

        if ($this->search) {
            $payments
                ->orwhere(DB::raw('lower(p.memo_no)'), 'like', '%' . strtolower($this->search) . '%')
                ->orWhere('p.tot_payable_amt', 'like', '%' . $this->search . '%')
                ->orWhere('p.tot_paid_amt', 'like', '%' . $this->search . '%');
        }



        return $payments->paginate($this->pagination);
    }


    public function mount(){

        $this->paymentState['pay_mode'] = 1;
        $this->paymentMethodAll();


    }
}
