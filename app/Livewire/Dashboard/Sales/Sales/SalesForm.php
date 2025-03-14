<?php

namespace App\Livewire\Dashboard\Sales\Sales;

use App\Service\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\On;

class SalesForm extends Component
{
    public $state = [];
    public $document = [];
    public $paymentState = [];
    public $customers, $war_houses, $productsearch, $payment_methods;
    public $resultProducts = [];
    public $saleCart = [];
    public $saleCheck = [];
    public $searchSelect = -1;
    public $countProduct = 0;
    public $isCheck = false;
    public $pay_amt, $due_amt, $change_amt, $net_pay_amt;
    public $edit_select = [];
    public $sale_id;


    public function customersAll()
    {
        return $this->customers = DB::table('INV_CUSTOMER_INFO')
            ->orderBy('customer_id', 'DESC')
            ->get();
    }

    #[On('add-customer-sale')]
    public function customerRedender($customer_id){
        $this->customers = DB::table('INV_CUSTOMER_INFO')
            ->orderBy('customer_id', 'DESC')
            ->get();
        $data = [
            'customers' => $this->customers,
            'customer_id' => $customer_id
        ];

        $this->dispatch('render-customer-sale', data: $data);
    }

    public function wirehouseAll()
    {
        return $this->war_houses = DB::table('INV_WAREHOUSE_INFO')
            ->orderBy('war_id', 'DESC')
            ->get(['war_id', 'war_name']);
    }

    public function paymentMethodAll()
    {
        return $this->payment_methods = DB::table('ACC_PAYMENT_MODE')
            ->get(['p_mode_id', 'p_mode_name']);
    }

    public function updatedProductsearch()
    {
        if ($this->productsearch) {

            $result = DB::table('VW_INV_ITEM_DETAILS as p')
                ->where('p.item_code', $this->productsearch)
                ->leftJoin('VW_INV_ITEM_STOCK_QTY as s', function ($join) {
                    $join->on('s.st_group_item_id', '=', 'p.st_group_item_id');
                })
                ->get(['p.st_group_item_id', 'p.item_name', 'p.mrp_rate', 'p.vat_amt', 'p.item_code', 'p.color_name', 'p.item_size_name', 's.stock_qty'])
                ->toArray();

            if ($result) {
                $this->resultProducts = $result;
                $this->resultAppend(0);
            } else {

                $this->resultProducts = DB::table('VW_INV_ITEM_DETAILS as p')
                    ->where(DB::raw('lower(p.item_name)'), 'like', '%' . strtolower($this->productsearch) . '%')
                    ->leftJoin('VW_INV_ITEM_STOCK_QTY as s', function ($join) {
                        $join->on('s.st_group_item_id', '=', 'p.st_group_item_id');
                    })
                    ->get(['p.st_group_item_id', 'p.item_name', 'p.mrp_rate', 'p.vat_amt', 'p.item_code', 'p.color_name', 'p.item_size_name', 's.stock_qty'])
                    ->toArray();
                // dd($this->resultProducts, $this->productsearch);
            }

            $this->searchSelect = -1;
        } else {
            $this->resetProductSearch();
        }
        $this->countProduct = count($this->resultProducts);
    }

