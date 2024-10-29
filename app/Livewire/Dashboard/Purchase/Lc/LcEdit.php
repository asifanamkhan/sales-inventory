<?php

namespace App\Livewire\Dashboard\Purchase\Lc;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\On;

class LcEdit extends Component
{
    public $lc_id;
    public function mount($lc_id)
    {
        $this->lc_id = $lc_id;
        // dd($this->lc_id);
    }

    #[On('updateLC')]
    public function updateLC($formData)
    {
        // dd($formData);
        DB::beginTransaction();
        try {

            DB::table('INV_LC_DETAILS')
                ->where('tran_mst_id', $this->lc_id)
                ->update($formData['state']);

            DB::commit();

            session()->flash('status', 'New LC created successfully');
            return $this->redirect(route('lc'), navigate: true);

        } catch (\Exception $exception) {
            DB::rollback();
            session()->flash('error', $exception);
        }
    }
    public function render()
    {
        return view('livewire.dashboard.purchase.lc.lc-edit');
    }
}