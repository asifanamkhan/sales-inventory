<?php

namespace App\Livewire\Dashboard\Purchase\Return;

use App\Service\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\On;

class PurchaseReturnForm extends Component
{
    public $state = [];
    public $document = [];
    public $paymentState = [];
    public $purchasesearch, $payment_methods, $oldPurchaseSearch;
    public $resultPurchases = [];
    public $purchaseCart = [];
    public $searchSelect = -1;
    public $countProduct = 0;
    public $isCheck = false;
    public $pay_amt, $due_amt;



    public function paymentMethodAll()
    {
        return $this->payment_methods = DB::table('ACC_PAYMENT_MODE')
            ->get(['p_mode_id', 'p_mode_name']);
    }

    public function updatedPurchasesearch()
    {

        if ($this->purchasesearch) {

            $result = DB::table('INV_PURCHASE_MST as p')
                ->where('memo_no', $this->purchasesearch)
                ->get()
                ->toArray();

            if ($result) {
                $this->resultPurchases = $result;
                $this->resultAppend(0);
            } else {
                $this->resultPurchases = DB::table('INV_PURCHASE_MST as p')
                    ->where(DB::raw('lower(p.memo_no)'), 'like', '%' . strtolower($this->purchasesearch) . '%')
                    ->get()
                    ->toArray();
            }

            $this->searchSelect = -1;
        } else {
            $this->resetPurchaseSearch();
        }

        $this->countProduct = count($this->resultPurchases);
    }

    public function mount($purchase_return_id)
    {
        $this->state['net_payable_amt'] = 0;
        $this->state['tot_payable_amt'] = 0;
        $this->state['total_qty'] = 0;
        $this->state['tot_discount'] = 0;
        $this->state['tot_vat_amt'] = 0;
        // $this->state['pay_amt'] = '';
        $this->state['status'] = 1;
        $this->state['tran_date'] = Carbon::now()->toDateString();
        $this->paymentState['pay_mode'] = 1;

        $this->paymentMethodAll();
    }

    //search increment decrement start
    public function decrementHighlight()
    {
        if ($this->searchSelect > 0) {
            $this->searchSelect--;
        }
    }
    public function incrementHighlight()
    {
        if ($this->searchSelect < ($this->countProduct - 1)) {
            $this->searchSelect++;
        }
    }
    public function selectAccount()
    {
        $this->resultAppend($this->searchSelect);
    }

    public function searchRowSelect($pk)
    {
        $this->resultAppend($pk);
    }

    public function resultAppend($key)
    {
        $search = @$this->resultPurchases[$key]->tran_mst_id;
        $this->oldPurchaseSearch = @$this->resultPurchases[$key]->memo_no;

        if ($search) {
            $purchase_dtls = DB::table('VW_PRODUCT_PURCHASE_REPORT as pr')
                ->where('pr.purchase_no', $this->oldPurchaseSearch)
                ->get(['pr.*']);

            $this->purchaseCart = [];
            $this->state['p_code'] = @$this->resultPurchases[$key]->p_code;
            $this->state['war_id'] = @$this->resultPurchases[$key]->war_id;
            $this->state['comp_id'] = @$this->resultPurchases[$key]->comp_id;
            $this->state['branch_id'] = @$this->resultPurchases[$key]->branch_id;
            $this->state['lc_no'] = @$this->resultPurchases[$key]->lc_no;
            $this->state['ref_memo_no'] = @$this->resultPurchases[$key]->memo_no;

            foreach ($purchase_dtls as $purchase_dtl) {

                $return_qty = DB::table('INV_PURCHASE_RET_DTL as pr')
                    ->where('ref_memo_no', $this->state['ref_memo_no'])
                    ->where('item_code', $purchase_dtl->st_group_item_id)
                    ->sum('item_qty');

                $current_qty = (float)$purchase_dtl->item_qty - $return_qty;

                if ((float)$purchase_dtl->vat_amt && (float)$purchase_dtl->vat_amt > 0) {
                    $p_vat_amt = (float)$purchase_dtl->vat_amt / $purchase_dtl->item_qty;
                }

                $this->purchaseCart[] = [
                    'item_name' => $purchase_dtl->item_name,
                    'color_name' => $purchase_dtl->color_name,
                    'item_size_name' => $purchase_dtl->item_size_name,
                    'mrp_rate' => $purchase_dtl->pr_rate,
                    'vat_amt' => $purchase_dtl->vat_amt,
                    'p_vat_amt' => $p_vat_amt ?? 0,
                    'line_total' => $purchase_dtl->tot_payble_amt,
                    'qty' => $current_qty,
                    'p_qty' => $current_qty,
                    'discount' => $purchase_dtl->discount,
                    'st_group_item_id' => $purchase_dtl->st_group_item_id,
                    'is_check' => false,
                ];
            }

            $this->grandCalculation();
            $this->purchasesearch = '';
            $this->resetPurchaseSearch();
        }
    }

