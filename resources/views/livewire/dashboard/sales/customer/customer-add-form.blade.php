<div>
    <form wire:submit='save'>
        <div class="row p-4">
            @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
            </div>
            @elseif (session('error'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('status') }}
            </div>
            @endif
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="">Name </label>
                    <input value="Walk In Customer" wire:model='state.customer_name' type='text'
                        class="form-control @error('customer_name') is-invalid @enderror">
                    @error('customer_name')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="">Phone <span style="color: red"> * </span></label>
                    <input wire:model='state.phone_no' type='text' class="form-control @error('phone_no') is-invalid @enderror">
                    @error('phone_no')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="">Status <span style="color: red"> * </span></label>
                    <select class="form-select @error('status') is-invalid @enderror" wire:model='state.status' name="" id="">
                        <option name="" id="">Select Status</option>
                        <option selected value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    @error('status')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3" wire:ignore>
                    <label for="">customer type <span style="color: red"> * </span></label>
                    <select class="form-select select2" id='customer_type'>
                        <option value="">Select type</option>
                        @forelse ($customer_types as $type)
                        <option value="{{$type->customer_type_code }}">{{ $type->customer_type_name }}</option>
                        @empty
                        <option value=""></option>
                        @endforelse
                    </select>

                </div>
                @error('customer_type')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="">Email</label>
                    <input wire:model='state.email' type='text'
                        class="form-control @error('email') is-invalid @enderror">
                    @error('email')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="">Address</label>
                    <input wire:model='state.customer_address' type='text'
                        class="form-control @error('customer_address') is-invalid @enderror">
                    @error('customer_address')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>
        <div class="mt-2 mb-2 d-flex justify-content-center">
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
        
        $('#customer_type').val(4).trigger('change');
    })



    $('#customer_type').on('change', function(e){
        let data = $(this).val();
        @this.set('state.customer_type', e.target.value, false);
    })



</script>
@endscript

