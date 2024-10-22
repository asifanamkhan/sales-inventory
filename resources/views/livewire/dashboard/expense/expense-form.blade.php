<div>
    @push('css')
    <style>
        .productRow {
            color: white;
            cursor: pointer;
            padding: 0rem 1rem !important;
            margin-bottom: 5px !important
        }

        .ql-editor {
            height: 70px;
            max-height: 250px;
            overflow: auto;
        }

        .productRow:hover {
            background: #8f9cff
        }

        .search__container {
            background: #227CE9 !important;
            padding: 0.2rem !important;
            border-bottom-left-radius: 8px !important;
            border-bottom-right-radius: 8px !important;
        }
    </style>
    @endpush

    <div wire:loading class="spinner-border text-primary custom-loading">
        <span class="sr-only">Loading...</span>
    </div>

    <form action="" wire:submit='save'>
        <div class="row" x-data="{edit : false}">
            <div class="col-md-2">
                <x-input required_mark='true' wire:model='state.expense_date' name='expense_date' type='date'
                    label='Expense date' />
            </div>
            <div class="col-md-2">
                <div class="form-group mb-3">
                    <label for="">Status<span style="color: red"> * </span></label>
                    <select wire:model='state.status' class="form-select" id='status'>
                        <option value="1">Complete</option>
                        <option value="2">Pending</option>
                        <option value="5">Cancled</option>

                    </select>
                    @error('status')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-5">
                <div class="d-flex align-items-center">
                    <div style="width: 90%">
                        <div class="form-group mb-3" wire:ignore>
                            <label for="">Type<span style="color: red"> * </span></label>
                            <select class="form-select select2" id='type'>
                                <option value="">Select type</option>
                                @forelse ($categories as $type)
                                <option @if ($type->expense_id == @$edit_select['expense_type'])
                                    selected
                                    @endif
                                    value="{{ $type->expense_id }}">{{ $type->expense_type }}</option>
                                @empty
                                <option value=""></option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="pt-2">
                        <a class="btn btn-primary">+</a>
                    </div>
                </div>
                @error('expense_id')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
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
            <div style="text-align: right">
                <a class="btn btn-success" wire:click='addCart'>
                    <i class="fa fa-plus"></i> Add new row
                </a>
            </div>
            <div class="col-md-12 mt-2 responsive-table">
                <table class="table table-bordered">
                    <thead>
                        <tr class="bg-sidebar">
                            <td class="" style="width:5%">SL</td>
                            <td>Description</td>
                            <td class="text-center" style="width:20%">Total Amount</td>
                            <td class="text-center" style="width:2%">Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($expenseCart as $expense_key => $expense)
                        <tr wire:key='{{ $expense_key }}'>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <input type="text" class="form-control"
                                    wire:model='expenseCart.{{ $expense_key }}.description'>
                            </td>
                            <td>
                                <input wire:input.debounce.500ms='grandCalculation({{$expense_key}})' type="number"
                                    style="border: 1px solid green; text-align: right" class="form-control"
                                    wire:model='expenseCart.{{$expense_key}}.item_amount'>
                            </td>

                            <td>
                                @if ($expense_key != 0)
                                <div class="text-center">
                                    <a type="button" wire:click.prevent='removeItem({{ $expense_key }})'>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="red"
                                            class="dz-w-6 dz-h-6 dz-text-black dark:dz-text-white">
                                            <path fill-rule="evenodd"
                                                d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z"
                                                clip-rule="evenodd">
                                            </path>
                                        </svg>
                                    </a>
                                </div>
                                @endif

                            </td>
                        </tr>
                        @empty

                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr style="font-weight: 500; background:aliceblue">
                            <td colspan="2" style="text-align: right">Total:</td>
                            <td style="text-align: right">
                                {{ $state['total_amount'] }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="col-md-5 mt-4">
                @if (session('payment-error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('payment-error') }}
                </div>
                @endif
                <div style="border: 1px solid #DEE2E6; padding: 0 !important">
                    <div>
                        <h4 class="h4 text-center pt-2 pb-2" style="background: #0080005c">
                            Make Payment
                        </h4>
                        <h4 class="h4 text-center pt-2 pb-2" style="color: darkred">
                            @if ($pay_amt)
                            Payment amount: {{ number_format($pay_amt, 2, '.', ',') }}
                            @endif
                        </h4>
                    </div>
                    <div style="padding: 5px 15px">
                        <div class="form-group mb-3">
                            <label for="">Payment method<span style="color: red"> *
                                </span></label>
                            <select wire:model.live.debounce.500ms='paymentState.pay_mode' class="form-select"
                                id='pay_mode'>
                                @forelse ($payment_methods as $method)
                                <option {{-- @if ($type->st_group_id ==
                                    @$edit_select['edit_group_id'])
                                    selected
                                    @endif --}}
                                    value="{{ $method->p_mode_id }}">{{ $method->p_mode_name }}
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
                                <livewire:dashboard.purchase.purchase.pay-partial.bank
                                    :bank_code="$paymentState['bank_code'] ?? null" />
                            </div>
                            <div class="col-md-6">
                                <x-input required_mark='' wire:model='paymentState.bank_ac_no' name='bank_ac_no'
                                    type='text' label='Bank account no' />
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
                                <livewire:dashboard.expense.expense.pay-partial.mobile-bank
                                    :mfs_id="$paymentState['mfs_id'] ?? null " />
                            </div>
                            <div class="col-md-6">
                                <x-input required_mark='' wire:model='paymentState.mfs_acc_no' name='mfs_acc_no'
                                    type='text' label='Mobile no' />
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
                                <x-input required_mark='' wire:model='paymentState.online_trx_dt' name='tran_date'
                                    type='date' label='Transaction date' />
                            </div>
                        </div>
                        @endif
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-2"> </div>
            <div class="col-md-5 mt-4">
                <table class="table table-borderless">
                    <tbody>
                        <tr style="text-align: right">
                            <td>Expense amount</td>
                            <td>
                                <input style="text-align: right" readonly class="form-control"
                                    wire:model='state.total_amount'>
                            </td>
                        </tr>
                        <tr style="text-align: right">
                            <td> Payment amount</td>
                            <td>
                                <input type="number" step="0.01" style="text-align: right" class="form-control"
                                    wire:model='pay_amt' wire:input.debounce.500ms='grandCalculation'>
                                @if (session('payment-error'))
                                <div class="" role="alert">
                                    <span style="color: red">{{ session('payment-error') }}</span>
                                </div>
                                @endif
                            </td>
                        </tr>
                        <tr style="text-align: right">
                            <td>Due amount</td>
                            <td style="text-align:right">
                                <input style="text-align: right;" readonly class="form-control" wire:model='due_amt'>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-7">
                <div class="form-group">
                    <label for="">Expense remarks </label>
                    <livewire:quill-text-editor wire:model="state.remarks" theme="snow" />
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label for="">Expense documents </label>
                    <livewire:dropzone wire:model="document" :rules="['mimes:jpg,svg,png,jpeg,pdf,docx,xlsx,csv']"
                        :key="'dropzone-two'" />
                </div>
            </div>
        </div>
        <div class="mt-2 d-flex justify-content-center">
            <button class="btn btn-primary">Save</button>
        </div>
    </form>
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

    $('#type').on('change', function(e){
        @this.set('state.expense_type', e.target.value, false);
    });

    $wire.on('set_bank_code_expense',(event)=>{
        @this.set('paymentState.bank_code', event.id, false);
    });

    $wire.on('set_mfs_code_expense',(event)=>{
        @this.set('paymentState.mfs_id', event.id, false);
    });
</script>
@endscript
