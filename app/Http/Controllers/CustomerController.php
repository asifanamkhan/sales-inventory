<?php

namespace App\Http\Controllers;

use App\Service\CustomTcPDFHF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function customerInfo()
    {
        $filename = 'customer info';
        $customers = DB::table('VW_INV_CUSTOMER_INFO')->get();
        $data = [
            'customers' => $customers
        ];

        $html = view()->make('livewire.dashboard.reports.customer.customer-info', $data)->render();

        $pdf = new CustomTcPDFHF();

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(Auth::user()->name);
        $pdf->SetTitle('Customer Info');

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

    public function customerLedgerPdf($code)
    {
        $filename = 'customer-ledger.pdf';
        $ledgers = DB::table('VW_INV_CUSTOMER_PAYMENT_LEDGER')
            ->where('customer_id', $code)
            ->get();

        $data = [
            'ledgers' => $ledgers
        ];

        $html = view()->make('livewire.dashboard.reports.customer.customer-ledger-pdf', $data)->render();

        $pdf = new CustomTcPDFHF();

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(Auth::user()->name);
        $pdf->SetTitle('Customer Ledger');

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