    public function mount($sale_id)
    {

        if ($sale_id) {
            $this->sale_id = $sale_id;
            $tran_mst = DB::table('INV_SALES_MST')
                ->where('tran_mst_id', $sale_id)
                ->first();
            // dd($tran_mst);
            $this->state['net_payable_amt'] = $tran_mst->net_payable_amt;
            $this->state['tot_payable_amt'] = $tran_mst->tot_payable_amt;
            $this->state['total_qty'] = $tran_mst->total_qty;
            $this->state['tot_discount'] = $tran_mst->tot_discount;
            $this->state['tot_vat_amt'] = $tran_mst->tot_vat_amt;
            $this->state['shipping_amt'] = $tran_mst->shipping_amt;
            $this->state['war_id'] = $tran_mst->war_id;
            $this->state['status'] = $tran_mst->status;
            $this->state['customer_id'] = $tran_mst->customer_id;
            $this->state['remarks'] = $tran_mst->remarks;
            $this->state['tran_date'] = Carbon::parse($tran_mst->tran_date)->toDateString();

            $this->pay_amt = $tran_mst->tot_paid_amt;
            $this->due_amt = $tran_mst->tot_due_amt;

            $this->edit_select['customer_id'] = $tran_mst->customer_id;
            $this->edit_select['war_id'] = $tran_mst->war_id;

            // dd($resultPay);

            $resultDtls = DB::table('INV_SALES_DTL as p')
                ->where('p.tran_mst_id', $sale_id)
                ->leftJoin('VW_INV_ITEM_DETAILS as pr', function ($join) {
                    $join->on('pr.st_group_item_id', '=', 'p.item_code');
                })
                ->leftJoin('VW_INV_ITEM_STOCK_QTY as s', function ($join) {
                    $join->on('s.st_group_item_id', '=', 'p.item_code');
                })
                ->get([
                    'p.mrp_rate',
                    'p.vat_amt',
                    'p.tot_payble_amt',
                    'p.item_qty',
                    'p.discount',
                    'p.item_code',
                    'pr.item_name',
                    'pr.color_name',
                    'pr.item_size_name',
                    'pr.vat_amt as p_vat_amt',
                    's.stock_qty'
                ]);

            // dd($resultDtls);

            foreach ($resultDtls as $resultDtl) {
                $this->saleCart[] = [
                    'item_name' => $resultDtl->item_name,
                    'color_name' => $resultDtl->color_name,
                    'item_size_name' => $resultDtl->item_size_name,
                    'mrp_rate' => $resultDtl->mrp_rate,
                    'vat_amt' => $resultDtl->vat_amt,
                    'p_vat_amt' =>  $resultDtl->p_vat_amt ?? 0,
                    'line_total' => $resultDtl->tot_payble_amt,
                    'qty' => $resultDtl->item_qty,
                    'discount' => $resultDtl->discount,
                    'st_group_item_id' => $resultDtl->item_code,
                    'stock_qty' => $resultDtl->stock_qty + $resultDtl->item_qty,
                    // 'expire_date' => $resultDtl->expire_date ? Carbon::parse($resultDtl->expire_date)->toDateString() : ''
                ];
            }

            // dd($tran_mst->tran_date);
        } else {
            $this->state['net_payable_amt'] = 0;
            $this->state['tot_payable_amt'] = 0;
            $this->state['total_qty'] = 0;
            $this->state['tot_discount'] = 0;
            $this->state['tot_vat_amt'] = 0;
            $this->state['war_id'] = 1;
            $this->state['status'] = 1;
            $this->state['tran_date'] = Carbon::now()->toDateString();
            $this->paymentState['pay_mode'] = 1;
        }

        $this->customersAll();
        $this->wirehouseAll();
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

        $search = @$this->resultProducts[$key]->st_group_item_id;

        if ($search) {

            $mrp = @$this->resultProducts[$key]->mrp_rate;

            if (!$mrp) {
                $this->resetProductSearch();
                session()->flash('error', 'Pricing has not added to selected product');
                return 0;
            }

            $valid = in_array($search, $this->saleCheck);

            if ($valid) {
                $this->resetProductSearch();
                session()->flash('error', 'Product already added to cart');
                return 0;
            }

            $stock = @$this->resultProducts[$key]->stock_qty;

            if ($stock < 1) {
                $this->resetProductSearch();
                session()->flash('error', "You can maximum $stock qty of this item");
                return 0;
            }

            $pricing = $this->resultProducts[$key];

            $this->saleCheck[] = $search;

            $line_total = (float)$pricing->mrp_rate + @$pricing->vat_amt ?? 0;

            $this->saleCart[] = [
                'item_name' => @$this->resultProducts[$key]->item_name,
                'color_name' => @$this->resultProducts[$key]->color_name,
                'item_size_name' => @$this->resultProducts[$key]->item_size_name,
                'mrp_rate' => $pricing->mrp_rate,
                'vat_amt' => $pricing->vat_amt,
                'p_vat_amt' => $pricing->vat_amt ?? 0,
                'line_total' => $line_total,
                'qty' => 1,
                'discount' => 0,
                'st_group_item_id' => $search,
                'stock_qty' => @$this->resultProducts[$key]->stock_qty,
            ];

            $this->grandCalculation();

            $this->productsearch = '';
            $this->resetProductSearch();
        }
    }

    public function hideDropdown()
    {
        $this->resetProductSearch();
    }

