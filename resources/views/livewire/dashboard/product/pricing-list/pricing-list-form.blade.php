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
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
    </div>
    @endif
    <form id="confirmationForm" action="">
        @if (@$product->item_name)
        <h4 style="text-align: center; font-size: 20px">
            {{$product->item_name }}
            <span style="font-size: 12px; font-style: italic">
            @if ($product->item_size_name)
            , {{ $product->item_size_name  }}
            @endif
            @if ($product->color_name)
            , {{ $product->color_name }}
            @endif
            ({{$product->item_code }})
            </span>
        </h4>
        @endif
        <div class="row">

            <div class="col-md-4">
                <x-input required_mark='true' wire:model='state.pr_rate' name='pr_rate' type='number'
                    label='Purchase rate' />
            </div>
            <div class="col-md-4">
                <x-input required_mark='' wire:model='state.dp_rate' name='dp_rate' type='number' label='Diller rate' />
            </div>
            <div class="col-md-4">
                <x-input required_mark='' wire:model='state.rp_rate' name='rp_rate' type='number' label='Retail rate' />
            </div>
            <div class="col-md-4">
                <x-input required_mark='true' wire:input.debounce.500ms='vat_calculation' wire:model='state.mrp_rate'
                    name='mrp_rate' type='number' label='MRP rate' />
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="">Stock Alert quantity </label>
                    <input wire:model='state.max_ch_qty' type='number'
                        class="form-control @error('max_ch_qty') is-invalid @enderror">
                    @error('max_ch_qty')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-3 d-flex align-items-center">
                <div class="form-group mb-3">
                    <input wire:click='vatApply' wire:model='state.vat_apply' class="form-check-input" type="checkbox">
                    <label for="">Vat applicable </label>
                    @error('vat_apply')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group mb-3">
                    <label for="">Vat rate (%) </label>
                    <input @if (@$state['vat_apply'] == false) readonly @endif wire:input.debounce.500ms='vat_calculation'
                        wire:model='state.vat_rate' type='number' step="any"
                        class="form-control @error('vat_rate') is-invalid @enderror">
                    @error('vat_rate')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label for="">Vat amount </label>
                    <input readonly wire:model='state.vat_amt' type='number'
                        class="form-control @error('vat_amt') is-invalid @enderror">
                    @error('vat_amt')
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

    // $('#product').on('change', function(e){
    //     @this.set('state.item_code', e.target.value, false);
    // });

    document.getElementById('confirmationForm').addEventListener('submit', function (e) {
            e.preventDefault(); // Prevent the form from submitting automatically

            // Show SweetAlert confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to submit the form?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, submit it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.dispatch('save_form'); // Trigger the Livewire submit function
                }
            });
        });

</script>
@endscript
