<div class="card p-4" style="min-height: 250px">
    <div wire:loading class="spinner-border text-primary custom-loading"
        product-pricing-list-product-pricing-list="status">
        <span class="sr-only">Loading...</span>
    </div>
    @if (session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('status') }}
    </div>
    @elseif (session('error'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        {{ session('error') }}
    </div>
    @endif
    <form wire:submit='save' action="">
        <div class="row">
            <div class="col-6">
                <div class="form-group mb-3" wire:ignore>
                    <label for="">Products</label>
                    <select class="form-select select2" id='product'>
                        <option value="">Select product</option>
                        @forelse ($products as $product)
                        <option value="{{ $product->u_code }}">{{ $product->item_name }}</option>
                        @empty
                        <option value=""></option>
                        @endforelse
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group mb-3" wire:ignore>
                    <label for="">Products all items</label>
                    <select class="form-select select2" id='product_all_items'>
                        <option value="">Select product</option>
                        @forelse ($products as $product)
                        <option value="{{ $product->u_code }}">{{ $product->item_name }}</option>
                        @empty
                        <option value=""></option>
                        @endforelse
                    </select>
                </div>
                @error('item_code')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-4">
                <div class="form-group mb-3">
                    <label for="">Purchase rate <span style="color: red"> * </span></label>
                    <input wire:model='state.pr_rate' type='text'
                        class="form-control @error('pr_rate') is-invalid @enderror">
                    @error('pr_rate')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-4">
                <div class="form-group mb-3">
                    <label for="">Diller rate </label>
                    <input wire:model='state.dp_rate' type='number'
                        class="form-control @error('dp_rate') is-invalid @enderror">
                    @error('dp_rate')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-4">
                <div class="form-group mb-3">
                    <label for="">Retail rate </label>
                    <input wire:model='state.rp_rate' type='number'
                        class="form-control @error('rp_rate') is-invalid @enderror">
                    @error('rp_rate')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-4">
                <div class="form-group mb-3">
                    <label for="">MRP rate </label>
                    <input wire:input.debounce.500ms='vat_calculation' wire:model='state.mrp_rate' type='number'
                        class="form-control @error('mrp_rate') is-invalid @enderror">
                    @error('mrp_rate')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-4">
                <div class="form-group mb-3">
                    <label for="">Vat rate (%)</label>
                    <input wire:input.debounce.500ms='vat_calculation' wire:model='state.vat_rate' type='number'
                        class="form-control @error('vat_rate') is-invalid @enderror">
                    @error('vat_rate')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-4">
                <div class="form-group mb-3">
                    <label for="">Vat amount </label>
                    <input readonly wire:model='state.vat_amt' type='number'
                        class="form-control @error('vat_amt') is-invalid @enderror">
                    @error('vat_amt')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-4">
                <div class="form-group mb-3">
                    <label for="">Stock Alert quantity </label>
                    <input wire:model='state.max_ch_qty' type='number'
                        class="form-control @error('max_ch_qty') is-invalid @enderror">
                    @error('max_ch_qty')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center">
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

    $('#product').on('change', function(){
        let data = $(this).val();
        $wire.dispatch('product_change_on_pricing_list', {id: data});
    });

    $wire.on('product-varient-list-as-product',(event)=>{
        $('#product_all_items').html('');
        $('#product_all_items').append(`<option >Select product varient</option>`)
        if(event.productVarient.length > 0){
            event.productVarient.forEach(function(item) {
            $('#product_all_items').append(
                `<option value='${item.st_group_item_id}'>
                    ${item.item_name} - ${item.color_name ?? ''} - ${item.item_size_name ?? '-' }
                </option>`
            );
        });
        }
    });

    $wire.on('refresh-product-varient-list-as-product',(event)=>{
        $('#product_all_items').html('');
    });



    $('#product_all_items').on('change', function(e){
        @this.set('state.item_code', e.target.value, false);
    });
</script>
@endscript