    public function hideDropdown()
    {
        $this->resetPurchaseSearch();
    }

    //search increment decrement end

    public function resetPurchaseSearch()
    {
        $this->searchSelect = -1;
        $this->resultPurchases = [];
    }


    public function calculation($key)
    {
        $purchase_qty = (float)$this->purchaseCart[$key]['p_qty'];
        if ((float)$this->purchaseCart[$key]['qty'] > $purchase_qty) {
            (float)$this->purchaseCart[$key]['qty'] = 1;
            session()->flash('error', "Return quantity can not bigger than purchase quantity ($purchase_qty)");
        }

        $qty = (float)$this->purchaseCart[$key]['qty'] ?? 1;
        $mrp_rate = (float)$this->purchaseCart[$key]['mrp_rate'] ?? 0;
        $discount = (float)$this->purchaseCart[$key]['discount'] ?? 0;
        (float)$this->purchaseCart[$key]['vat_amt'] = (float)$this->purchaseCart[$key]['p_vat_amt'] * $qty;
        $vat =  (float)$this->purchaseCart[$key]['vat_amt'] ?? 0;

        $this->purchaseCart[$key]['line_total'] = ((($qty * $mrp_rate) + $vat) -  $discount);

        $this->grandCalculation();
    }

    public function grandCalculation()
    {
        $sub_total = 0;
        $total_qty = 0;
        $total_discount = 0;
        $total_vat = 0;
        $shipping_amt = $this->state['shipping_amt'] ?? 0;

        foreach ($this->purchaseCart as $value) {
            if ($value['is_check']) {
                $sub_total += (float)$value['line_total'] ?? 0;
                $total_qty += (float)$value['qty'] ?? 0;
                $total_discount += (float)$value['discount'] ?? 0;
                $total_vat += (float)$value['vat_amt'] ?? 0;
            }
        }

        $this->state['net_payable_amt'] = number_format($sub_total, 2, '.', '') ?? 0;

        $this->state['total_qty'] = $total_qty ?? 0;
        $this->state['tot_vat_amt'] = $total_vat ?? 0;
        $this->state['tot_discount'] = $total_discount ?? 0;

        $this->state['tot_payable_amt'] = number_format(((float)$sub_total - (float)$shipping_amt), 2, '.', '');
        $this->due_amt = number_format(((float)$this->state['tot_payable_amt'] - (float)$this->pay_amt), 2, '.', '');
    }

    public function purchaseActive($key)
    {
        if ($this->purchaseCart[$key]['is_check'] == true) {
            $this->purchaseCart[$key]['is_check'] = 1;
        } else {
            $this->purchaseCart[$key]['is_check'] = 0;
            $this->purchaseCart[$key]['return_qty'] =  '';
        }
        $this->calculation($key);
    }

