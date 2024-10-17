<?php

namespace App\Http\Controllers;

use App\Service\CustomTcPDFHF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function productList(){
        $filename = 'product-info.pdf';
        $products = DB::table('VW_INV_ITEM_DETAILS')->get();
        $data = [
            'products' => $products
        ];

        $html = view()->make('livewire.dashboard.reports.product.product-list', $data)->render();

        $pdf = new CustomTcPDFHF();
        // $pdf = new CustomTcPDFHF('L', 'pt', ['format' => 'A4']);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(Auth::user()->name);
        $pdf->SetTitle('Product Info');

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