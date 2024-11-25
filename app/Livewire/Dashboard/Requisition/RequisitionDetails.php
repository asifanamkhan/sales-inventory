<?php

namespace App\Livewire\Dashboard\Requisition;

use Illuminate\Support\Facades\DB;
use Livewire\Component;


class RequisitionDetails extends Component
{
    public $requisition_mst;
    public $requisition_dtl;
    public $requisition_id;
    public $payment_info;

    public function mount($requisition_id)
    {
        $this->requisition_id = $requisition_id;
        $this->requisition_mst = DB::table('INV_REQUISTION_MST as p')
            ->where('p.tran_mst_id', $requisition_id)
            ->leftJoin('INV_SUPPLIER_INFO as s', function ($join) {
                $join->on('s.p_code', '=', 'p.p_code');
            })
            ->first(['p.*', 's.p_name']);

        $this->requisition_dtl = DB::table('INV_REQUISTION_DTL as p')
            ->where('p.tran_mst_id', $requisition_id)
            ->leftJoin('VW_INV_ITEM_DETAILS as pr', function ($join) {
                $join->on('pr.st_group_item_id', '=', 'p.item_code');
            })
            ->get([
                'p.pr_rate',
                'p.vat_amt',
                'p.tot_payble_amt',
                'p.item_qty',
                'p.discount',
                'p.item_code',
                'pr.item_name',
                'pr.color_name',
                'pr.item_size_name',
                'pr.vat_amt as p_vat_amt'
            ]);

        $this->payment_info = DB::table('ACC_PAYMENT_INFO as p')
        ->where('p.ref_memo_no', $this->requisition_mst->memo_no)
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
        return view('livewire.dashboard.requisition.requisition-details');
    }
}