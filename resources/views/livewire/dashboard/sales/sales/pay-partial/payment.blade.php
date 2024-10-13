<div>
    <div class="p-4">
        <div wire:loading class="spinner-border text-primary custom-loading" branch="status">
            <span class="sr-only">Loading...</span>
        </div>
        @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
        </div>
        @elseif (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
        </div>
        @elseif (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('warning') }}
        </div>
        @endif
        @if ($sale_mst)
        <div class="row" style="padding: 0px 8px 2px">
            <p class="col-auto">
                Total sale:
                <span class="badge bg-primary">
                    {{ number_format($sale_mst['tot_payable_amt'], 2, '.', ',') }}
                </span>
            </p>
            <p class="col-auto">
                Return:
                <span class="badge bg-warning">
                    {{ number_format($sale_mst['prt_amt'], 2, '.', ',') }}
                </span>
            </p>
            <p class="col-auto">
                Total paid:
                <span class='badge bg-success'>
                    {{ number_format($sale_mst['tot_paid_amt'], 2, '.', ',') }}
                </span>
            </p>
            <p class="col-auto">
                Total due:
                <span class='badge bg-danger'>
                    {{ number_format($sale_mst['tot_due_amt'], 2, '.', ',') }}
                </span>
            </p>
        </div>
        @endif
        <form action="" wire:submit='save'>
            <div style="padding: 5px 15px">
                <div class="form-group mb-3">
                    <label for="">Payment method<span style="color: red"> *
                        </span></label>
                    <select wire:model.live.debounce.500ms='paymentState.pay_mode' class="form-select" id='pay_mode'>
                        @forelse ($payment_methods as $method)
                        <option value="{{ $method->p_mode_id }}">{{ $method->p_mode_name }}
                        </option>
                        @empty
                        <option value=""></option>
                        @endforelse
                    </select>
                    @error('pay_mode')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
                @if ($paymentState['pay_mode'] != 1)

                @if ($paymentState['pay_mode'] == 2)

                <div class='row'>
                    <div class="col-md-6">
                        <livewire:dashboard.sales.sales.pay-partial.bank
                            :bank_code="$paymentState['bank_code'] ?? null" />
                    </div>
                    <div class="col-md-6">
                        <x-input required_mark='' wire:model='paymentState.bank_ac_no' name='bank_ac_no' type='text'
                            label='Bank account no' />
                    </div>
                    <div class="col-md-6">
                        <x-input required_mark='' wire:model='paymentState.chq_no' name='chq_no' type='text'
                            label='Cheque no' />
                    </div>
                    <div class="col-md-6">
                        <x-input required_mark='' wire:model='paymentState.chq_date' name='chq_date' type='date'
                            label='Cheque date' />
                    </div>
                </div>
                @endif
                @if ($paymentState['pay_mode'] == 3 || $paymentState['pay_mode'] == 6 ||
                $paymentState['pay_mode'] == 7)
                <div class="col-md-12">
                    <x-input required_mark='' wire:model='paymentState.card_no' name='card_no' type='text'
                        label='Card no' />
                </div>
                @endif
                @if ($paymentState['pay_mode'] == 4)
                <div class="row">
                    <div class="col-md-6">
                        <livewire:dashboard.sales.sales.pay-partial.mobile-bank
                            :mfs_id="$paymentState['mfs_id'] ?? null " />
                    </div>
                    <div class="col-md-6">
                        <x-input required_mark='' wire:model='paymentState.mfs_acc_no' name='mfs_acc_no' type='text'
                            label='Mobile no' />
                    </div>
                </div>
                @endif
                @if ($paymentState['pay_mode'] == 4 || $paymentState['pay_mode'] == 5)
                <div class="row">
                    <div class="col-md-6">
                        <x-input required_mark='' wire:model='paymentState.online_trx_id' name='online_trx_id'
                            type='text' label='Transaction no' />
                    </div>
                    <div class="col-md-6">
                        <x-input required_mark='' wire:model='paymentState.online_trx_dt' name='tran_date' type='date'
                            label='Transaction date' />
                    </div>
                </div>
                @endif
                @endif
                <x-input required_mark='' wire:model='paymentState.tot_paid_amt' name='tot_paid_amt' type='number'
                    steps='0.01' label='Payment amount' />
            </div>
            <div class="mt-1 d-flex justify-content-center">
                <button class="btn btn-primary">Pay</button>
            </div>
        </form>
        {{-- <div class="row g-3 mb-3 align-items-center">
            <div class="col-auto">
                <input type="text" wire:model.live.debounce.300ms='search' class="form-control"
                    placeholder="search here">
            </div>
            <div class="col-auto">
                <select class="form-select" wire:model.live='pagination' name="" id="">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div> --}}
        <div class="responsive-table mt-4" style="font-size: 0.9em !important;">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="bg-sidebar">
                        <td style="">#</td>
                        <td style="">Date</td>
                        <td style="">Memo</td>
                        <td style="">Methods</td>
                        <td style="text-align: center">Details</td>
                        <td style="text-align: center">Amount</td>
                        {{-- <td class="text-center">Action</td> --}}

                    </tr>

                </thead>
                <tbody>
                    @if (count($this->resultPayments) > 0)
                    @foreach ($this->resultPayments as $key => $payment)
                    <tr wire:key='{{ $key }}'>
                        <td>{{ $this->resultPayments->firstItem() + $key }}</td>
                        <td>{{ date('d-M-y', strtotime($payment->payment_date)) }}</td>
                        <td>{{ $payment->memo_no }}</td>
                        <td>{{ $payment->p_mode_name }}</td>
                        <td>
                            @if ($payment->pay_mode == 1)
                            Payment by cash
                            @elseif ($payment->pay_mode == 2)
                            @php
                            $bank_info = DB::table('ACC_BANK_INFO')
                            ->where('bank_code', $payment->bank_code)
                            ->first();
                            @endphp
                            Bank : {{ $bank_info->bank_name }} </br>
                            Bank Account No : {{ $payment->bank_ac_no }} </br>
                            Cheque No : {{ $payment->chq_no }} </br>
                            Cheque Date : {{ $payment->chq_date }}
                            @elseif ($payment->pay_mode == 3)
                            -
                            @elseif ($payment->pay_mode == 4)
                            -
                            @endif
                        </td>
                        <td style="text-align: right">{{ number_format($payment->tot_paid_amt, 2, '.','') }}</td>
                        {{-- <td>
                            <button class="btn btn-sm btn-warning">
                                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20px" height="20px"
                                    viewBox="0 0 50 50">
                                    <path fill="white"
                                        d="M 43.050781 1.9746094 C 41.800781 1.9746094 40.549609 2.4503906 39.599609 3.4003906 L 38.800781 4.1992188 L 45.699219 11.099609 L 46.5 10.300781 C 48.4 8.4007812 48.4 5.3003906 46.5 3.4003906 C 45.55 2.4503906 44.300781 1.9746094 43.050781 1.9746094 z M 37.482422 6.0898438 A 1.0001 1.0001 0 0 0 36.794922 6.3925781 L 4.2949219 38.791016 A 1.0001 1.0001 0 0 0 4.0332031 39.242188 L 2.0332031 46.742188 A 1.0001 1.0001 0 0 0 3.2578125 47.966797 L 10.757812 45.966797 A 1.0001 1.0001 0 0 0 11.208984 45.705078 L 43.607422 13.205078 A 1.0001 1.0001 0 1 0 42.191406 11.794922 L 9.9921875 44.09375 L 5.90625 40.007812 L 38.205078 7.8085938 A 1.0001 1.0001 0 0 0 37.482422 6.0898438 z">
                                    </path>
                                </svg>
                            </button>
                        </td> --}}
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@script
<script data-navigate-once>
    document.addEventListener('livewire:navigated', () => {
        $(document).ready(function() {
            $('.select2').select2({
                theme: "bootstrap-5",
            });
        });
    });

    $wire.on('set_bank_code_sale',(event)=>{
        @this.set('paymentState.bank_code', event.id, false);
    });

    $wire.on('set_mfs_code_sale',(event)=>{
        @this.set('paymentState.mfs_id', event.id, false);
    });
</script>
@endscript
