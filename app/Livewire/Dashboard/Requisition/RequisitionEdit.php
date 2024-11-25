<?php

namespace App\Livewire\Dashboard\Requisition;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\On;

class RequisitionEdit extends Component
{
    public $requisition_id;
    public function mount($requisition_id)
    {
        $this->requisition_id = $requisition_id;
        // dd($this->requisition_id);
    }

    #[On('updateRequisition')]
    public function updateRequisition($formData)
    {
        DB::beginTransaction();
        try {
            DB::table('INV_REQUISTION_MST')
                ->where('tran_mst_id', $this->requisition_id)
                ->update($formData['state']);

            DB::table('INV_REQUISTION_DTL')
                ->where('tran_mst_id', $this->requisition_id)
                ->delete();

            $tran_mst_id = $this->requisition_id;

            foreach ($formData['requisitionCart'] as $key => $value) {
                DB::table('INV_REQUISTION_DTL')->insert([
                    'tran_mst_id' => $tran_mst_id,
                    'item_code' => $value['st_group_item_id'],
                    'item_qty' => $value['qty'],
                    'pr_rate' => $value['mrp_rate'],
                    'vat_amt' => $value['vat_amt'],
                    'discount' => $value['discount'],
                    'tot_payble_amt' => $value['line_total'],
                    'user_name' => $formData['state']['user_name'],
                ]);
            }

            DB::commit();

            session()->flash('status', 'Requisition updated successfully');
            return $this->redirect(route('requisition'), navigate: true);
        } catch (\Exception $exception) {
            DB::rollback();
            session()->flash('error', $exception);
        }
    }
    public function render()
    {
        return view('livewire.dashboard.requisition.requisition-edit');
    }
}