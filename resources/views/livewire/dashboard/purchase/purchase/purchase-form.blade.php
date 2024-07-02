<div>
    <style>
        p:hover {
            background: #8f9cff
        }
    </style>
    <div wire:loading class="spinner-border text-primary custom-loading">
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
    @endif
    <form action="" wire:submit='save'>
        <div class="row" x-data="{edit : false}">
            <div class="col-4">
                <div class="form-group mb-3">
                    <label for=""> Date <span style="color: red"> * </span></label>
                    <input wire:model='state.tran_date' type='date'
                        class="form-control @error('tran_date') is-invalid @enderror">
                    @error('tran_date')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-4">
                <div class="form-group mb-3" wire:ignore>
                    <label for="">Supplier<span style="color: red"> * </span></label>
                    <select class="form-select select2" id='supplier'>
                        <option value="">Select supplier</option>
                        @forelse ($suppliers as $supplier)
                        <option {{-- @if ($supplier->st_group_id == @$edit_select['edit_group_id'])
                            selected
                            @endif --}}
                            value="{{ $supplier->p_code }}">{{ $supplier->p_name }}</option>
                        @empty
                        <option value=""></option>
                        @endforelse
                    </select>
                </div>
                @error('p_code')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-4">
                <div class="form-group mb-3" wire:ignore>
                    <label for="">Warhouse<span style="color: red"> * </span></label>
                    <select class="form-select select2" id='ware_house'>
                        @forelse ($war_houses as $war_house)
                        <option {{-- @if ($supplier->st_group_id == @$edit_select['edit_group_id'])
                            selected
                            @endif --}}
                            value="{{ $war_house->war_id }}">{{ $war_house->war_name }}</option>
                        @empty
                        <option value=""></option>
                        @endforelse
                    </select>
                </div>
                @error('p_code')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-4">
                <div class="form-group mb-3">
                    <label for="">LC no</label>
                    <input wire:model='state.lc_no' type='text'
                        class="form-control @error('lc_no') is-invalid @enderror">
                    @error('lc_no')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-4">
                <div class="form-group mb-3">
                    <label for=""> Document </label>
                    <input wire:model='state.document' type='file'
                        class="form-control @error('document') is-invalid @enderror">
                    @error('document')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-4">
                <div class="form-group mb-3">
                    <label for="">Status<span style="color: red"> * </span></label>
                    <select class="form-select" id='status'>
                        <option value="1">Received</option>
                        <option value="2">Partial</option>
                        <option value="3">Pending</option>
                        <option value="4">Ordered</option>
                    </select>
                    @error('p_code')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-12 mt-2">
                <div class="form-group mb-3">
                    <label for=""> Product search </label>
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:5%; border: 1px solid #DFE2E6;padding: 10px;border-radius: 4px;">
                            <i style="font-size: 35px" class="fa fa-barcode"></i>
                        </div>
                        <div class="position-relative" @click.away="edit = false" style="width: 90%">
                            <input autocomplete="off" autofocus='true'
                                placeholder="please type product name or code or scan barcode" @input="edit = true"
                                style="padding: 1rem" wire:model.live.debounce.300ms='productsearch'
                                wire:keydown.escape="hideDropdown" wire:keydown.tab="hideDropdown"
                                wire:keydown.Arrow-Up="decrementHighlight" wire:keydown.Arrow-Down="incrementHighlight"
                                wire:keydown.enter.prevent="selectAccount" type='text'
                                class="form-control @error('product') is-invalid @enderror">
                            <div class="position-absolute w-full"
                                style="width:100%; max-height: 250px; overflow-y:scroll">
                                @if (count($resultProducts) > 0)
                                <div x-show="edit === true" style="
                                    background:#227CE9 !important;
                                    padding:0.2rem !important;
                                    border-bottom-left-radius: 8px !important;
                                    border-bottom-right-radius: 8px !important;
                                    ">
                                    @forelse ($resultProducts as $pk => $resultProduct)
                                    <p wire:click='searchRowSelect({{ $pk }})' wire:key='{{ $pk }}'
                                        @click="edit = false" style="
                                        @if($searchSelect === $pk) background: #1e418685; @endif

                                        color: white;
                                        cursor: pointer;
                                        padding:0rem 1rem !important;
                                        margin-bottom: 5px !important">
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

            <div class="col-12 mt-4 table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr class="bg-sidebar">
                            <td class="" style="width:2%">SL</td>
                            <td class="" style="width:30%">Name</td>
                            <td class="text-center" style="width:10%">Qty</td>
                            <td class="text-center" style="width:15%">Price</td>
                            <td class="text-center" style="width:10%">Discount</td>
                            <td class="text-center" style="width:15%">Tax</td>
                            <td class="text-center" style="width:15%">Total Amount</td>
                            <td class="text-center" style="width:2%">Action</td>
                        </tr>
                    </thead>
                    <tbody >
                        @forelse ($purchaseCart as $purchase_key => $purchase)
                        <tr wire:key='{{ $purchase_key }}'>
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
                                <input wire:input.debounce.300ms='calculation({{ $purchase_key }})' type="number" wire:model='purchaseCart.{{ $purchase_key }}.qty'
                                    class="form-control text-center">
                            </td>
                            <td>
                                <input readonly type="number" wire:model='purchaseCart.{{ $purchase_key }}.mrp_rate'
                                    class="form-control text-center">
                            </td>
                            <td>
                                <input wire:input.debounce.300ms='calculation({{ $purchase_key }})' type="number" wire:model='purchaseCart.{{ $purchase_key }}.discount'
                                    class="form-control text-center">
                            </td>
                            <td>
                                <input readonly type="number" wire:model='purchaseCart.{{ $purchase_key }}.vat_amt'
                                    class="form-control text-center">
                            </td>
                            <td>
                                <input style="border: 1px solid green; text-align: right" readonly class="form-control"
                                    wire:model='purchaseCart.{{ $purchase_key }}.line_total'>
                            </td>
                            <td>
                                <div class="text-center">
                                    <a type="button" wire:click.prevent='removeItem({{ $purchase_key}}, {{$purchase['st_group_item_id'] }})'>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="red"
                                            class="dz-w-6 dz-h-6 dz-text-black dark:dz-text-white">
                                            <path fill-rule="evenodd"
                                                d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z"
                                                clip-rule="evenodd"></path>
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
                                {{ $state['total_qty'] }}
                            </td>
                            <td colspan="1" style="text-align: right"></td>
                            <td style="text-align: center">
                                {{ $state['tot_discount'] }}
                            </td>
                            <td colspan="1" style="text-align: right">Sub total: </td>
                            <td style="text-align: right">
                                {{ $state['net_payable_amt'] }}
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td rowspan="4" colspan="5" style="border: none">

                            </td>
                            <td style="text-align: right">
                                Shipping
                            </td>
                            <td>
                                <input wire:model='state.shipping_amt' style="text-align: right" class="form-control"
                                    wire:input.debounce.300ms='grandCalculation'>
                            </td>
                            <td></td>
                        </tr>

                        <tr >
                            <td style="text-align:right">
                                Net payable
                            </td>
                            <td style="text-align:right">
                                <input style="text-align: right" readonly class="form-control"
                                    wire:model='state.tot_payable_amt'>
                            </td>
                            <td style=""></td>
                        </tr>
                        <tr >
                            <td style="text-align:right">
                                 Payment amount
                            </td>
                            <td style="text-align:right">
                                <input style="text-align: right" class="form-control"
                                    wire:model='state.pay_amt' wire:input.debounce.300ms='grandCalculation'>
                            </td>
                            <td style=""></td>
                        </tr>
                        <tr style="border-bottom: none">
                            <td style="border-bottom: 1px solid #DEE2E6; text-align:right">
                                 Due amount
                            </td>
                            <td style="border-bottom: 1px solid #DEE2E6; text-align:right">
                                <input style="text-align: right" readonly class="form-control"
                                    wire:model='state.due_amt'>
                            </td>
                            <td style="border-bottom: 1px solid #DEE2E6"></td>
                        </tr>

                    </tfoot>
                </table>
            </div>

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

    $('#ware_house').on('change', function(e){
        @this.set('state.war_id', e.target.value, false);
    });

</script>
@endscript
