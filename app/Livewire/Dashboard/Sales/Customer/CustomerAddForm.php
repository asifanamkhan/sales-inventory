<?php

namespace App\Livewire\Dashboard\Sales\Customer;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomerAddForm extends Component
{


    public $state = [];
    public $customer_types, $sale;
    public $editForm = '';

    public function save()
    {
        Validator::make($this->state, [
            'customer_name' => 'required',
            'phone_no' => 'required|unique:INV_CUSTOMER_INFO,phone_no',
            'email' => 'email|nullable',
            'status' => 'required|numeric',
            'customer_type' => 'required|numeric',

        ])->validate();

        $customer_id = DB::table('INV_CUSTOMER_INFO')->insertGetId($this->state,'customer_id');
        session()->flash('status', 'New customers create successfully. You can find it at customers list');
        $this->dispatch('add-customer-sale', customer_id: $customer_id);
        $this->reset();
        $this->category_type();

    }

    public function category_type()
    {
        $this->customer_types = DB::table('INV_CUSTOMER_TYPE')
            ->orderBy('customer_type_code', 'DESC')
            ->get();

    }

    public function mount(){
        $this->state['customer_name'] = 'Walk In Customer';
        $this->state['status'] = 1;
        $this->category_type();
    }
    public function render()
    {
        return view('livewire.dashboard.sales.customer.customer-add-form');
    }
}