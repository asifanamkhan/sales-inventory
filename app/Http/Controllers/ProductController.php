<?php

namespace App\Http\Controllers;

use App\Service\CustomTcPDFHF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function productList()
    {
        $products = DB::table('VW_INV_ITEM_DETAILS')->get();
        $data = [
            'products' => $products
        ];

        $html = view()->make('livewire.dashboard.reports.product.product-list', $data)->render();

        $pdf_data = [
            'html' => $html,
            'filename' => 'product-info.pdf',
        ];
        $this->PDFGenerate($pdf_data);
    }

    public function purchaseReport(Request $request)
    {
        $query = DB::table('VW_PRODUCT_PURCHASE_REPORT');

        if ($request->start_date) {
            $query->where('tran_date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->where('tran_date', '<=', $request->end_date);
        }
        if ($request->st_group_item_id) {
            $query->where('st_group_item_id', $request->st_group_item_id);
        }
        if ($request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }
        if ($request->catagories_id) {
            $query->where('catagories_id', $request->catagories_id);
        }
        $products = $query->get();

        $data = [
            'ledgers' => $products
        ];

        $html = view()->make('livewire.dashboard.reports.product.pdf.product-purchase-report-pdf', $data)->render();

        $pdf_data = [
            'html' => $html,
            'filename' => 'product-purchase.pdf',
        ];
        $this->PDFGenerate($pdf_data);

    }

    public function purchaseReturnReport(Request $request){

        $query = DB::table('VW_PRODUCT_PURCHASE_RETURN');

        if ($request->start_date) {
            $query->where('return_date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->where('return_date', '<=', $request->end_date);
        }
        if ($request->st_group_item_id) {
            $query->where('st_group_item_id', $request->st_group_item_id);
        }
        if ($request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }
        if ($request->catagories_id) {
            $query->where('catagories_id', $request->catagories_id);
        }
        $products = $query->get();

        $data = [
            'ledgers' => $products
        ];

        $html = view()->make('livewire.dashboard.reports.product.pdf.product-purchase-return-report-pdf', $data)->render();

        $pdf_data = [
            'html' => $html,
            'filename' => 'product-purchase.pdf',
        ];
        $this->PDFGenerate($html,  $pdf_data);
    }



    public function purchaseDamageReport(Request $request){
          $query = DB::table('VW_PRODUCT_DAMAGE_REPORT');
        if ($request->start_date) {
            $query->where('damage_date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->where('damage_date', '<=', $request->end_date);
        }
        if ($request->st_group_item_id) {
            $query->where('st_group_item_id', $request->st_group_item_id);
        }
        if ($request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }
        if ($request->catagories_id) {
            $query->where('catagories_id', $request->catagories_id);
        }
        $products = $query->get();

        $data = [
            'ledgers' => $products
        ];

        $html = view()->make('livewire.dashboard.reports.product.pdf.product-damage-report-pdf', $data)->render();

        $pdf_data = [
            'html' => $html,
            'filename' => 'product-damage.pdf',
        ];

        $this->PDFGenerate($html,  $pdf_data);
    }

    public function purchaseExpireReport(Request $request){

         $query = DB::table('VW_PRODUCT_EXPIRE_REPORT');
        if ($request->start_date) {
            $query->where('expire_date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->where('expire_date', '<=', $request->end_date);
        }
        if ($request->st_group_item_id) {
            $query->where('st_group_item_id', $request->st_group_item_id);
        }
        if ($request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }
        if ($request->catagories_id) {
            $query->where('catagories_id', $request->catagories_id);
        }
        $products = $query->get();

        $data = [
            'ledgers' => $products
        ];

        $html = view()->make('livewire.dashboard.reports.product.pdf.product-expire-report-pdf', $data)->render();

        $pdf_data = [
            'html' => $html,
            'filename' => 'product-expire.pdf',
        ];

        $this->PDFGenerate($pdf_data);
    }

    public function purchaseStockReport(Request $request){
        $query = DB::table('VW_INV_ITEM_STOCK_QTY');

        if ($request->st_group_item_id) {
            $query->where('st_group_item_id', $request->st_group_item_id);
        }
        if ($request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }
        if ($request->catagories_id) {
            $query->where('catagories_id', $request->catagories_id);
        }
        $products = $query->get();

        $data = [
            'ledgers' => $products
        ];

        $html = view()->make('livewire.dashboard.reports.product.pdf.product-stock-report-pdf', $data)->render();

        $pdf_data = [
            'html' => $html,
            'filename' => 'product-stock.pdf',
        ];
        $this->PDFGenerate($pdf_data);

   }


   public function PDFGenerate($data){
    
    $pdf = new CustomTcPDFHF();
    // $pdf = new CustomTcPDFHF('L', 'pt', ['format' => 'A4']);

    // Set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor(Auth::user()->name);

    // Set margins
    $pdf->SetMargins(10, 52, 10);
    $pdf->SetHeaderMargin(3);
    $pdf->SetFooterMargin(3);

    // Set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    $pdf->AddPage();

    $pdf->writeHTML($data['html'], true, false, true, false, '');

    $pdf->Output(public_path($data['filename']), 'I');
}
}