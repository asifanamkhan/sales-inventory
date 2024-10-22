<?php

namespace App\Livewire\Dashboard\Expense;

use App\Service\Payment;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;


class Expense extends Component
{
    use WithPagination;

    public $search;
    public $pagination = 10;
    public $grand_total = 0;
    public $paid_total = 0;
    public $due_total = 0;
    public $expenseGrantAmt = 0;
    public $expensePaidAmt = 0;
    public $expenseDueAmt = 0;
    public $selectRows = [];
    public $selectPageRows = false;

    public $searchDate, $firstFilterDate, $lastFilterDate;


    #[Computed]
    #[On('expense-all')]
    public function resultExpense()
    {
        $expenses = DB::table('ACC_EXPENSE_MST as p');

        $expenses
            ->orderBy('p.expense_mst_id', 'DESC')
            ->leftJoin('ACC_EXPENSES_LIST as s', function ($join) {
                $join->on('s.expense_id', '=', 'p.expense_type');
            })
            ->select(['p.*', 's.expense_type as p_name']);

        if ($this->search) {
            $expenses
                ->orwhere(DB::raw('lower(p.memo_no)'), 'like', '%' . strtolower($this->search) . '%')
                ->orWhere('p.total_amount', 'like', '%' . $this->search . '%')
                ->orWhere('p.tot_paid_amt', 'like', '%' . $this->search . '%')
                ->orWhere('s.expense_type', 'like', '%' . $this->search . '%');
        }


        if ($this->firstFilterDate) {
            $expenses->where('p.expense_date', '>=', $this->firstFilterDate);
        }

        if ($this->lastFilterDate) {
            $expenses->where('p.expense_date', '<=', $this->lastFilterDate);
        }


        // $p =   $expenses->get();
        // dd($p);

        return $expenses->paginate($this->pagination);
    }

    public function dateFilter()
    {
        $dates = explode('-', $this->searchDate);
        $this->firstFilterDate = Carbon::parse($dates[0])->format('Y-m-d');
        $this->lastFilterDate = Carbon::parse($dates[1])->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectPageRows()
    {
        if ($this->selectPageRows) {
            $this->selectRows = $this->resultExpense->pluck('expense_mst_id')->toArray();
        } else {
            $this->selectRows = [];
        }
    }

    public function mount()
    {
        $amt = DB::table('ACC_EXPENSE_MST as p')
            ->select(
                DB::raw('SUM(total_amount) AS total_amount'),
                DB::raw('SUM(tot_paid_amt) AS tot_paid_amt'),
                DB::raw('SUM(tot_due_amt) AS tot_due_amt'),
            )
            ->first();


        $this->expenseGrantAmt = $amt->total_amount;
        $this->expensePaidAmt = $amt->tot_paid_amt;
        $this->expenseDueAmt = $amt->tot_due_amt;
    }
    public function render()
    {
        return view('livewire.dashboard.expense.expense');
    }
}