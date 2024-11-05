<?php

namespace App\Livewire\Dashboard\Purchase\Purchase;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\On;

class PurchaseEdit extends Component
{
    public $purchase_id;
    public function mount($purchase_id)
    {
        $this->purchase_id = $purchase_id;
        // dd($this->purchase_id);
    }

    #[On('updatePurchase')]
    public function updatePurchase($formData)
    {
        DB::beginTransaction();
        try {
            DB::table('INV_PURCHASE_MST')
                ->where('tran_mst_id', $this->purchase_id)
                ->update($formData['state']);

            DB::table('INV_PURCHASE_DTL')
                ->where('tran_mst_id', $this->purchase_id)
                ->delete();

            $tran_mst_id = $this->purchase_id;

            foreach ($formData['purchaseCart'] as $key => $value) {
                DB::table('INV_PURCHASE_DTL')->insert([
                    'tran_mst_id' => $tran_mst_id,
                    'item_code' => $value['st_group_item_id'],
                    'item_qty' => $value['qty'],
                    'pr_rate' => $value['mrp_rate'],
                    'vat_amt' => $value['vat_amt'],
                    'discount' => $value['discount'],
                    'tot_payble_amt' => $value['line_total'],
                    'user_name' => $formData['state']['user_name'],
                    'expire_date' => @$value['expire_date'],
                ]);
            }
            
            DB::commit();

            session()->flash('status', 'Purchase updated successfully');
            return $this->redirect(route('purchase'), navigate: true);
        } catch (\Exception $exception) {
            DB::rollback();
            session()->flash('error', $exception);
        }
    }
    public function render()
    {
        return view('livewire.dashboard.purchase.purchase.purchase-edit')
            ->title('Purchase edit');
    }
}