<?php

namespace App\Livewire\Dashboard\Sales\Sales\PayPartial;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Bank extends Component
{
    public $banks;


    public function mount(){
        $this->banks = DB::table('ACC_BANK_INFO')
        ->distinct('bank_name')
        ->orderBy('bank_name', 'ASC')
        ->get(['bank_name','bank_code']);
    }
    public function render()
    {
        return view('livewire.dashboard.sales.sales.pay-partial.bank');
    }
}
