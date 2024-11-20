<?php

namespace App\Livewire\Dashboard\Reports\Expense;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ExpenseReport extends Component
{
    public $expense_types, $branchs, $catagories, $ledgers = [];
    public $state = [];

    public function expense_typesAll()
    {
        return $this->expense_types = DB::table('ACC_EXPENSES_LIST')
            ->get();
    }

    public function branchAll()
    {
        return $this->branchs = DB::table('INV_BRANCH_INFO')
            ->orderBy('branch_id', 'DESC')
            ->get();
    }

    public function search()
    {

        $query = DB::table('VW_ACC_EXPENSE_INFO');
        if(@$this->state['start_date']){
            $query->where('expense_date', '>=', $this->state['start_date']);
        }
        if(@$this->state['end_date']){
            $query->where('expense_date', '<=', $this->state['end_date']);
        }
        if(@$this->state['expense_type']){
            $query->where('expense_type', $this->state['expense_type']);
        }
        if(@$this->state['branch_id']){
            $query->where('branch_id', $this->state['branch_id']);
        }

        $this->ledgers = $query->get();

        // dd($this->ledgers);

    }

    public function mount()
    {
        $this->expense_typesAll();
        $this->branchAll();
        $this->state['branch_id'] = '';
        $this->state['expense_type'] = '';
        $this->state['start_date'] = '';
        $this->state['end_date'] = '';


    }
    public function render()
    {
        return view('livewire.dashboard.reports.expense.expense-report');
    }
}