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
                <x-input required_mark='true' wire:model='state.tran_date' name='tran_date' type='date'
                    label='Purchase date' />
            </div>

            <div class="col-md-2">
                <div class="form-group mb-3">
                    <label for="">Status<span style="color: red"> * </span></label>
                    <select wire:model='state.status' class="form-select" id='status'>
                        <option value="1">Received</option>
                        <option value="2">Partial</option>
                        <option value="3">Pending</option>
                        <option value="4">Ordered</option>
                    </select>
                    @error('status')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
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
            <div class="col-md-12 mt-2">
                <div class="form-group mb-3">
                    <label for=""> Purchase memo search </label>
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:5%; border: 1px solid #DFE2E6;padding: 10px;border-radius: 4px;">
                            <i style="font-size: 35px" class="fa fa-barcode"></i>
                        </div>
                        <div class="position-relative" @click.away="edit = false" style="width: 90%">
                            <input autocomplete="off" autofocus='true'
                                placeholder="please type purchase memo or code or scan barcode" @input="edit = true"
                                style="padding: 1rem" wire:model.live.debounce.500ms='purchasesearch'
                                wire:keydown.escape="hideDropdown" wire:keydown.tab="hideDropdown"
                                wire:keydown.Arrow-Up="decrementHighlight" wire:keydown.Arrow-Down="incrementHighlight"
                                wire:keydown.enter.prevent="selectAccount" type='text' class="form-control">

                            <div class="position-absolute w-full"
                                style="width:100%; max-height: 250px; overflow-y:scroll">
                                @if (count($resultPurchases) > 0)
                                <div x-show="edit === true" class="search__container">
                                    @forelse ($resultPurchases as $pk => $resultPurchase)
                                    <p class="productRow" wire:click='searchRowSelect({{ $pk }})' wire:key='{{ $pk }}'
                                        @click="edit = false"
                                        style="@if($searchSelect === $pk) background: #1e418685; @endif">
                                        {{ $resultPurchase->memo_no }}
                                        | {{ date('d-M-Y', strtotime($resultPurchase->tran_date)) }}
                                        | <b>Amt:</b> {{ number_format($resultPurchase->tot_payable_amt, 2, '.', '') }}

                                    </p>
                                    @empty
                                    <p>No purchase</p>
                                    @endforelse

                                </div>
                                @endif
                            </div>
                        </div>
                        <div style="width:5%; border: 1px solid #DFE2E6;padding: 10px;border-radius: 4px;">
                            <i style="font-size: 35px" class="fa fa-barcode"></i>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-12 mt-4 table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr class="bg-sidebar">
                            <td class="" style="width:3%">SL</td>
                            <td class="" style="width:30%">Name</td>
                            <td class="text-center" style="width:10%">RT Qty</td>
                            <td class="text-center" style="width:10%">Price</td>
                            <td class="text-center" style="width:10%">ADJ Discount</td>
                            <td class="text-center" style="width:10%">RT Tax</td>
                            <td class="text-center" style="width:15%">RT Amount</td>
                            <td class="text-center" style="width:2%">Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($purchaseCart as $purchase_key => $purchase)
                        <tr wire:key='{{ $purchase_key }}'
                        style="
                        @if ($purchase['is_check'] == 1)
                            background: #A3D1A3
                        @endif
                        "
                        >
                            <td>{{ $purchase_key + 1 }}</td>
                            <td>
                                {{ $purchase['item_name'] }}

                                @if (@$purchase['item_size_name'])
                                | {{ $purchase['item_size_name'] }}
                                @endif
                                @if (@$purchase['color_name'])
                                | {{ $purchase['color_name'] }}
                                @endif

                            </td>
                            <td>
                                <input @if ($purchase['is_check']==0) readonly @endif
                                    wire:input.debounce.1000ms='calculation({{ $purchase_key }})' type="number"
                                    wire:model='purchaseCart.{{ $purchase_key }}.qty' class="form-control text-center">
                            </td>

                            <td>
                                <input tabindex="-1" readonly type="number"
                                    wire:model='purchaseCart.{{ $purchase_key }}.mrp_rate'
                                    class="form-control text-center">
                            </td>
                            <td>
                                <input @if ($purchase['is_check']==0) readonly @endif
                                    wire:input.debounce.500ms='calculation({{ $purchase_key }})' type="number"
                                    wire:model='purchaseCart.{{ $purchase_key }}.discount'
                                    class="form-control text-center">
                            </td>
                            <td>
                                <input tabindex="-1" readonly type="number"
                                    wire:input.debounce.500ms='calculation({{ $purchase_key }})'
                                    wire:model='purchaseCart.{{ $purchase_key }}.vat_amt'
                                    class="form-control text-center">
                            </td>
                            <td>
                                <input tabindex="-1" type="number" style="text-align: right" readonly
                                    class="form-control" wire:model='purchaseCart.{{ $purchase_key }}.line_total'>
                            </td>

                            <td>
                                <div class="text-center">
                                    <div class="d-flex justify-content-center">
                                        <input wire:click='purchaseActive({{ $purchase_key }})'
                                            wire:model='purchaseCart.{{ $purchase_key }}.is_check'
                                            class="form-check-input" type="checkbox">
                                    </div>

                                </div>
                            </td>
                        </tr>
                        @empty

                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr style="font-weight: 500; background:aliceblue">
                            <td colspan="2" style="text-align: right">Total:</td>
                            <td style="text-align: center">
                                {{ $state['total_qty'] }}
                            </td>
                            <td></td>
                            <td style="text-align: center">
                                {{ $state['tot_discount'] }}
                            </td>
                            <td style="text-align: center">
                                {{ $state['tot_vat_amt'] }}
                            </td>
                            <td style="text-align: right">
                                {{ $state['net_payable_amt'] }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="col-md-5 mt-4">
                <div style="border: 1px solid #DEE2E6; padding: 0 !important">
                    <div>
                        <h4 class="h4 text-center pt-2 pb-2" style="background: #0080005c">
                            Make receivable payment
                        </h4>
                        <h4 class="h4 text-center pt-2 pb-2" style="color: darkred">
                            @if ($pay_amt)
                            Return amount: {{ number_format($pay_amt, 2, '.', ',') }}
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
                                <option
                                {{-- @if ($supplier->st_group_id ==
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
                        <div class='row'>
                            @if ($paymentState['pay_mode'] == 2 || $paymentState['pay_mode'] == 3 ||
                            $paymentState['pay_mode'] == 6)



                            <div class="col-md-6">
                                <livewire:dashboard.purchase.purchase.pay-partial.bank />
                            </div>
                            @if ($paymentState['pay_mode'] == 2)
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
                            @endif

                            @endif
                            @if ($paymentState['pay_mode'] == 3 || $paymentState['pay_mode'] == 6 ||
                            $paymentState['pay_mode'] == 7)
                            <div class="col-md-12">
                                <x-input required_mark='' wire:model='paymentState.card_no' name='card_no' type='text'
                                    label='Card no' />
                            </div>
                            @endif
                            @if ($paymentState['pay_mode'] == 4)

                            <div class="col-md-6">
                                <livewire:dashboard.purchase.purchase.pay-partial.mobile-bank />
                            </div>
                            <div class="col-md-6">
                                <x-input required_mark='' wire:model='paymentState.mfs_acc_no' name='mfs_acc_no'
                                    type='text' label='Mobile no' />
                            </div>

                            @endif
                            @if ($paymentState['pay_mode'] == 4 || $paymentState['pay_mode'] == 5 ||
                            $paymentState['pay_mode'] == 6 || $paymentState['pay_mode'] == 3)

                            <div class="col-md-6">
                                <x-input required_mark='' wire:model='paymentState.online_trx_id' name='online_trx_id'
                                    type='text' label='Transaction no' />
                            </div>
                            <div class="col-md-6">
                                <x-input required_mark='' wire:model='paymentState.online_trx_dt' name='tran_date'
                                    type='date' label='Transaction date' />
                            </div>

                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-2"> </div>
            <div class="col-md-5 mt-4">
                <table class="table table-borderless">
                    <tbody>
                        <tr style="text-align: right">
                            <td>Return shipping</td>
                            <td>
                                <input type="number" wire:model='state.shipping_amt' style="text-align: right"
                                    class="form-control" wire:input.debounce.500ms='grandCalculation'>
                            </td>
                        </tr>
                        <tr style="text-align: right">
                            <td>Net receivable</td>
                            <td>
                                <input style="text-align: right" readonly class="form-control"
                                    wire:model='state.tot_payable_amt'>
                            </td>
                        </tr>
                        <tr style="text-align: right">
                            <td>Received amount</td>
                            <td>
                                <input type="number" style="text-align: right" class="form-control" wire:model='pay_amt'
                                    wire:input.debounce.500ms='grandCalculation'>
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
                    <label for="">Purchase return remarks </label>
                    <livewire:quill-text-editor wire:model="state.remarks" theme="snow" />
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label for="">Purchase return documents </label>
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

    $('#supplier').on('change', function(e){
        @this.set('state.p_code', e.target.value, false);
    });

    $wire.on('set_bank_code_purchase',(event)=>{
        @this.set('paymentState.bank_code', event.id, false);
    });

    $wire.on('set_mfs_code_purchase',(event)=>{
        @this.set('paymentState.mfs_id', event.id, false);
    });
</script>
@endscript
