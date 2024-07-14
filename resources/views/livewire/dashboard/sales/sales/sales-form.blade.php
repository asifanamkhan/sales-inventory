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
                    label='Sale date' />
            </div>
            <div class="col-md-3">
                <div class="form-group mb-3" wire:ignore>
                    <label for="">Warhouse<span style="color: red"> * </span></label>
                    <select class="form-select" id='ware_house'>
                        @forelse ($war_houses as $war_house)
                        <option {{-- @if ($customer->st_group_id == @$edit_select['edit_group_id'])
                            selected
                            @endif --}}
                            value="{{ $war_house->war_id }}">{{ $war_house->war_name }}</option>
                        @empty
                        <option value=""></option>
                        @endforelse
                    </select>
                </div>
                @error('war_id')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-3">
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
            <div class="col-md-4">
                <div class="form-group mb-3" wire:ignore>
                    <label for="">Customer<span style="color: red"> * </span></label>
                    <select class="form-select select2" id='customer'>
                        <option value="">Select customer</option>
                        @forelse ($customers as $customer)
                        <option {{-- @if ($customer->st_group_id == @$edit_select['edit_group_id'])
                            selected
                            @endif --}}
                            value="{{ $customer->customer_id }}">{{ $customer->customer_name }}</option>
                        @empty
                        <option value=""></option>
                        @endforelse
                    </select>
                </div>
                @error('customer_id')
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

            <div class="col-md-12 mt-2">
                <div class="form-group mb-3">
                    <label for=""> Product search </label>
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:5%; border: 1px solid #DFE2E6;padding: 10px;border-radius: 4px;">
                            <i style="font-size: 35px" class="fa fa-barcode"></i>
                        </div>
                        <div class="position-relative" @click.away="edit = false" style="width: 90%">
                            <input autocomplete="off" autofocus='true'
                                placeholder="please type product name or code or scan barcode" @input="edit = true"
                                style="padding: 1rem" wire:model.live.debounce.1000ms='productsearch'
                                wire:keydown.escape="hideDropdown" wire:keydown.tab="hideDropdown"
                                wire:keydown.Arrow-Up="decrementHighlight" wire:keydown.Arrow-Down="incrementHighlight"
                                wire:keydown.enter.prevent="selectAccount" type='text' class="form-control">

                            <div class="position-absolute w-full"
                                style="width:100%; max-height: 250px; overflow-y:scroll">
                                @if (count($resultProducts) > 0)
                                <div x-show="edit === true" class="search__container">
                                    @forelse ($resultProducts as $pk => $resultProduct)
                                    <p class="productRow" wire:click='searchRowSelect({{ $pk }})' wire:key='{{ $pk }}'
                                        @click="edit = false"
                                        style="@if($searchSelect === $pk) background: #1e418685; @endif">
                                        {{ $resultProduct->item_name }}
                                        @if (@$resultProduct->item_size_name)
                                        | {{ $resultProduct->item_size_name }}
                                        @endif
                                        @if (@$resultProduct->color_name)
                                        | {{ $resultProduct->color_name}}
                                        @endif
                                    </p>
                                    @empty
                                    <p>No product</p>
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
                            <td class="" style="width:40%">Name</td>

                            <td class="text-center" style="width:10%">Qty</td>
                            <td class="text-center" style="width:10%">Price</td>
                            <td class="text-center" style="width:10%">Discount</td>
                            <td class="text-center" style="width:10%">Tax</td>
                            <td class="text-center" style="width:15%">Total Amount</td>
                            <td class="text-center" style="width:2%">Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($saleCart as $sale_key => $sale)
                        <tr wire:key='{{ $sale_key }}'>
                            <td>{{ $sale_key + 1 }}</td>
                            <td>
                                {{ $sale['item_name'] }}

                                @if (@$sale['item_size_name'])
                                | {{ $sale['item_size_name'] }}
                                @endif
                                @if (@$sale['color_name'])
                                | {{ $sale['color_name'] }}
                                @endif

                            </td>

                            <td>
                                <input wire:input.debounce.1000ms='qtyCalculation({{ $sale['st_group_item_id'] }},{{ $sale_key }})'
                                    type="number" wire:model='saleCart.{{ $sale_key }}.qty'
                                    class="form-control text-center">
                            </td>
                            <td>
                                <input tabindex="-1" readonly type="number"
                                    wire:model='saleCart.{{ $sale_key }}.mrp_rate' class="form-control text-center">
                            </td>
                            <td>
                                <input wire:input.debounce.1000ms='calculation({{ $sale_key }})' type="number"
                                    wire:model='saleCart.{{ $sale_key }}.discount' class="form-control text-center">
                            </td>
                            <td>
                                <input tabindex="-1" readonly type="number"
                                    wire:model='saleCart.{{ $sale_key }}.vat_amt' class="form-control text-center">
                            </td>
                            <td>
                                <input tabindex="-1" type="number" style="border: 1px solid green; text-align: right"
                                    readonly class="form-control" wire:model='saleCart.{{ $sale_key }}.line_total'>
                            </td>
                            <td>
                                <div class="text-center">
                                    <a type="button" wire:click.prevent='removeItem(
                                    {{ $sale_key }} ,
                                     {{ $sale['st_group_item_id'] }})'>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="red"
                                            class="dz-w-6 dz-h-6 dz-text-black dark:dz-text-white">
                                            <path fill-rule="evenodd"
                                                d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z"
                                                clip-rule="evenodd">
                                            </path>
                                        </svg>
                                    </a>
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
                                {{ $state['total_qty'] }} </td>
                            <td colspan="1" style="text-align: right"></td>
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
                            <select wire:model.live.debounce.1000ms='paymentState.pay_mode' class="form-select"
                                id='pay_mode'>
                                @forelse ($payment_methods as $method)
                                <option {{-- @if ($customer->st_group_id ==
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
                                <livewire:dashboard.sales.sales.pay-partial.bank />
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
                                <livewire:dashboard.sales.sales.pay-partial.mobile-bank />
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
                            <td>Shipping</td>
                            <td>
                                <input type="number" wire:model='state.shipping_amt' style="text-align: right"
                                    class="form-control" wire:input.debounce.1000ms='grandCalculation'>
                            </td>
                        </tr>
                        <tr style="text-align: right">
                            <td>Net payable</td>
                            <td>
                                <input style="text-align: right" readonly class="form-control"
                                    wire:model='state.tot_payable_amt'>
                            </td>
                        </tr>
                        <tr style="text-align: right">
                            <td> Payment amount</td>
                            <td>
                                <input type="number" style="text-align: right" class="form-control" wire:model='pay_amt'
                                    wire:input.debounce.1000ms='grandCalculation'>
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
            <div class="col-md-7 mt-2">
                <div class="form-group">
                    <label for="">Sale remarks </label>
                    <livewire:quill-text-editor wire:model="state.remarks" theme="snow" />
                </div>
            </div>
            <div class="col-md-5 mt-2">
                <div class="form-group">
                    <label for="">Sale documents </label>
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

    $('#customer').on('change', function(e){
        @this.set('state.customer_id', e.target.value, false);
    });

    $wire.on('set_bank_code_sale',(event)=>{
        @this.set('paymentState.bank_code', event.id, false);
    });

    $wire.on('set_mfs_code_sale',(event)=>{
        @this.set('paymentState.mfs_id', event.id, false);
    });
</script>
@endscript
