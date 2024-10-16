<?php

namespace App\Livewire\Dashboard\Reports\Customer;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;

class CustomerLedger extends Component
{
    public $customers, $ledgers = [];
    public $state = [];

    public function customersAll()
    {
        return $this->customers = DB::table('INV_CUSTOMER_INFO')
            ->orderBy('customer_id', 'DESC')
            ->get();
    }

    public function search()
    {
        Validator::make($this->state, [
            'customer_id' => 'required|numeric',
        ], [
            'customer_id' => 'customer name is required'
        ])->validate();

        $this->ledgers = DB::table('VW_INV_CUSTOMER_PAYMENT_LEDGER')
            ->where('customer_id', $this->state['customer_id'])
            ->get();

    }

    public function mount()
    {
        $this->customersAll();

    }
    public function render()
    {
        return view('livewire.dashboard.reports.customer.customer-ledger');
    }
}