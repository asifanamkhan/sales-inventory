<?php

namespace App\Livewire\Dashboard\Purchase\Purchase;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;


class PurchaseDetails extends Component
{
    public $purchase_mst;
    public $purchase_dtl;
    public $purchase_id;
    public $payment_info;

    public function render()
    {
        return view('livewire.dashboard.purchase.purchase.purchase-details');
    }
    public function mount($purchase_id)
    {
        $this->purchase_id = $purchase_id;
        $this->purchase_mst = DB::table('INV_PURCHASE_MST as p')
            ->where('p.tran_mst_id', $purchase_id)
            ->leftJoin('INV_SUPPLIER_INFO as s', function ($join) {
                $join->on('s.p_code', '=', 'p.p_code');
            })
            ->leftJoin('INV_WAREHOUSE_INFO as w', function ($join) {
                $join->on('w.war_id', '=', 'p.war_id');
            })
            ->first(['p.*', 's.p_name', 'w.war_name']);

        $this->purchase_dtl = DB::table('INV_PURCHASE_DTL as p')
            ->where('p.tran_mst_id', $purchase_id)
            ->leftJoin('VW_INV_ITEM_DETAILS as pr', function ($join) {
                $join->on('pr.st_group_item_id', '=', 'p.item_code');
            })
            ->get([
                'p.pr_rate',
                'p.expire_date',
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
        ->where('p.ref_memo_no', $this->purchase_mst->memo_no)
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

    public function print()
    {
        // dd(1);
        $pdf = Pdf::loadHTML('<h1>Hello world!</h1>');

        return response()->streamDownload(function () use($pdf) {
            echo  $pdf->stream();
        }, 'report.pdf');


    }
}