    //search increment decrement end

    public function resetProductSearch()
    {
        $this->searchSelect = -1;
        $this->resultProducts = [];
    }

    public function removeItem($key, $id)
    {
        unset($this->saleCart[$key]);
        $del_key = array_search($id, $this->saleCheck);
        unset($this->saleCheck[$del_key]);
        $this->grandCalculation();
    }

    public function calculation($key)
    {
        $qty = (float)$this->saleCart[$key]['qty'] ?? 0;
        $mrp_rate = (float)$this->saleCart[$key]['mrp_rate'] ?? 0;
        // $discount = (float)$this->saleCart[$key]['discount'] ?? 0;
        $discount =  0;
        (float)$this->saleCart[$key]['vat_amt'] = ((float)$this->saleCart[$key]['p_vat_amt'] * $qty) ?? 0;
        $vat = (float)$this->saleCart[$key]['vat_amt'] ?? 0;

        $this->saleCart[$key]['line_total'] = ((($qty * $mrp_rate) + $vat) -  $discount);

        $this->grandCalculation();
    }

    public function grandCalculation()
    {
        $sub_total = 0;
        $total_qty = 0;
        $total_discount = 0;
        $total_vat = 0;
        $shipping_amt = $this->state['shipping_amt'] ?? 0;

        foreach ($this->saleCart as $value) {

            $sub_total += (float)$value['line_total'] ?? 0;
            $total_qty += (float)$value['qty'] ?? 0;
            $total_discount += (float)$value['discount'] ?? 0;
            $total_vat += (float)$value['vat_amt'] ?? 0;
        }
        if($this->state['tot_discount'] > 0){
            $total_discount = $this->state['tot_discount'];
        }

        $this->state['net_payable_amt'] = number_format($sub_total, 2, '.', '') ?? 0;

        $this->state['total_qty'] = $total_qty ?? 0;
        $this->state['tot_vat_amt'] = $total_vat ?? 0;
        $this->state['tot_discount'] = $total_discount ?? 0;

        $this->state['tot_payable_amt'] = number_format(((float)$shipping_amt + (float)$sub_total - (float)$this->state['tot_discount']), 2, '.', '');


        if(!$this->pay_amt){
            $this->change_amt = 0;
            $this->due_amt = number_format(((float)$this->state['tot_payable_amt'] - (float)$this->pay_amt), 2, '.', '');
        }else{
            if( $this->pay_amt > $this->state['tot_payable_amt']){
                $this->change_amt = $this->pay_amt - $this->state['tot_payable_amt'];
                $this->due_amt = 0;
                $this->net_pay_amt = $this->state['tot_payable_amt'];
            }else{
                $this->change_amt = 0;
                $this->due_amt = number_format(((float)$this->state['tot_payable_amt'] - (float)$this->pay_amt), 2, '.', '');
                $this->net_pay_amt = $this->pay_amt;
            }
        }


    }

    public function qtyCalculation($product, $key)
    {

        $stock = $this->saleCart[$key]['stock_qty'];

        if ((float)$this->saleCart[$key]['qty'] <= (float)$stock ) {
            $this->calculation($key);
        } else {
            session()->flash('error', "You can maximum $stock qty of this item");
            $this->saleCart[$key]['qty'] = $stock;
        }
    }

