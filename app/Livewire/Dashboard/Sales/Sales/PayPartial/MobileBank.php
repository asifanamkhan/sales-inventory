<?php

namespace App\Livewire\Dashboard\Sales\Sales\PayPartial;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class MobileBank extends Component
{
    public $mfs;


    public function mount(){
        $this->mfs = DB::table('ACC_MFS_INFO')
        ->get();
    }
    public function render()
    {
        return view('livewire.dashboard.sales.sales.pay-partial.mobile-bank');
    }
}