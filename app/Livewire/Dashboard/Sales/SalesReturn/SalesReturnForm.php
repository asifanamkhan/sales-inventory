<?php

namespace App\Livewire\Dashboard\Sales\SalesReturn;

use App\Service\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SalesReturnForm extends Component
{
    public $state = [];
    public $document = [];
    public $paymentState = [];
    public $salesesearch, $payment_methods, $olSaleSearch;
    public $resultSales = [];
    public $saleCart = [];
    public $searchSelect = -1;
    public $countProduct = 0;
    public $isCheck = false;
    public $pay_amt, $due_amt;


    public function paymentMethodAll()
    {
        return $this->payment_methods = DB::table('ACC_PAYMENT_MODE')
            ->get(['p_mode_id', 'p_mode_name']);
    }

    public function updatedSalesesearch()
    {
        if ($this->salesesearch) {
            $this->olSaleSearch = $this->salesesearch;
            $result = DB::table('INV_SALES_MST as p')
                ->where('memo_no', $this->salesesearch)
                ->get()
                ->toArray();

            if ($result) {
                $this->resultSales = $result;
                $this->resultAppend(0);
            } else {
                $this->resultSales = DB::table('INV_SALES_MST as p')
                    ->where(DB::raw('lower(p.memo_no)'), 'like', '%' . strtolower($this->salesesearch) . '%')
                    ->get()
                    ->toArray();
            }

            $this->searchSelect = -1;
        } else {
            $this->resetSaleSearch();
        }

        $this->countProduct = count($this->resultSales);
    }

    public function mount($sales_return_id)
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
        $search = @$this->resultSales[$key]->tran_mst_id;

        if ($search) {
            $sale_dtls = DB::table('INV_SALES_DTL as pr')
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

                ->get(['pr.*', 'p.item_name', 'p.st_group_item_id', 's.item_size_name', 'c.color_name']);

            $this->saleCart = [];
            $this->state['customer_id'] = @$this->resultSales[$key]->customer_id;
            $this->state['war_id'] = @$this->resultSales[$key]->war_id;
            $this->state['comp_id'] = @$this->resultSales[$key]->comp_id;
            $this->state['branch_id'] = @$this->resultSales[$key]->branch_id;
            $this->state['ref_memo_no'] = @$this->resultSales[$key]->memo_no;

            foreach ($sale_dtls as $sale_dtl) {

                $return_qty = DB::table('INV_SALES_RET_DTL as pr')
                    ->where('ref_memo_no', $this->state['ref_memo_no'])
                    ->where('item_code', $sale_dtl->st_group_item_id)
                    ->sum('item_qty');

                $current_qty = (float)$sale_dtl->item_qty - $return_qty;

                if ((float)$sale_dtl->vat_amt && (float)$sale_dtl->vat_amt > 0) {
                    $p_vat_amt = (float)$sale_dtl->vat_amt / $sale_dtl->item_qty;
                }

                $this->saleCart[] = [
                    'item_name' => $sale_dtl->item_name,
                    'color_name' => $sale_dtl->color_name,
                    'item_size_name' => $sale_dtl->item_size_name,
                    'mrp_rate' => $sale_dtl->mrp_rate,
                    'vat_amt' => $sale_dtl->vat_amt,
                    'p_vat_amt' => $p_vat_amt ?? 0,
                    'line_total' => $sale_dtl->tot_payble_amt,
                    'qty' => $current_qty,
                    'p_qty' => $current_qty,
                    'discount' => $sale_dtl->discount,
                    'st_group_item_id' => $sale_dtl->st_group_item_id,
                    'is_check' => false,
                ];
            }

            $this->grandCalculation();
            $this->salesesearch = '';
            $this->resetSaleSearch();
        }
    }

    public function hideDropdown()
    {
        $this->resetSaleSearch();
    }

    //search increment decrement end

    public function resetSaleSearch()
    {
        $this->searchSelect = -1;
        $this->resultSales = [];
    }


    public function calculation($key)
    {
        $sale_qty = (float)$this->saleCart[$key]['p_qty'];
        if ((float)$this->saleCart[$key]['qty'] > $sale_qty) {
            (float)$this->saleCart[$key]['qty'] = 1;
            session()->flash('error', "Return quantity can not bigger than sale quantity ($sale_qty)");
        }

        $qty = (float)$this->saleCart[$key]['qty'] ?? 1;
        $mrp_rate = (float)$this->saleCart[$key]['mrp_rate'] ?? 0;
        $discount = (float)$this->saleCart[$key]['discount'] ?? 0;
        (float)$this->saleCart[$key]['vat_amt'] = (float)$this->saleCart[$key]['p_vat_amt'] * $qty;
        $vat =  (float)$this->saleCart[$key]['vat_amt'] ?? 0;

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

        $this->state['tot_payable_amt'] = number_format(((float)$sub_total + (float)$shipping_amt), 2, '.', '');
        $this->due_amt = number_format(((float)$this->state['tot_payable_amt'] - (float)$this->pay_amt), 2, '.', '');
    }

    public function saleActive($key)
    {
        if ($this->saleCart[$key]['is_check'] == true) {
            $this->saleCart[$key]['is_check'] = 1;
        } else {
            $this->saleCart[$key]['is_check'] = 0;
            $this->saleCart[$key]['return_qty'] =  '';
        }
        $this->calculation($key);
    }

    public function save()
    {

        Validator::make($this->state, [
            'tran_date' => 'required|date',
            'status' => 'required|numeric',
            'tot_payable_amt' => 'required|numeric',
            'net_payable_amt' => 'required|numeric',

        ])->validate();

        if (count($this->saleCart) > 0) {


            DB::beginTransaction();
            try {
                $this->state['user_name'] = Auth::user()->id;
                $this->state['emp_id'] = Auth::user()->id;
                $this->state['comp_id'] = 1;
                $this->state['branch_id'] = 1;
                $this->state['tot_due_amt'] = $this->due_amt;
                $this->state['tot_paid_amt'] = $this->pay_amt;
                $this->state['payment_status'] = Payment::ReturnPaymentCheck($this->due_amt);


                $tran_mst_id = DB::table('INV_SALES_RET_MST')->insertGetId($this->state, 'tran_mst_id');

                foreach ($this->saleCart as $key => $value) {
                    if ($value['is_check'] == 1) {
                        DB::table('INV_SALES_RET_DTL')->insert([
                            'tran_mst_id' => $tran_mst_id,
                            'item_code' => $value['st_group_item_id'],
                            'item_qty' => $value['qty'],
                            'mrp_rate' => $value['mrp_rate'],
                            'vat_amt' => $value['vat_amt'],
                            'discount' => $value['discount'],
                            'tot_payble_amt' => $value['line_total'],
                            'user_name' => $this->state['user_name'],
                            'ref_memo_no' => $this->state['ref_memo_no']
                        ]);
                    }
                }

                $prev_rt_amount = DB::table('INV_SALES_MST as p')
                    ->where('memo_no', $this->olSaleSearch)
                    ->sum('prt_amt');

                DB::table('INV_SALES_MST as p')
                    ->where('memo_no', $this->olSaleSearch)
                    ->update([
                        'prt_amt' => ($prev_rt_amount + $this->state['tot_payable_amt']),
                    ]);
                if ($this->pay_amt && $this->pay_amt > 0) {
                    $ref_memo_no = DB::table('INV_SALES_RET_MST')
                        ->where('tran_mst_id', $tran_mst_id)
                        ->first();

                    $payment_info = [
                        'tran_mst_id' => $tran_mst_id,
                        'tran_type' => 'SRT',
                        'payment_date' => $this->state['tran_date'],
                        'p_code' => $this->state['customer_id'],
                        'pay_mode' => $this->paymentState['pay_mode'],
                        'tot_payable_amt' => $this->state['tot_payable_amt'],
                        'discount' => $this->state['tot_discount'],
                        'vat_amt' => $this->state['tot_vat_amt'],
                        'net_payable_amt' => $this->state['net_payable_amt'] ?? 0,
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


                    DB::table('ACC_PAYMENT_INFO')->insert($payment_info);
                }
                DB::commit();

                session()->flash('status', 'New sales return created successfully');
                return $this->redirect(route('sale-return'), navigate: true);
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
        return view('livewire.dashboard.sales.sales-return.sales-return-form');
    }
}