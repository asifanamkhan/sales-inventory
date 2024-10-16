<?php

namespace App\Livewire\Dashboard\Reports\Supplier;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;

class SupplierLedger extends Component
{
    public $suppliers, $ledgers = [];
    public $state = [];

    public function suppliersAll()
    {
        return $this->suppliers = DB::table('INV_SUPPLIER_INFO')
            ->orderBy('p_code', 'DESC')
            ->get();
    }

    public function search()
    {
        Validator::make($this->state, [
            'p_code' => 'required|numeric',
        ], [
            'p_code' => 'supplier name is required'
        ])->validate();

        $this->ledgers = DB::table('VW_INV_SUPPLIER_PAYMENT_LEDGER')
            ->where('p_code', $this->state['p_code'])
            ->get();

    }

    public function mount()
    {
        $this->suppliersAll();

    }
    public function render()
    {
        return view('livewire.dashboard.reports.supplier.supplier-ledger');
    }
}