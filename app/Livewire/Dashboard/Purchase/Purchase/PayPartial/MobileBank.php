<?php

namespace App\Livewire\Dashboard\Purchase\Purchase\PayPartial;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class MobileBank extends Component
{
    public $mfs;
    public $mfs_id;

    public function mount($mfs_id){
        $this->mfs_id = $mfs_id ?? '';
        $this->mfs = DB::table('ACC_MFS_INFO')
        ->get();
    }
    public function render()
    {
        return view('livewire.dashboard.purchase.purchase.pay-partial.mobile-bank');
    }
}