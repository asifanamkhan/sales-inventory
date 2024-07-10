<?php

namespace App\Livewire\Dashboard\Sales\Sales;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
    public $pay_amt, $due_amt;


    public function customersAll()
    {
        return $this->customers = DB::table('INV_CUSTOMER_INFO')
            ->orderBy('customer_id', 'DESC')
            ->get();
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

            $result = DB::table('INV_ST_GROUP_ITEM as p')
                ->where('barcode', $this->productsearch)
                ->leftJoin('INV_ST_ITEM_SIZE as s', function ($join) {
                    $join->on('s.item_size_code', '=', 'p.item_size');
                })
                ->leftJoin('INV_COLOR_INFO as c', function ($join) {
                    $join->on('c.tran_mst_id', '=', 'p.color_code');
                })
                ->get(['p.st_group_item_id', 'p.item_name', 'c.color_name', 's.item_size_name'])
                ->toArray();

            if ($result) {
                $this->resultProducts = $result;
                $this->resultAppend(0);
            } else {

                $this->resultProducts = DB::table('INV_ST_GROUP_ITEM as p')
                    ->where(DB::raw('lower(p.item_name)'), 'like', '%' . strtolower($this->productsearch) . '%')
                    ->leftJoin('INV_ST_ITEM_SIZE as s', function ($join) {
                        $join->on('s.item_size_code', '=', 'p.item_size');
                    })
                    ->leftJoin('INV_COLOR_INFO as c', function ($join) {
                        $join->on('c.tran_mst_id', '=', 'p.color_code');
                    })
                    ->get(['p.st_group_item_id', 'p.item_name', 'c.color_name', 's.item_size_name'])
                    ->toArray();
            }

            $this->searchSelect = -1;
        } else {
            $this->resetProductSearch();
        }
        $this->countProduct = count($this->resultProducts);
    }

    public function mount()
    {
        $this->state['net_payable_amt'] = 0;
        $this->state['tot_payable_amt'] = 0;
        $this->state['total_qty'] = 0;
        $this->state['tot_discount'] = 0;
        $this->state['tot_vat_amt'] = 0;
        // $this->state['pay_amt'] = '';
        $this->state['war_id'] = 1;
        $this->state['status'] = 1;
        $this->state['tran_date'] = Carbon::now()->toDateString();
        $this->paymentState['pay_mode'] = 1;

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
            $valid = in_array($search, $this->saleCheck);
            if (!$valid) {

                $pricing = DB::table('INV_PRICE_SCHEDULE_MST')
                    ->where('item_code', $search)
                    ->first();

                if ($pricing) {

                    // $stock = DB::table('VW_ITEM_STOCK_QTY')
                    // ->where('item_code', $search)
                    // ->first();
                    // dd($stock);

                    $this->saleCheck[] = $search;

                    $line_total = (float)$pricing->mrp_rate + @$pricing->vat_amt ?? 0;

                    $this->saleCart[] = [
                        'item_name' => @$this->resultProducts[$key]->item_name,
                        'color_name' => @$this->resultProducts[$key]->color_name,
                        'item_size_name' => @$this->resultProducts[$key]->item_size_name,
                        'mrp_rate' => $pricing->mrp_rate,
                        'vat_amt' => $pricing->vat_amt,
                        'line_total' => $line_total,
                        'qty' => 1,
                        'discount' => 0,
                        'st_group_item_id' => $search,
                    ];

                    $this->grandCalculation();

                    $this->productsearch = '';
                    $this->resetProductSearch();
                } else {
                    $this->resetProductSearch();
                    session()->flash('warning', 'Pricing has not added to selected product');
                }
            } else {
                $this->resetProductSearch();
                session()->flash('error', 'Product already added to cart');
            }
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
        $discount = (float)$this->saleCart[$key]['discount'] ?? 0;
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

        $this->state['net_payable_amt'] = number_format($sub_total, 2, '.', '') ?? 0;

        $this->state['total_qty'] = $total_qty ?? 0;
        $this->state['tot_vat_amt'] = $total_vat ?? 0;
        $this->state['tot_discount'] = $total_discount ?? 0;

        $this->state['tot_payable_amt'] = number_format(((float)$shipping_amt + (float)$sub_total), 2, '.', '');
        $this->due_amt = number_format(((float)$this->state['tot_payable_amt'] - (float)$this->pay_amt), 2, '.', '');
    }

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

        if (count($this->saleCart) > 0) {

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

                $tran_mst_id = DB::table('INV_SALES_MST')->insertGetId($this->state, 'tran_mst_id');

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

                session()->flash('status', 'New sale created successfully');
                return $this->redirect(route('sale'), navigate:true);

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
        return view('livewire.dashboard.sales.sales.sales-form');
    }
}
