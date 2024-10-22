<?php

namespace App\Livewire\Dashboard\Expense;

use Livewire\Component;

class ExpenseEdit extends Component
{
    public $expense_id;
    public function mount($expense_id){
        $this->expense_id = $expense_id;
        
    }
    public function render()
    {
        return view('livewire.dashboard.expense.expense-edit');
    }
}