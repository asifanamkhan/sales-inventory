<div>

    <div class="form-group mb-3" wire:ignore>
        <label for="">Bank<span style="color: red"> *
            </span></label>
        <select class="form-select select2" id='bank_id'>
            <option value="">Select bank</option>
            @forelse ($banks as $bank)
            <option
            @if (@$bank_code == $bank->bank_code)
                selected
            @endif
                value="{{ $bank->bank_code }}">{{ $bank->bank_name }}
            </option>
            @empty
            <option value=""></option>
            @endforelse
        </select>

    </div>
    @error('pay_mode')
    <small class="form-text text-danger">{{ $message }}</small>
    @enderror

</div>

@script
<script data-navigate-once>
    $(document).ready(function() {
        $('.select2').select2({
            theme: "bootstrap-5",
        });
    });

    $('#bank_id').on('change', function(e){
        let data = $(this).val();
        $wire.dispatch('set_bank_code_sale', {id: data});
    });
</script>
@endscript
