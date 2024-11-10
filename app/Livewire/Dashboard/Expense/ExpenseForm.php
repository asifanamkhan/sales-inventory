<?php

namespace App\Livewire\Dashboard\Expense;


use App\Service\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExpenseForm extends Component
{
    public $state = [];
    public $edit_select = [];
    public $expense_id;
    public $document = [];
    public $paymentState = [];
    public $categories, $payment_methods, $payment_info;
    public $expenseCart = [];
    public $pay_amt, $due_amt, $account_code;


    public function categoriesAll()
    {
        return $this->categories = DB::table('ACC_EXPENSES_LIST')
            ->orderBy('expense_id', 'DESC')
            ->get();
    }

    public function paymentMethodAll()
    {
        return $this->payment_methods = DB::table('ACC_PAYMENT_MODE')
            ->get(['p_mode_id', 'p_mode_name']);
    }

    public function mount($expense_id)
    {
        if ($expense_id) {
            $this->expense_id = $expense_id;
            $tran_mst = DB::table('ACC_EXPENSE_MST')
                ->where('expense_mst_id', $expense_id)
                ->first();
            // dd($tran_mst);
            $this->state['total_amount'] = $tran_mst->total_amount;
            $this->state['status'] = $tran_mst->status;
            $this->state['expense_type'] = $tran_mst->expense_type;
            $this->state['remarks'] = $tran_mst->remarks;
            $this->state['expense_date'] = Carbon::parse($tran_mst->expense_date)->toDateString();

            $this->pay_amt = $tran_mst->tot_paid_amt;
            $this->due_amt = $tran_mst->tot_due_amt;

            $this->edit_select['expense_type'] = $tran_mst->expense_type;


            $resultPay = DB::table('ACC_PAYMENT_INFO')
                ->where('tran_mst_id', $expense_id)
                ->first();

            if ($resultPay) {
                $this->paymentState['pay_mode'] = $resultPay->pay_mode;

                if ($resultPay->pay_mode == 2) {
                    $this->paymentState['bank_code'] = $resultPay->bank_code;
                    $this->paymentState['bank_ac_no'] = $resultPay->bank_ac_no;
                    $this->paymentState['chq_no'] = $resultPay->chq_no;
                    $this->paymentState['chq_date'] = Carbon::parse($resultPay->chq_date)->toDateString();
                }
                if ($resultPay->pay_mode == 3) {
                    $this->paymentState['card_no'] = $resultPay->card_no;
                }
                if ($resultPay->pay_mode == 4) {
                    $this->paymentState['mfs_id'] = $resultPay->mfs_id;
                    $this->paymentState['mfs_acc_no'] = $resultPay->mfs_acc_no;
                }

                if ($this->paymentState['pay_mode'] == 4 || $this->paymentState['pay_mode'] == 5) {
                    $this->paymentState['online_trx_id'] = $resultPay->online_trx_id;
                    $this->paymentState['online_trx_dt'] = Carbon::parse($resultPay->online_trx_dt)->toDateString();
                }
            } else {
                $this->paymentState['pay_mode'] = 1;
            }


            // dd($resultPay);

            $resultDtls = DB::table('ACC_EXPENSE_DTLS as p')
                ->where('p.expense_mst_id', $expense_id)
                ->get();

            $this->account_code = $resultDtls[0]->account_code;



            foreach ($resultDtls as $resultDtl) {
                $this->expenseCart[] = [
                    'description' => $resultDtl->description,
                    'item_amount' => $resultDtl->item_amount,
                ];
            }
        } else {

            $this->state['total_amount'] = 0;
            $this->state['status'] = 1;
            $this->state['expense_date'] = Carbon::now()->toDateString();
            $this->paymentState['pay_mode'] = 1;
            $this->expenseCart[] = [
                'description' => '',
                'item_amount' => 0,
            ];
        }

        $this->categoriesAll();
        $this->paymentMethodAll();
    }

    public function addCart()
    {
        $this->expenseCart[] = [
            'description' => '',
            'item_amount' => 0,
        ];
    }

    public function removeItem($key)
    {
        unset($this->expenseCart[$key]);
        $this->grandCalculation();
    }


    public function grandCalculation()
    {
        $total_amount = 0;
        foreach ($this->expenseCart as $value) {
            $total_amount += (float)$value['item_amount'] ?? 0;
        }

        $this->state['total_amount'] = number_format($total_amount, 2, '.', '');

        if ($this->pay_amt <= $this->state['total_amount']) {
            $this->due_amt = number_format(((float)$this->state['total_amount'] - (float)$this->pay_amt), 2, '.', '');
        } else {
            $this->pay_amt = $this->state['total_amount'];
            $this->due_amt = 0;
            session()->flash('payment-error', 'Payment amt cant bigger than net amount');
        }
    }

    public function save()
    {

        Validator::make($this->state, [
            'expense_date' => 'required|date',
            'expense_type' => 'required|numeric',
            'total_amount' => 'required|numeric',

        ])->validate();

        if (count($this->expenseCart) > 0) {

            // dd(
            //     $this->state,
            //     $this->paymentState,
            //     $this->expenseCart,
            // );

            DB::beginTransaction();
            try {
                $this->state['employee_id'] = Auth::user()->id;
                $this->state['comp_id'] = 1;
                $this->state['branch_id'] = 1;
                $this->state['tot_due_amt'] = $this->due_amt;
                $this->state['tot_paid_amt'] = $this->pay_amt;
                $this->state['payment_status'] = Payment::PaymentCheck($this->due_amt);
                // dd($this->state);

                if ($this->expense_id) {
                    DB::table('ACC_EXPENSE_MST')
                        ->where('expense_mst_id', $this->expense_id)
                        ->update($this->state);

                    DB::table('ACC_EXPENSE_DTLS')
                        ->where('expense_mst_id', $this->expense_id)
                        ->delete();

                    $expense_mst_id = $this->expense_id;
                } else {

                    $expense_mst_id = DB::table('ACC_EXPENSE_MST')
                        ->insertGetId($this->state, 'expense_mst_id');
                }

                foreach ($this->expenseCart as $key => $value) {
                    DB::table('ACC_EXPENSE_DTLS')->insert([
                        'expense_mst_id' => $expense_mst_id,
                        'item_name' => 'item_name',
                        'item_amount' => $value['item_amount'],
                        'description' => $value['description'],
                        'account_code' => $this->account_code,
                        'item_date' => $this->state['expense_date'],
                    ]);
                }

                $ref_memo_no = DB::table('ACC_EXPENSE_MST')
                    ->where('expense_mst_id', $expense_mst_id)
                    ->first();
                //voucher
                if ($this->expense_id) {
                    DB::table('ACC_VOUCHER_INFO')
                        ->where('ref_memo_no', $ref_memo_no->memo_no)
                        ->where('ref_pay_no', null)
                        ->where('cash_type', null)
                        ->update([
                            'amount' => $this->state['total_amount'],
                        ]);
                } else {
                    DB::table('ACC_VOUCHER_INFO')->insert([
                        'voucher_date' => $this->state['expense_date'],
                        'voucher_type' => 'DR',
                        'narration' => 'expense vouchar',
                        'amount' => $this->state['total_amount'],
                        'created_by' => $this->state['employee_id'],
                        'tran_type' => 'EXP',
                        'ref_memo_no' => $ref_memo_no->memo_no,
                        'account_code' => $this->account_code,
                    ]);
                }

                if ($this->pay_amt && $this->pay_amt > 0) {

                    $this->payment_info = [
                        'tran_mst_id' => $expense_mst_id,
                        'tran_type' => 'EXP',
                        'payment_date' => $this->state['expense_date'],
                        'p_code' => $this->state['expense_type'],
                        'pay_mode' => $this->paymentState['pay_mode'],
                        'tot_payable_amt' => $this->state['total_amount'],
                        'discount' => 0,
                        'vat_amt' => 0,
                        'net_payable_amt' => $this->state['total_amount'],
                        'tot_paid_amt' => $this->pay_amt ?? 0,
                        'due_amt' => $this->due_amt,
                        'user_id' => Auth::user()->id,
                        'ref_memo_no' => $ref_memo_no->memo_no,
                        'payment_status' => Payment::PaymentCheck($this->due_amt),
                    ];

                    $this->paymentStatusFunc();

                    $acc_tran = DB::table('ACC_PAYMENT_INFO')
                        ->where('tran_type', 'EXP')
                        ->where('tran_mst_id', $this->expense_id)
                        ->first();

                    if ($this->expense_id && $acc_tran) {

                        DB::table('ACC_PAYMENT_INFO')
                            ->where('tran_type', 'EXP')
                            ->where('tran_mst_id', $this->expense_id)
                            ->update($this->payment_info);

                        DB::table('ACC_VOUCHER_INFO')
                            ->where('ref_pay_no', $acc_tran->memo_no)
                            ->update([
                                'amount' => $this->pay_amt,
                            ]);
                    } else {

                        $pay_id = DB::table('ACC_PAYMENT_INFO')
                            ->insertGetId($this->payment_info, 'payment_no');

                        $pay_memo = DB::table('ACC_PAYMENT_INFO')
                            ->where('payment_no', $pay_id)
                            ->first()
                            ->memo_no;

                        DB::table('ACC_VOUCHER_INFO')->insert([
                            'voucher_date' => $this->state['expense_date'],
                            'voucher_type' => 'CR',
                            'narration' => 'expense vouchar',
                            'amount' => $this->pay_amt,
                            'created_by' => Auth::user()->id,
                            'tran_type' => 'EXP',
                            'ref_memo_no' => $ref_memo_no->memo_no,
                            'account_code' => $this->account_code,
                            'ref_pay_no' => $pay_memo,
                            'cash_type' => 'OUT',
                        ]);
                    }
                }

                DB::commit();

                session()->flash('status', 'New expense created successfully');
                return $this->redirect(route('expense'), navigate: true);
            } catch (\Exception $exception) {
                DB::rollback();
                session()->flash('error', $exception);
            }
        } else {
            session()->flash('error', '*At least one product need to added');
        }
    }

    public function paymentStatusFunc(){
        if ($this->paymentState['pay_mode'] == 2) {
            $this->payment_info['bank_code'] = @$this->paymentState['bank_code'] ?? '';
            $this->payment_info['bank_ac_no'] = @$this->paymentState['bank_ac_no'] ?? '';
            $this->payment_info['chq_no'] = @$this->paymentState['chq_no'] ?? '';
            $this->payment_info['chq_date'] = @$this->paymentState['chq_date'] ?? '';
        }

        if ($this->paymentState['pay_mode'] == 3 || $this->paymentState['pay_mode'] == 6 || $this->paymentState['pay_mode'] == 7) {
            $this->payment_info['card_no'] = @$this->paymentState['card_no'] ?? '';
        }

        if ($this->paymentState['pay_mode'] == 4) {
            $this->payment_info['mfs_id'] = @$this->paymentState['mfs_id'] ?? '';
            $this->payment_info['mfs_acc_no'] = @$this->paymentState['mfs_acc_no'] ?? '';
        }
        if ($this->paymentState['pay_mode'] == 4 || $this->paymentState['pay_mode'] == 5) {
            $this->payment_info['online_trx_id'] = @$this->paymentState['online_trx_id'] ?? '';
            $this->payment_info['online_trx_dt'] = @$this->paymentState['online_trx_dt'] ?? '';
        }
    }


    public function render()
    {
        return view('livewire.dashboard.expense.expense-form');
    }
}