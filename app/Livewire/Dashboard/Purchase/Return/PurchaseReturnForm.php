<?php

namespace App\Livewire\Dashboard\Purchase\Return;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PurchaseReturnForm extends Component
{
    public $state = [];
    public $document = [];
    public $paymentState = [];
    public $purchasesearch, $payment_methods;
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
                    ->get(['*'])
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
        $this->state['return_total_qty'] = 0;
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

        if ($search) {
            $purchase_dtls =DB::table('INV_PURCHASE_DTL as pr')
                    ->where('pr.tran_mst_id', $search)
                    ->leftJoin('INV_ST_GROUP_ITEM as p', function ($join) {
                        $join->on('p.st_group_item_id', '=', 'pr.item_code');
                    })
                    ->leftJoin('INV_ST_ITEM_SIZE as s', function ($join) {
                        $join->on('s.item_size_code', '=', 'p.item_size');
                    })
                    ->leftJoin('INV_COLOR_INFO as c', function ($join) {
                        $join->on('c.tran_mst_id', '=', 'p.color_code');
                    })

                    ->get(['pr.*','p.item_name','p.st_group_item_id','s.item_size_name','c.color_name']);

                    $this->purchaseCart = [];
                    foreach($purchase_dtls as $purchase_dtl){

                        $this->purchaseCart[] = [
                            'item_name' => $purchase_dtl->item_name,
                            'color_name' => $purchase_dtl->color_name,
                            'item_size_name' => $purchase_dtl->item_size_name,
                            'mrp_rate' => $purchase_dtl->pr_rate,
                            'vat_amt' => $purchase_dtl->vat_amt,
                            'line_total' => $purchase_dtl->tot_payble_amt,
                            'qty' => $purchase_dtl->item_qty,
                            'discount' => $purchase_dtl->discount,
                            'st_group_item_id' => $purchase_dtl->st_group_item_id,
                            'is_check' => 0,
                            'return_qty' => '',
                            'return_line_total' => '',
                            'return_discount' => '',
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
        $qty = (float)$this->purchaseCart[$key]['qty'] ?? 0;
        $mrp_rate = (float)$this->purchaseCart[$key]['mrp_rate'] ?? 0;
        $discount = (float)$this->purchaseCart[$key]['discount'] ?? 0;
        $vat = (float)$this->purchaseCart[$key]['vat_amt'] ?? 0;

        $return_qty = (float)$this->purchaseCart[$key]['return_qty'] ?? 0;
        $mrp_rate = (float)$this->purchaseCart[$key]['mrp_rate'] ?? 0;
        $return_discount = (float)$this->purchaseCart[$key]['return_discount'] ?? 0;
        $vat = (float)$this->purchaseCart[$key]['vat_amt'] ?? 0;

        $this->purchaseCart[$key]['line_total'] = ((($qty * $mrp_rate) + $vat) -  $discount);
        $this->purchaseCart[$key]['return_line_total'] = ((($return_qty * $mrp_rate) + $vat) -  $return_discount);

        $this->grandCalculation();
    }

    public function grandCalculation()
    {
        $sub_total = 0;
        $total_qty = 0;
        $total_discount = 0;
        $total_vat = 0;
        $shipping_amt = $this->state['shipping_amt'] ?? 0;

        $total_return_qty = 0;
        $return_sub_total = 0;
        $total_return_discount = 0;
        $total_return_vat = 0;

        foreach ($this->purchaseCart as $value) {

            $sub_total += (float)$value['line_total'] ?? 0;
            $total_qty += (float)$value['qty'] ?? 0;
            $total_discount += (float)$value['discount'] ?? 0;
            $total_vat += (float)$value['vat_amt'] ?? 0;

            $return_sub_total += (float)$value['return_line_total'] ?? 0;
            $total_return_qty += (float)$value['return_qty'] ?? 0;
            $total_return_discount += (float)$value['return_discount'] ?? 0;
            
        }

        $this->state['net_payable_amt'] = number_format($sub_total, 2, '.', '') ?? 0;

        $this->state['total_qty'] = $total_qty ?? 0;
        $this->state['tot_vat_amt'] = $total_vat ?? 0;
        $this->state['tot_discount'] = $total_discount ?? 0;

        $this->state['tot_payable_amt'] = number_format(((float)$shipping_amt + (float)$sub_total), 2, '.', '');
        $this->due_amt = number_format(((float)$this->state['tot_payable_amt'] - (float)$this->pay_amt), 2, '.', '');
    }

    public function purchaseActive($key){
        if($this->purchaseCart[$key]['is_check'] == true){
            $this->purchaseCart[$key]['is_check'] = 1;

        }else{
            $this->purchaseCart[$key]['is_check'] = 0;
            $this->purchaseCart[$key]['return_qty'] =  '';
        }
    }

    public function save()
    {

        Validator::make($this->state, [
            'tran_date' => 'required|date',
            'war_id' => 'required|numeric',
            'status' => 'required|numeric',
            'p_code' => 'required|numeric',
            'tot_payable_amt' => 'required|numeric',
            'net_payable_amt' => 'required|numeric',

        ])->validate();

        if (count($this->purchaseCart) > 0) {

            // dd(
            //     $this->state,
            //     $this->paymentState,
            //     $this->purchaseCart,
            // );

            DB::beginTransaction();
            try {
                $this->state['user_name'] = Auth::user()->id;
                $this->state['emp_id'] = Auth::user()->id;
                $this->state['comp_id'] = Auth::user()->id;
                $this->state['branch_id'] = Auth::user()->id;

                $tran_mst_id = DB::table('INV_PURCHASE_MST')->insertGetId($this->state, 'tran_mst_id');

                foreach ($this->purchaseCart as $key => $value) {
                    DB::table('INV_PURCHASE_DTL')->insert([
                        'tran_mst_id' => $tran_mst_id,
                        'item_code' => $value['st_group_item_id'],
                        'item_qty' => $value['qty'],
                        'pr_rate' => $value['mrp_rate'],
                        'vat_amt' => $value['vat_amt'],
                        'discount' => $value['discount'],
                        'tot_payble_amt' => $value['line_total'],
                        'user_name' => $this->state['user_name'],
                        'expire_date' => @$value['expire_date'],
                    ]);
                }


                $payment_info = [
                    'tran_mst_id' => $tran_mst_id,
                    'tran_type' => 'PR',
                    'payment_date' => $this->state['tran_date'],
                    'p_code' => $this->state['p_code'],
                    'pay_mode' => $this->paymentState['pay_mode'],
                    'tot_payable_amt' => $this->state['tot_payable_amt'],
                    'discount' => $this->state['tot_discount'],
                    'vat_amt' => $this->state['tot_vat_amt'],
                    'net_payable_amt' => $this->pay_amt ?? 0,
                    'due_amt' => $this->due_amt,
                    'user_id' => $this->state['user_name']
                ];
                if ($this->paymentState['pay_mode'] == 2) {
                    $payment_info['bank_code'] = @$this->paymentState['bank_code'] ?? '';
                    $payment_info['bank_ac_no'] = @$this->paymentState['bank_ac_no'] ?? '';
                    $payment_info['chq_no'] = @$this->paymentState['chq_no'] ?? '';
                    $payment_info['chq_date'] = @$this->paymentState['chq_date'] ?? '';
                }

                if ($this->paymentState['pay_mode'] == 3 || $this->paymentState['pay_mode'] == 6 || $this->paymentState['pay_mode'] == 7) {
                    $payment_info['card_no'] = @$this->paymentState['card_no'] ?? '';
                }

                if ($this->paymentState['pay_mode'] == 4) {
                    $payment_info['mfs_id'] = @$this->paymentState['mfs_id'] ?? '';
                }
                if ($this->paymentState['pay_mode'] == 4 || $this->paymentState['pay_mode'] == 5) {
                    $payment_info['online_trx_id'] = @$this->paymentState['online_trx_id'] ?? '';
                    $payment_info['chq_date'] = @$this->paymentState['chq_date'] ?? '';
                }


                DB::table('ACC_PAYMENT_INFO')->insert($payment_info);


                DB::commit();

                session()->flash('status', 'New purchase created successfully');
                return $this->redirect(route('purchase'), navigate:true);

            } catch (\Exception $exception) {
                DB::rollback();
                session()->flash('error', $exception);
            }
        } else {
            session()->flash('error', '*At least one product need to added');
        }
    }
    public function render()
    {
        return view('livewire.dashboard.purchase.return.purchase-return-form');
    }
}
