<?php

namespace App\Livewire\Dashboard\AccountReports;

use App\Service\Accounts;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TransactionReport extends Component
{
    public $trancastionType, $branchs, $payment_methods, $ledgers = [];
    public $state = [];

    public function branchAll()
    {
        return $this->branchs = DB::table('INV_BRANCH_INFO')
            ->orderBy('branch_id', 'DESC')
            ->get();
    }

    public function paymentMethodAll()
    {
        return $this->payment_methods = DB::table('ACC_PAYMENT_MODE')
            ->get(['p_mode_id', 'p_mode_name']);
    }


    public function search()
    {

        $query = DB::table('VW_ACC_VOUCHER_INFO')
            ->where('cash_type', '!=', null);

        if (@$this->state['start_date']) {
            $query->where('voucher_date', '>=', $this->state['start_date']);
        }
        if (@$this->state['end_date']) {
            $query->where('voucher_date', '<=', $this->state['end_date']);
        }

        if (@$this->state['branch_id']) {
            $query->where('branch_id', $this->state['branch_id']);
        }
        if (@$this->state['pay_mode']) {
            $query->where('pay_mode', $this->state['pay_mode']);
        }
        
        $this->ledgers = $query
            ->orderBy('voucher_id', 'DESC')
            ->get();
        

    }

    public function mount()
    {

        $this->branchAll();
        $this->paymentMethodAll();
        $this->state['branch_id'] = '';
        $this->state['start_date'] = '';
        $this->state['end_date'] = '';
        $this->state['pay_mode'] = '';
        
    }
    public function render()
    {
        return view('livewire.dashboard.account-reports.transaction-report');
    }
}