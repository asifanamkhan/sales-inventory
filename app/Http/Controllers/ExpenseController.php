<?php

namespace App\Http\Controllers;

use App\Service\GeneratePdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function invoice($expense_mst_id)
    {
        $mst = DB::table('ACC_EXPENSE_MST as p')
            ->where('expense_mst_id', $expense_mst_id)
            ->leftJoin('ACC_EXPENSES_LIST as s', function ($join) {
                $join->on('s.expense_id', '=', 'p.expense_type');
            })
            ->select(['p.*', 's.expense_type as p_name'])
            ->first();

        $dtl = DB::table('ACC_EXPENSE_DTLS')
            ->where('expense_mst_id', $expense_mst_id)
            ->get();


        $data = [
            'ledgers' => $dtl,
            'mst' => $mst
        ];

        $html = view()->make('livewire.dashboard.reports.expense.pdf.invoice', $data)->render();

        $pdf_data = [
            'html' => $html,
            'filename' => 'product-return-sale.pdf',
        ];

        GeneratePdf::generate($pdf_data);
    }

    public function expenseReport(Request $request)
    {

        $query = DB::table('VW_ACC_EXPENSE_INFO');
        if ($request->start_date) {
            $query->where('expense_date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->where('expense_date', '<=', $request->end_date);
        }
        if ($request->expense_type) {
            $query->where('expense_type', $request->expense_type);
        }
        if ($request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }

        $products = $query->get();

        $data = [
            'ledgers' => $products
        ];

        $html = view()->make('livewire.dashboard.reports.expense.pdf.expense-report-pdf', $data)->render();

        $pdf_data = [
            'html' => $html,
            'filename' => 'expense-report.pdf',
        ];

        GeneratePdf::generate($pdf_data);
    }
}