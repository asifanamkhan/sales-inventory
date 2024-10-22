<?php

namespace App\Livewire\Dashboard\Expense;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ExpenseDetails extends Component
{
    public $expense_mst;
    public $expense_dtl;
    public $expense_id;
    public $payment_info;


    public function mount($expense_id)
    {
        $this->expense_id = $expense_id;
        $this->expense_mst = DB::table('ACC_EXPENSE_MST as p')
            ->where('p.expense_mst_id', $expense_id)
            ->leftJoin('ACC_EXPENSES_LIST as s', function ($join) {
                $join->on('s.expense_id', '=', 'p.expense_type');
            })
            ->first(['p.*', 's.expense_type as p_name']);

        $this->expense_dtl = DB::table('ACC_EXPENSE_DTLS as p')
            ->where('p.expense_mst_id', $expense_id)
            ->get();

        $this->payment_info = DB::table('ACC_PAYMENT_INFO as p')
        ->where('p.ref_memo_no', $this->expense_mst->memo_no)
            ->leftJoin('ACC_PAYMENT_MODE as pm', function ($join) {
                $join->on('pm.p_mode_id', '=', 'p.pay_mode');
            })
            ->get([
                'p.tran_mst_id',
                'p.bank_code',
                'p.memo_no',
                'p.payment_date',
                'p.pay_mode',
                'pm.p_mode_name',
                'p.mfs_id',
                'p.mfs_acc_no',
                'p.bank_code',
                'p.bank_ac_no',
                'p.chq_no',
                'p.chq_date',
                'p.card_no',
                'p.online_trx_id',
                'p.online_trx_dt',
                'p.user_id',
                'p.tot_paid_amt',
            ]);

        // dd($this->payment_info);
    }

    public function render()
    {
        return view('livewire.dashboard.expense.expense-details');
    }
}