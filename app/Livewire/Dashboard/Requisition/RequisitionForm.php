<?php

namespace App\Livewire\Dashboard\Requisition;

use App\Service\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\On;

class RequisitionForm extends Component
{
    public $state = [];
    public $edit_select = [];
    public $requisition_id;
    public $document = [];
    public $paymentState = [];
    public $suppliers, $war_houses, $productsearch, $payment_methods, $lc_all;
    public $resultProducts = [];
    public $requisitionCart = [];
    public $requisitionCheck = [];
    public $searchSelect = -1;
    public $countProduct = 0;
    public $pay_amt, $due_amt, $action;


    public function suppliersAll()
    {
        return $this->suppliers = DB::table('INV_SUPPLIER_INFO')
            ->orderBy('p_code', 'DESC')
            ->get();
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
                ->get(['p.st_group_item_id', 'p.item_name', 'p.pr_rate as mrp_rate', 'p.vat_amt', 'p.item_code', 'p.color_name', 'p.item_size_name'])
                ->toArray();

            if ($result) {
                $this->resultProducts = $result;
                $this->resultAppend(0);
            } else {

                $this->resultProducts = DB::table('VW_INV_ITEM_DETAILS as p')
                    ->where(DB::raw('lower(p.item_name)'), 'like', '%' . strtolower($this->productsearch) . '%')
                    ->get(['p.st_group_item_id', 'p.item_name', 'p.pr_rate as mrp_rate', 'p.vat_amt', 'p.item_code', 'p.color_name', 'p.item_size_name'])
                    ->toArray();
            }

            $this->searchSelect = -1;
        } else {
            $this->resetProductSearch();
        }