    #[On('save_form')]
    public function save()
    {

        Validator::make($this->state, [
            'tran_date' => 'required|date',
            'war_id' => 'required|numeric',
            'status' => 'required|numeric',
            'customer_id' => 'required|numeric',
            'tot_payable_amt' => 'required|numeric',
            'net_payable_amt' => 'required|numeric',

        ])->validate();

        if (count($this->saleCart) <= 0) {
            session()->flash('error', '*At least one product need to added');
            return 0;
        }

        // dd(
        //     $this->state,
        //     $this->paymentState,
        //     $this->saleCart,
        // );

        DB::beginTransaction();
        try {
            $this->state['user_name'] = Auth::user()->id;
            $this->state['emp_id'] = Auth::user()->id;
            $this->state['comp_id'] = Auth::user()->id;
            $this->state['branch_id'] = Auth::user()->id;
            $this->state['tot_due_amt'] = $this->due_amt;
            $this->state['tot_paid_amt'] = $this->net_pay_amt;
            $this->state['payment_status'] = Payment::PaymentCheck($this->due_amt);

            if ($this->sale_id) {
                DB::table('INV_SALES_MST')
                    ->where('tran_mst_id', $this->sale_id)
                    ->update($this->state);

                DB::table('INV_SALES_DTL')
                    ->where('tran_mst_id', $this->sale_id)
                    ->delete();

                $tran_mst_id = $this->sale_id;
            } else {
                $tran_mst_id = DB::table('INV_SALES_MST')
                    ->insertGetId($this->state, 'tran_mst_id');
            }

            foreach ($this->saleCart as $key => $value) {
                DB::table('INV_SALES_DTL')->insert([
                    'tran_mst_id' => $tran_mst_id,
                    'item_code' => $value['st_group_item_id'],
                    'item_qty' => $value['qty'],
                    'mrp_rate' => $value['mrp_rate'],
                    'vat_amt' => $value['vat_amt'],
                    'discount' => $value['discount'],
                    'tot_payble_amt' => $value['line_total'],
                    'user_name' => $this->state['user_name'],
                ]);
            }

            if (!$this->sale_id) {

                $ref_memo_no = DB::table('INV_SALES_MST')
                ->where('tran_mst_id', $tran_mst_id)
                ->first();

                DB::table('ACC_VOUCHER_INFO')->insert([
                    'voucher_date' => $this->state['tran_date'],
                    'voucher_type' => 'CR',
                    'narration' => 'sale vouchar',
                    'amount' => $this->state['tot_payable_amt'],
                    'created_by' => $this->state['user_name'],
                    'tran_type' => 'SL',
                    'ref_memo_no' => $ref_memo_no->memo_no,
                    'account_code' => 4010,
                ]);

                if ($this->pay_amt && $this->pay_amt > 0) {

                    $payment_info = [
                        'tran_mst_id' => $tran_mst_id,
                        'tran_type' => 'SL',
                        'payment_date' => $this->state['tran_date'],
                        'p_code' => $this->state['customer_id'],
                        'pay_mode' => $this->paymentState['pay_mode'],
                        'tot_payable_amt' => $this->state['tot_payable_amt'],
                        'discount' => $this->state['tot_discount'],
                        'vat_amt' => $this->state['tot_vat_amt'],
                        'net_payable_amt' => $this->pay_amt ?? 0,
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
                    }

                    if ($this->paymentState['pay_mode'] == 4) {
                        $payment_info['mfs_id'] = @$this->paymentState['mfs_id'] ?? '';
                        $payment_info['mfs_acc_no'] = @$this->paymentState['mfs_acc_no'] ?? '';
                    }
                    if ($this->paymentState['pay_mode'] == 3  || $this->paymentState['pay_mode'] == 4 || $this->paymentState['pay_mode'] == 5) {
                        $payment_info['online_trx_id'] = @$this->paymentState['online_trx_id'] ?? '';
                        $payment_info['online_trx_dt'] = @$this->paymentState['online_trx_dt'] ?? '';
                    }

                    if (!$this->sale_id) {
                        $pay_id = DB::table('ACC_PAYMENT_INFO')
                        ->insertGetId($payment_info, 'payment_no');

                        $pay_memo = DB::table('ACC_PAYMENT_INFO')
                            ->where('payment_no', $pay_id)
                            ->first()
                            ->memo_no;

                        DB::table('ACC_VOUCHER_INFO')->insert([
                            'voucher_date' => $this->state['tran_date'],
                            'voucher_type' => 'DR',
                            'narration' => 'sale vouchar',
                            'amount' => $this->pay_amt,
                            'created_by' => $this->state['user_name'],
                            'tran_type' => 'SL',
                            'ref_memo_no' => $ref_memo_no->memo_no,
                            'account_code' => 4010,
                            'ref_pay_no' => $pay_memo,
                            'cash_type' => 'IN',
                        ]);
                    }
                }
            }

            DB::commit();

            if ($this->sale_id) {
                session()->flash('status', 'Sale updated successfully');
            } else {
                session()->flash('status', 'New sale created successfully');
            }

            return $this->redirect(route('sale'), navigate: true);
        } catch (\Exception $exception) {
            DB::rollback();
            session()->flash('error', $exception);
        }
    }
    public function render()
    {
        return view('livewire.dashboard.sales.sales.sales-form');
   }
}