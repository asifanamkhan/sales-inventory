<?php

namespace App\Http\Controllers;

use App\Service\CustomTcPDFHF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function supplierInfo()
    {
        $filename = 'supplier info';
        $suppliers = DB::table('VW_INV_SUPPLIER_INFO')->get();
        $data = [
            'suppliers' => $suppliers
        ];

        $html = view()->make('livewire.dashboard.reports.supplier.supplier-info', $data)->render();

        $pdf = new CustomTcPDFHF();

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(Auth::user()->name);
        $pdf->SetTitle('Supplier Info');

        // Set margins
        $pdf->SetMargins(10, 52, 10);
        $pdf->SetHeaderMargin(3);
        $pdf->SetFooterMargin(3);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        $pdf->AddPage();

        $pdf->writeHTML($html, true, false, true, false, '');

        $pdf->Output(public_path($filename), 'I');
    }
}