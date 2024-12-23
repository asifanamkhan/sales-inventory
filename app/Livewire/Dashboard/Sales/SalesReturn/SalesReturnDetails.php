<?php

namespace App\Livewire\Dashboard\Sales\SalesReturn;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SalesReturnDetails extends Component
{
    public $sale_return_mst;
    public $sale_return_dtl;
    public $sale_return_id;
    public $payment_info;

    public function mount($sale_return_id)
    {
        $this->sale_return_id = $sale_return_id;
        $this->sale_return_mst = DB::table('INV_SALES_RET_MST as p')
            ->where('p.tran_mst_id', $sale_return_id)
            ->leftJoin('INV_CUSTOMER_INFO as s', function ($join) {
                $join->on('s.customer_id', '=', 'p.customer_id');
            })
            ->leftJoin('INV_WAREHOUSE_INFO as w', function ($join) {
                $join->on('w.war_id', '=', 'p.war_id');
            })
            ->first(['p.*', 's.customer_name as p_name', 'w.war_name']);

        $this->sale_return_dtl = DB::table('INV_SALES_RET_DTL as p')
            ->where('p.tran_mst_id', $sale_return_id)
            ->leftJoin('VW_INV_ITEM_DETAILS as pr', function ($join) {
                $join->on('pr.st_group_item_id', '=', 'p.item_code');
            })
            ->get([
                'p.mrp_rate',
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
            ->where('p.ref_memo_no', $this->sale_return_mst->memo_no)
            ->where('p.tran_mst_id', $this->sale_return_id)
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

        // dd($this->sale_return_dtl);
    }
    public function render()
    {
        return view('livewire.dashboard.sales.sales-return.sales-return-details');
    }
}