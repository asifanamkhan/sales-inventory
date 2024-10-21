<?php

namespace App\Livewire\Dashboard\Expense\Category;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class ExpenseCategory extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $create_title = 'Create new expense category ';
    public $update_title = 'Update expense category ';
    public $create_event = 'expense-cat-create';
    public $update_event = 'expense-cat-update';
    public $search;
    public $pagination = 10;


    #[Computed]
    public function resultExpense()
    {
        $expense_cats = DB::table('ACC_EXPENSES_LIST');


        if ($this->search) {
            $expense_cats
                ->where(DB::raw('lower(expense_type)'), 'like', '%' . strtolower($this->search) . '%')
                ;
        }

        return $expense_cats->orderBy('expense_id', 'DESC')
            ->paginate($this->pagination);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    #[On('refresh-expense-cates')]
    public function refreshExpense(){
        $this->resultExpense();
    }

    public function render()
    {
        return view('livewire.dashboard.expense.category.expense-category');
    }
}