    #[On('save_form')]
    public function save()
    {

        Validator::make($this->state, [
            'tran_date' => 'required|date',
            'status' => 'required|numeric',
            'tot_payable_amt' => 'required|numeric',
            'net_payable_amt' => 'required|numeric',

        ])->validate();

        if (count($this->purchaseCart) > 0) {

            // dd(
            //     // $this->state,
            //     // $this->paymentState,
            //     // $this->purchaseCart,
            //     // $this->oldPurchaseSearch,

            // );

            DB::beginTransaction();
            try {
                $this->state['user_name'] = Auth::user()->id;
                $this->state['emp_id'] = Auth::user()->id;
                $this->state['comp_id'] = 1;
                $this->state['branch_id'] = 1;
                $this->state['tot_due_amt'] = $this->due_amt;
                $this->state['tot_paid_amt'] = $this->pay_amt;
                $this->state['payment_status'] = Payment::ReturnPaymentCheck($this->due_amt);


                $tran_mst_id = DB::table('INV_PURCHASE_RET_MST')
                    ->insertGetId($this->state, 'tran_mst_id');

                foreach ($this->purchaseCart as $key => $value) {
                    if ($value['is_check'] == 1) {
                        DB::table('INV_PURCHASE_RET_DTL')->insert([
                            'tran_mst_id' => $tran_mst_id,
                            'item_code' => $value['st_group_item_id'],
                            'item_qty' => $value['qty'],
                            'pr_rate' => $value['mrp_rate'],
                            'vat_amt' => $value['vat_amt'],
                            'discount' => $value['discount'],
                            'tot_payble_amt' => $value['line_total'],
                            'user_name' => $this->state['user_name'],
                            'expire_date' => @$value['expire_date'],
                            'ref_memo_no' => $this->state['ref_memo_no']
                        ]);
                    }
                }

                $prev_rt_amount = DB::table('INV_PURCHASE_MST as p')
                    ->where('memo_no', $this->oldPurchaseSearch)
                    ->first();

                DB::table('INV_PURCHASE_MST as p')
                    ->where('memo_no', $this->oldPurchaseSearch)
                    ->update([
                        'prt_amt' => ((float)$prev_rt_amount->prt_amt + $this->state['tot_payable_amt']),
                    ]);

                $ref_memo_no = DB::table('INV_PURCHASE_RET_MST')
                    ->where('tran_mst_id', $tran_mst_id)
                    ->first();

                DB::table('ACC_VOUCHER_INFO')->insert([
                    'voucher_date' => $this->state['tran_date'],
                    'voucher_type' => 'CR',
                    'narration' => 'purchase vouchar',
                    'amount' => $this->state['tot_payable_amt'],
                    'created_by' => $this->state['user_name'],
                    'tran_type' => 'PRT',
                    'ref_memo_no' => $ref_memo_no->memo_no,
                    'account_code' => 1030,
                ]);

                if ($this->pay_amt && $this->pay_amt > 0) {

                    $payment_info = [
                        'tran_mst_id' => $tran_mst_id,
                        'tran_type' => 'PRT',
                        'payment_date' => $this->state['tran_date'],
                        'p_code' => $this->state['p_code'],
                        'pay_mode' => $this->paymentState['pay_mode'],
                        'tot_payable_amt' => $this->state['tot_payable_amt'],
                        'discount' => $this->state['tot_discount'],
                        'vat_amt' => $this->state['tot_vat_amt'],
                        'net_payable_amt' => $this->state['net_payable_amt'],
                        'due_amt' => $this->due_amt,
                        'user_id' => $this->state['user_name'],
                        'ref_memo_no' => $ref_memo_no->memo_no,
                        'payment_status' => Payment::PaymentCheck($this->due_amt),
                        'tot_paid_amt' => $this->pay_amt ?? 0,

                    ];
                    if ($this->paymentState['pay_mode'] == 2) {
                        $payment_info['bank_code'] = @$this->paymentState['bank_code'] ?? '';
                        $payment_info['bank_ac_no'] = @$this->paymentState['bank_ac_no'] ?? '';
                        $payment_info['chq_no'] = @$this->paymentState['chq_no'] ?? '';
                        $payment_info['chq_date'] = @$this->paymentState['chq_date'] ?? '';
                    }

                    if ($this->paymentState['pay_mode'] == 3 || $this->paymentState['pay_mode'] == 6 || $this->paymentState['pay_mode'] == 7) {
                        $payment_info['card_no'] = @$this->paymentState['card_no'] ?? '';
                        $payment_info['bank_code'] = @$this->paymentState['bank_code'] ?? '';
                    }

                    if ($this->paymentState['pay_mode'] == 4) {
                        $payment_info['mfs_id'] = @$this->paymentState['mfs_id'] ?? '';
                        $payment_info['mfs_acc_no'] = @$this->paymentState['mfs_acc_no'] ?? '';
                    }
                    if ($this->paymentState['pay_mode'] == 4 || $this->paymentState['pay_mode'] == 5 || $this->paymentState['pay_mode'] == 3 || $this->paymentState['pay_mode'] == 6) {
                        $payment_info['online_trx_id'] = @$this->paymentState['online_trx_id'] ?? '';
                        $payment_info['online_trx_dt'] = @$this->paymentState['online_trx_dt'] ?? '';
                    }

                    $pay_id = DB::table('ACC_PAYMENT_INFO')
                        ->insertGetId($payment_info, 'payment_no');

                    $pay_memo = DB::table('ACC_PAYMENT_INFO')
                        ->where('payment_no', $pay_id)
                        ->first()
                        ->memo_no;

                    DB::table('ACC_VOUCHER_INFO')->insert([
                        'voucher_date' => $this->state['tran_date'],
                        'voucher_type' => 'DR',
                        'narration' => 'purchase vouchar',
                        'amount' => $this->pay_amt,
                        'created_by' => $this->state['user_name'],
                        'tran_type' => 'PRT',
                        'ref_memo_no' => $ref_memo_no->memo_no,
                        'account_code' => 1030,
                        'ref_pay_no' => $pay_memo,
                        'cash_type' => 'IN',
                    ]);

                    DB::table('INV_PURCHASE_MST as p')
                        ->where('memo_no', $this->oldPurchaseSearch)
                        ->update([
                        'prt_paid' => ((float)$prev_rt_amount->prt_paid + $this->pay_amt),
                    ]);
                }


                DB::commit();

                session()->flash('status', 'New purchase return created successfully');
                return $this->redirect(route('purchase-return'), navigate: true);
            } catch (\Exception $exception) {
                DB::rollback();
                session()->flash('error', $exception);
            }
        } else {
            session()->flash('error', '*At least one item need to added');
        }
    }
    public function render()
    {
        return view('livewire.dashboard.purchase.return.purchase-return-form');
    }
}