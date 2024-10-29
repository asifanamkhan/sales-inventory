<?php

namespace App\Livewire\Dashboard\Purchase\Lc;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\On;

class LcCreate extends Component
{
    #[On('saveLC')]
    public function saveLC($formData)
    {
        // dd($formData);
        DB::beginTransaction();
        try {

            DB::table('INV_LC_DETAILS')
                ->insert($formData['state']);

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
        return view('livewire.dashboard.purchase.lc.lc-create');
    }
}