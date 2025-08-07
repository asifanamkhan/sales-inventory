<?php

namespace App\Livewire\Dashboard\AccountReports;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ProfitLoss extends Component
{
    public $branchs;
    public $state = [];

    public function branchAll()
    {
        return $this->branchs = DB::table('INV_BRANCH_INFO')
            ->orderBy('branch_id', 'DESC')
            ->get();
    }

    public function search()
    {

        $data = DB::table('VW_ACC_VOUCHER_INFO')
        ->select('tran_type', 'voucher_type', DB::raw('SUM(amount) as total_amount'))
        ->groupBy('tran_type', 'voucher_type')
        ->get();
        dd($data);
        $query = DB::table('VW_ACC_VOUCHER_INFO');

        if (@$this->state['start_date']) {
            $query->where('voucher_date', '>=', $this->state['start_date']);
        }
        if (@$this->state['end_date']) {
            $query->where('voucher_date', '<=', $this->state['end_date']);
        }

        if (@$this->state['branch_id']) {
            $query->where('branch_id', $this->state['branch_id']);
        }

        $this->state['sales'] = $query
            ->orderBy('voucher_id', 'ASC')
            ->get();

        dd($this->ledgers);
    }

    public function mount()
    {

        $this->branchAll();
        $this->state['branch_id'] = '';
        $this->state['start_date'] = '';
        $this->state['end_date'] = '';
        $this->state['pay_mode'] = '';

    }
    public function render()
    {
        return view('livewire.dashboard.account-reports.profit-loss');
    }
}