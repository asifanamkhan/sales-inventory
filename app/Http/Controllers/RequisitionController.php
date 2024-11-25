<?php

namespace App\Http\Controllers;

use App\Service\GeneratePdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequisitionController extends Controller
{
    public function invoice($requisition_id)
    {
        $mst = DB::table('INV_REQUISTION_MST as p')
                ->where('p.tran_mst_id', $requisition_id)
                ->leftJoin('INV_SUPPLIER_INFO as s', function ($join) {
                    $join->on('s.p_code', '=', 'p.p_code');
                })
            ->first(['p.*', 's.p_name', 's.address', 's.phone']);


        $dtl = DB::table('INV_REQUISTION_DTL as p')
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
                'pr.unit_name',
                'pr.color_name',
                'pr.item_size_name',
                'pr.vat_amt as p_vat_amt'
            ]);


        $data = [
            'ledgers' => $dtl,
            'tran_mst' => $mst
        ];

        $html = view()->make('livewire.dashboard.reports.requisition.requisition-invoice', $data)->render();

        $pdf_data = [
            'html' => $html,
            'filename' => 'requisition-invoice.pdf',
        ];

        GeneratePdf::generate($pdf_data);
    }

}