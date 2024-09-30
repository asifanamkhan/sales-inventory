<div>

    <div class="form-group mb-3" wire:ignore>
        <label for="">Mobile bank<span style="color: red"> *
            </span></label>
        <select class="form-select select2" id='mfs_id'>
            <option value="">Select mobile bank</option>
            @forelse ($mfs as $mf)
            <option
            
            @if (@$mfs_id == $mf->mfs_id)
                selected
            @endif

                value="{{ $mf->mfs_id }}">{{ $mf->mfs_name }}
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

    $('#mfs_id').on('change', function(e){
        let data = $(this).val();
        $wire.dispatch('set_mfs_code_purchase', {id: data});
    });
</script>
@endscript

