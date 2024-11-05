<div class="row">
    <div class="col-md-4">
        @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
        </div>
        @elseif (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
        </div>
        @endif
        <div class="form-group mb-3" wire:ignore>
            <label for="">Size </label>
            <select class="form-select select2" id='product_size'>
                <option value="">Select size</option>
                @forelse ($product_size as $size)
                <option value="{{ $size->item_size_code }}">{{ $size->item_size_name }}</option>
                @empty
                <option value=""></option>
                @endforelse
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3" wire:ignore>
            <label for="">Color </label>
            <select class="form-select select2" id='product_color'>
                <option value="">Select color</option>
                @forelse ($product_colors as $color)
                <option value="{{ $color->tran_mst_id }}">{{ $color->color_name }}</option>
                @empty
                <option value=""></option>
                @endforelse
            </select>
        </div>
    </div>
    <div class="col-md-4 d-flex align-items-center">
        <a wire:click.prevent='addVariant' class="btn btn btn-success">
            <i class="fa fa-plus"></i>
            Add
        </a>
    </div>
</div>
@script
<script>
    $(document).ready(function() {
        $('#product_size').select2({
            theme: "bootstrap-5",
        });
        $('#product_color').select2({
            theme: "bootstrap-5",
        });
    });

    $('#product_size').on('change', function(e){
        @this.set('product_size_id', e.target.value, false);
        @this.set('product_size_name', e.target.selectedOptions[0].text, false);
    });

    $('#product_color').on('change', function(e){
        @this.set('product_color_id', e.target.value, false);
        @this.set('product_color_name', e.target.selectedOptions[0].text, false);
    });
</script>
@endscript
