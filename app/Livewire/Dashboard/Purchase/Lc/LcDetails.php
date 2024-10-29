<?php

namespace App\Livewire\Dashboard\Purchase\Lc;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class LcDetails extends Component
{
    public $lc_mst;
    public $lc_id;

    public function mount($lc_id)
    {
        $this->lc_id = $lc_id;
        $this->lc_mst = DB::table('INV_LC_DETAILS as p')
            ->where('p.tran_mst_id', $lc_id)
            ->first(['p.*']);

    }
    public function render()
    {
        return view('livewire.dashboard.purchase.lc.lc-details');
    }
}
