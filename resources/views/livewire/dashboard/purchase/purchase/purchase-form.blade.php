<div>
    <style>
        p:hover {
            background: #8f9cff
        }
    </style>
    <div wire:loading class="spinner-border text-primary custom-loading">
        <span class="sr-only">Loading...</span>
    </div>
    <form action="" wire:submit='save'>
        <div class="row" x-data="{edit : false}">
            <div class="col-3">
                <div class="form-group mb-3">
                    <label for=""> Date <span style="color: red"> * </span></label>
                    <input wire:model='state.tran_date' type='date'
                        class="form-control @error('tran_date') is-invalid @enderror">
                    @error('tran_date')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-3">
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
            <div class="col-3">
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
            
            <div class="col-3">
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
                        <div class="position-relative" @click.away="edit = false" style="width: 95%">
                            <input autocomplete="off" placeholder="please type product name or code or scan barcode"
                                @input="edit = true" style="padding: 1rem"
                                wire:model.live.debounce.300ms='productsearch' wire:keydown.escape="hideDropdown"
                                wire:keydown.tab="hideDropdown" wire:keydown.Arrow-Up="decrementHighlight"
                                wire:keydown.Arrow-Down="incrementHighlight" wire:keydown.enter.prevent="selectAccount"
                                type='text' class="form-control @error('product') is-invalid @enderror">
                            <div class="position-absolute w-full"
                                style="width:100%; max-height: 250px; overflow-y:scroll">
                                @if (count($resultProducts) > 0)
                                <div x-show="edit === true" style="
                                    background: #3C50E0;
                                    padding:0.2rem !important;
                                    border-bottom-left-radius: 8px !important;
                                    border-bottom-right-radius: 8px !important;
                                    ">
                                    @forelse ($resultProducts as $k => $resultProduct)
                                    <p wire:key='{{ $k }}' @click="edit = false" style="
                                        {{ $searchSelect === $k ? ' background: #173777;' : ' background: #3C50E0;' }}
                                        color: white;
                                        cursor: pointer;
                                        padding:0rem 1rem !important;
                                        margin-bottom: 5px !important">
                                        {{ $resultProduct->item_name }}
                                    </p>
                                    @empty
                                    <p>No product</p>
                                    @endforelse

                                </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-12 mt-2 table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr class="bg-sidebar">
                            <td>SL</td>
                            <td>Name</td>
                            <td>Qty</td>
                            <td>Price</td>
                            <td>Discount</td>
                            <td>Tax</td>
                            <td>Total Amount</td>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($purchaseCart as $purchase_key => $purchase)
                            <tr wire:key='purchase_key'>
                                <td>{{ $purchase_key + 1 }}</td>
                                <td>{{ $purchase['item_name'] }}</td>
                                <td>
                                    <input type="number" wire:model='purchaseCart.{{ $purchase_key }}.qty' class="form-control">
                                </td>
                            </tr>
                        @empty

                        @endforelse
                    </tbody>
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

    ware_house
</script>
@endscript
