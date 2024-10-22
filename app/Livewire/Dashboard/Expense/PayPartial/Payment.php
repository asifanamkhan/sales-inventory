<?php

namespace App\Livewire\Dashboard\Expense\PayPartial;

use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Validator;
use App\Service\Payment as PurchasePayment;
use Illuminate\Support\Facades\Auth;

class Payment extends Component
{
    use WithPagination;

    public $search;
    public $pagination = 10;
    public $payment_methods, $purchase_mst;
    public $purchase_id;
    public $paymentState = [];

    public function paymentMethodAll()
    {
        return $this->payment_methods = DB::table('ACC_PAYMENT_MODE')
            ->get(['p_mode_id', 'p_mode_name']);
    }


    #[On('expense-payment')]
    public function purchasePayment($id)
    {
        $this->purchase_id = $id;
        $this->purchaseMst($id);
    }

    public function purchaseMst($id)
    {
        $this->purchase_mst = (array)DB::table('ACC_EXPENSE_MST as p')
            ->where('p.expense_mst_id', $id)
            ->first(['p.*']);
    }

    #[Computed]
    public function resultPayments()
    {
        // $this->purchase_mst = (array)DB::table('INV_PURCHASE_MST as p')
        //     ->where('p.tran_mst_id', $this->purchase_id)
        //     ->first(['p.*']);

        $payments = DB::table('ACC_PAYMENT_INFO as p')
            ->where('p.ref_memo_no', @$this->purchase_mst['memo_no'])
            ->where('p.tran_mst_id', $this->purchase_id)
            ->leftJoin('ACC_PAYMENT_MODE as pm', function ($join) {
                $join->on('pm.p_mode_id', '=', 'p.pay_mode');
            });

        $payments
            ->orderBy('p.memo_no', 'DESC')
            ->select([
                'p.*',
                'pm.p_mode_name'
            ]);

        if ($this->search) {
            $payments
                ->orwhere(DB::raw('lower(p.memo_no)'), 'like', '%' . strtolower($this->search) . '%')
                ->orWhere('p.tot_payable_amt', 'like', '%' . $this->search . '%')
                ->orWhere('p.tot_paid_amt', 'like', '%' . $this->search . '%');
        }

        return $payments->paginate($this->pagination);
    }

    public function mount()
    {
        $this->paymentState['pay_mode'] = 1;
        $this->paymentState['tot_paid_amt'] = 0;
        $this->paymentMethodAll();
    }

    public function save()
    {

        Validator::make($this->paymentState, [
            'tot_paid_amt' => 'required',
            'pay_mode' => 'required',

        ])->validate();

        if ((float)$this->paymentState['tot_paid_amt'] == 0 || (float)$this->paymentState['tot_paid_amt'] > (float)$this->purchase_mst['tot_due_amt']) {
            session()->flash('error', 'Payment amount is incorrect');
        } else {
            DB::beginTransaction();
            try {
                $due_amt = (float)$this->purchase_mst['tot_due_amt'] - (float)$this->paymentState['tot_paid_amt'];
                DB::table('ACC_EXPENSE_MST as p')
                    ->where('p.expense_mst_id', $this->purchase_id)
                    ->update([
                        'tot_due_amt' => $due_amt,
                        'tot_paid_amt' => (float)$this->purchase_mst['tot_paid_amt'] + (float)$this->paymentState['tot_paid_amt'],
                    ]);
                $payment_info = [
                    'tran_mst_id' => $this->purchase_id,
                    'tran_type' => 'PR',
                    'payment_date' => Carbon::now()->toDateString(),
                    'p_code' => $this->purchase_mst['expense_type'],
                    'pay_mode' => $this->paymentState['pay_mode'],
                    'tot_payable_amt' => $this->purchase_mst['total_amount'],
                    'discount' => 0,
                    'vat_amt' => 0,
                    'net_payable_amt' => $this->purchase_mst['total_amount'],
                    'tot_paid_amt' => $this->paymentState['tot_paid_amt'] ?? 0,
                    'due_amt' => $due_amt,
                    'user_id' => $this->purchase_mst['employee_id'],
                    'ref_memo_no' => $this->purchase_mst['memo_no'],
                    'payment_status' => PurchasePayment::PaymentCheck(($due_amt)),
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

                $pay_id = DB::table('ACC_PAYMENT_INFO')
                    ->insertGetId($payment_info, 'payment_no');

                $pay_memo = DB::table('ACC_PAYMENT_INFO')
                    ->where('payment_no', $pay_id)
                    ->first()
                    ->memo_no;

                DB::table('ACC_VOUCHER_INFO')->insert([
                    'voucher_date' => Carbon::now()->toDateString(),
                    'voucher_type' => 'CR',
                    'narration' => 'expense payment vouchar',
                    'amount' => $this->paymentState['tot_paid_amt'],
                    'created_by' => Auth::user()->id,
                    'tran_type' => 'PR',
                    'ref_memo_no' => $this->purchase_mst['memo_no'],
                    'account_code' => 1030,
                    'ref_pay_no' => $pay_memo,
                    'cash_type' => 'OUT',
                ]);

                DB::commit();

                session()->flash('status', 'New Payment made successfully');
                $this->dispatch('expense-all');
                $this->paymentState['tot_paid_amt'] = 0;
                $this->purchaseMst($this->purchase_id);
                $this->paymentState['pay_mode'] = 1;
            } catch (\Exception $exception) {
                DB::rollback();
                session()->flash('error', $exception);
            }
        }
    }
    public function render()
    {
        return view('livewire.dashboard.expense.pay-partial.payment');
    }
}