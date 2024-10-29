<?php

namespace App\Livewire\Dashboard\Purchase\Purchase;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\On;

class PurchaseCreate extends Component
{
    public function render()
    {
        return view('livewire.dashboard.purchase.purchase.purchase-create')->title('Purchase create');
    }

    #[On('savePurchase')]
    public function savePurchase($formData)
    {
        // dd($formData);
        DB::beginTransaction();
        try {

            $tran_mst_id = DB::table('INV_PURCHASE_MST')
                ->insertGetId($formData['state'], 'tran_mst_id');

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

            $ref_memo_no = DB::table('INV_PURCHASE_MST')
                ->where('tran_mst_id', $tran_mst_id)
                ->first();

            DB::table('ACC_VOUCHER_INFO')->insert([
                'voucher_date' => $formData['state']['tran_date'],
                'voucher_type' => 'DR',
                'narration' => 'purchase vouchar',
                'amount' => $formData['state']['tot_payable_amt'],
                'created_by' => $formData['state']['user_name'],
                'tran_type' => 'PR',
                'ref_memo_no' => $ref_memo_no->memo_no,
                'account_code' => 1030,
            ]);

            if ($formData['payment_info']) {

                $formData['payment_info']['tran_mst_id'] = $tran_mst_id;
                $formData['payment_info']['ref_memo_no'] = $ref_memo_no->memo_no;
                $pay_id = DB::table('ACC_PAYMENT_INFO')
                    ->insertGetId($formData['payment_info'], 'payment_no');

                $pay_memo = DB::table('ACC_PAYMENT_INFO')
                    ->where('payment_no', $pay_id)
                    ->first()
                    ->memo_no;

                DB::table('ACC_VOUCHER_INFO')->insert([
                    'voucher_date' => $formData['state']['tran_date'],
                    'voucher_type' => 'CR',
                    'narration' => 'purchase vouchar',
                    'amount' => $formData['state']['tot_paid_amt'],
                    'created_by' => $formData['state']['user_name'],
                    'tran_type' => 'PR',
                    'ref_memo_no' => $ref_memo_no->memo_no,
                    'account_code' => 1030,
                    'ref_pay_no' => $pay_memo,
                    'cash_type' => 'OUT',
                ]);
            }
            DB::commit();

            session()->flash('status', 'New purchase created successfully');
            return $this->redirect(route('purchase'), navigate: true);

        } catch (\Exception $exception) {
            DB::rollback();
            session()->flash('error', $exception);
        }
    }
}