        $this->countProduct = count($this->resultProducts);
    }

    public function mount($requisition_id = null)
    {
        if ($requisition_id) {
            $this->requisition_id = $requisition_id;
            $tran_mst = DB::table('INV_REQUISTION_MST')
                ->where('tran_mst_id', $requisition_id)
                ->first();
            // dd($tran_mst);
            $this->state['net_payable_amt'] = $tran_mst->net_payable_amt;
            $this->state['tot_payable_amt'] = $tran_mst->tot_payable_amt;
            $this->state['total_qty'] = $tran_mst->total_qty;
            $this->state['tot_discount'] = $tran_mst->tot_discount;
            $this->state['tot_vat_amt'] = $tran_mst->tot_vat_amt;
            $this->state['shipping_amt'] = $tran_mst->shipping_amt;
            $this->state['status'] = $tran_mst->status;
            $this->state['p_code'] = $tran_mst->p_code;
            $this->state['remarks'] = $tran_mst->remarks;
            $this->state['tran_date'] = Carbon::parse($tran_mst->tran_date)->toDateString();

            $this->pay_amt = $tran_mst->tot_paid_amt;
            $this->due_amt = $tran_mst->tot_due_amt;

            $this->edit_select['supplier_id'] = $tran_mst->p_code;

            $resultDtls = DB::table('INV_REQUISTION_DTL as p')
                ->where('p.tran_mst_id', $requisition_id)
                ->leftJoin('VW_INV_ITEM_DETAILS as pr', function ($join) {
                    $join->on('pr.st_group_item_id', '=', 'p.item_code');
                })
                ->get([
                    'p.pr_rate',
                    'p.vat_amt',
                    'p.tot_payble_amt',
                    'p.item_qty',
                    'p.discount',
                    'p.item_code',
                    'p.expire_date',
                    'pr.item_name',
                    'pr.color_name',
                    'pr.item_size_name',
                    'pr.vat_amt as p_vat_amt'
                ]);

            // dd($resultDtls);

            foreach ($resultDtls as $resultDtl) {
                $this->requisitionCart[] = [
                    'item_name' => $resultDtl->item_name,
                    'color_name' => $resultDtl->color_name,
                    'item_size_name' => $resultDtl->item_size_name,
                    'mrp_rate' => $resultDtl->pr_rate,
                    'vat_amt' => $resultDtl->vat_amt,
                    'p_vat_amt' =>  $resultDtl->p_vat_amt ?? 0,
                    'line_total' => $resultDtl->tot_payble_amt,
                    'qty' => $resultDtl->item_qty,
                    'discount' => $resultDtl->discount,
                    'st_group_item_id' => $resultDtl->item_code,
                    'expire_date' => $resultDtl->expire_date ? Carbon::parse($resultDtl->expire_date)->toDateString() : ''
                ];
            }

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


        $this->suppliersAll();
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
            $valid = in_array($search, $this->requisitionCheck);

            if ($valid) {
                $this->resetProductSearch();
                session()->flash('error', 'Product already added to cart');
                return 0;
            }

            $mrp = @$this->resultProducts[$key]->mrp_rate;

            if (!$mrp) {
                $this->resetProductSearch();
                session()->flash('error', 'Pricing has not added to selected product');
                return 0;
            }

            $pricing = @$this->resultProducts[$key];

            $this->requisitionCheck[] = $search;

            $line_total = (float)$pricing->mrp_rate + @$pricing->vat_amt ?? 0;

            $this->requisitionCart[] = [
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
        unset($this->requisitionCart[$key]);
        $del_key = array_search($id, $this->requisitionCheck);
        unset($this->requisitionCheck[$del_key]);
        $this->grandCalculation();
    }

    public function calculation($key)
    {
        $qty = (float)$this->requisitionCart[$key]['qty'] ?? 0;
        $mrp_rate = (float)$this->requisitionCart[$key]['mrp_rate'] ?? 0;
        $discount = (float)$this->requisitionCart[$key]['discount'] ?? 0;
        (float)$this->requisitionCart[$key]['vat_amt'] = ((float)$this->requisitionCart[$key]['p_vat_amt'] * $qty) ?? 0;
        $vat = (float)$this->requisitionCart[$key]['vat_amt'] ?? 0;
        $this->requisitionCart[$key]['line_total'] = ((($qty * $mrp_rate) + $vat) -  $discount);

        $this->grandCalculation();
    }

    public function grandCalculation()
    {
        $sub_total = 0;
        $total_qty = 0;
        $total_discount = 0;
        $total_vat = 0;
        $shipping_amt = $this->state['shipping_amt'] ?? 0;

        foreach ($this->requisitionCart as $value) {

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

        if ($this->pay_amt <= $this->state['tot_payable_amt']) {
            $this->due_amt = number_format(((float)$this->state['tot_payable_amt'] - (float)$this->pay_amt), 2, '.', '');
        } else {
            $this->pay_amt = $this->state['tot_payable_amt'];
            $this->due_amt = 0;
            session()->flash('payment-error', 'Payment amt cant bigger than net amount');
        }
    }

    #[On('save_form')]
    public function save()
    {

        Validator::make($this->state, [
            'tran_date' => 'required|date',
            'status' => 'required|numeric',
            'p_code' => 'required|numeric',
            'tot_payable_amt' => 'required|numeric',
            'net_payable_amt' => 'required|numeric',

        ])->validate();

        if (count($this->requisitionCart) > 0) {

            // dd(
            //     $this->state,
            //     $this->paymentState,
            //     $this->requisitionCart,
            // );

            $this->state['user_name'] = Auth::user()->id;
            $this->state['emp_id'] = Auth::user()->id;
            $this->state['comp_id'] = 1;
            $this->state['branch_id'] = 1;
            $this->state['tot_due_amt'] = $this->due_amt;
            $this->state['tot_paid_amt'] = $this->pay_amt;
            $this->state['payment_status'] = Payment::PaymentCheck($this->due_amt);

            $payment_info = [];

            if(!$this->requisition_id){
                if ($this->pay_amt && $this->pay_amt > 0) {

                    $payment_info = [
                        'tran_type' => 'RQ',
                        'payment_date' => $this->state['tran_date'],
                        'p_code' => $this->state['p_code'],
                        'pay_mode' => $this->paymentState['pay_mode'],
                        'tot_payable_amt' => $this->state['tot_payable_amt'],
                        'discount' => $this->state['tot_discount'],
                        'vat_amt' => $this->state['tot_vat_amt'],
                        'net_payable_amt' => $this->state['net_payable_amt'],
                        'tot_paid_amt' => $this->pay_amt ?? 0,
                        'due_amt' => $this->due_amt,
                        'user_id' => $this->state['user_name'],
                        'payment_status' => Payment::PaymentCheck($this->due_amt),
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
                    if ($this->paymentState['pay_mode'] == 4 || $this->paymentState['pay_mode'] == 5) {
                        $payment_info['online_trx_id'] = @$this->paymentState['online_trx_id'] ?? '';
                        $payment_info['online_trx_dt'] = @$this->paymentState['online_trx_dt'] ?? '';
                    }
                }
            }
            // dd($this->action);
            $this->dispatch($this->action, [
                'state' => $this->state,
                'requisitionCart' => $this->requisitionCart,
                'payment_info' => $payment_info,
            ]);
        } else {
            session()->flash('error', '*At least one product need to added');
        }
    }
    public function render()
    {
        return view('livewire.dashboard.requisition.requisition-form');
    }
}