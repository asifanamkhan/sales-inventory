<?php

namespace App\Livewire\Dashboard\Expense\Category;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Validator;

class ExpenseCategoryForm extends Component
{
    public $expense_type, $accounts;
    public $expense_cat;
    public $state = [];
    public $editForm = false;
    public $edit_select = [];


    public function accountCodeAll()
    {
        return $this->accounts = DB::table('ACC_CHART_OF_ACCOUNTS')
            ->whereIn('parent_code', ['6000'])
            ->orderBy('account_code', 'DESC')
            ->get();
    }

    public function store(){

        Validator::make($this->state, [
            'expense_type' => 'required',
            'account_code' => 'required',

        ])->validate();

        DB::table('ACC_EXPENSES_LIST')->insert($this->state);

        $this->dispatch('refresh-expense-cates');
        session()->flash('status', 'Expense category create successfully');

        $this->refresh();
    }

    #[On('create-expense-cat-modal')]
    public function refresh(){
        $this->reset();
        $this->accountCodeAll();
    }

    #[On('expense-cat-edit-modal')]
    public function edit($id){

        $this->editForm = true;
        $this->state = (array)DB::table('ACC_EXPENSES_LIST')
            ->where('expense_id', $id)
            ->first();


    }

    public function update() {
        Validator::make($this->state, [
            'expense_type' => 'required',
            'account_code' => 'required',

        ])->validate();

        DB::table('ACC_EXPENSES_LIST')
            ->where('expense_id', $this->state['expense_id'])
            ->update($this->state);

        $this->dispatch('refresh-expense-cates');
        session()->flash('status', 'Expense category updated successfully');

    }

    public function mount(){
        $this->accountCodeAll();

    }

    public function render()
    {
        return view('livewire.dashboard.expense.category.expense-category-form');
    }
}