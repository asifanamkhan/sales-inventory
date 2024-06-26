<div>
    <div wire:loading class="spinner-border text-primary custom-loading">
        <span class="sr-only">Loading...</span>
    </div>
    <form action="" wire:submit='save'>
        <div class="row">
            <div class="col-4">
                <div class="form-group mb-3" wire:ignore>
                    <label for="">Product group <span style="color: red"> * </span></label>
                    <select class="form-select select2" id='product_group'>
                        <option value="">Select type</option>
                        @forelse ($product_groups as $group)
                        <option value="{{ $group->st_group_id }}">{{ $group->group_name }}</option>
                        @empty
                        <option value=""></option>
                        @endforelse
                    </select>
                </div>
                @error('p_type')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-4">
                <div class="form-group mb-3" wire:ignore>
                    <label for="">Product Category <span style="color: red"> * </span></label>
                    <select class="form-select select2" id='product_category'>
                        <option value="">Select type</option>
                        @if ($product_categories)
                        @forelse ($product_categories as $category)
                        <option value="{{ $category->tran_mst_id }}">{{ $category->catagories_name }}</option>
                        @empty
                        <option value=""></option>
                        @endforelse
                        @endif
                    </select>
                </div>
                @error('p_type')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-4">
                <div class="form-group mb-3" wire:ignore>
                    <label for="">Product Brand </label>
                    <select class="form-select select2" id='product_brand'>
                        <option value="">Select type</option>
                        @forelse ($product_brands as $brand)
                        <option value="{{ $brand->brand_code }}">{{ $brand->brand_name }}</option>
                        @empty
                        <option value=""></option>
                        @endforelse

                    </select>
                </div>
                @error('p_type')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-8">
                <div class="form-group mb-3">
                    <label for="">Product Name <span style="color: red"> * </span></label>
                    <input wire:model='color_name' type='text' label='Name'
                        class="form-control @error('color_name') is-invalid @enderror">
                    @error('color_name')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-4">
                <div class="form-group mb-3" wire:ignore>
                    <label for="">Product Unit </label>
                    <select class="form-select select2" id='product_unit'>
                        <option value="">Select type</option>
                        @forelse ($product_units as $unit)
                        <option value="{{ $unit->st_unit_convert_id }}">{{ $unit->unit_name }}</option>
                        @empty
                        <option value=""></option>
                        @endforelse

                    </select>
                </div>
                @error('p_type')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-4">
                <div class="form-group mb-3">
                    <label for="">Origin </label>
                    <input wire:model='color_name' type='text' label='Name'
                        class="form-control @error('color_name') is-invalid @enderror">
                    @error('color_name')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-4">
                <div class="form-group mb-3">
                    <label for="">Model </label>
                    <input wire:model='color_name' type='text' label='Name'
                        class="form-control @error('color_name') is-invalid @enderror">
                    @error('color_name')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-6 mb-3">
                <div class="form-group">
                    <label for="">Product description </label>
                    <livewire:quill-text-editor wire:model="description" theme="snow" />
                </div>
            </div>
            <div class="col-6 mb-3">
                <div class="form-group">
                    <label for="">Product images </label>
                    <livewire:dropzone wire:model="photos" :rules="['mimes:jpg,svg,png,jpeg,gif']"
                        :key="'dropzone-two'" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group d-flex align-items-center gap-2">
                    <input id='varientkey' wire:model.live='varientkey' class="form-check-input" type="checkbox">
                    <label for="">Add varient</label>
                </div>
            </div>

            @if ($varient)
            <div id='varient_area' class="row">
                <div class="col-4">
                    <div class="col-12">
                        <div class="form-group mb-3" wire:ignore>
                            <label for="">Size </label>
                            <select class="form-select select2" id='product_size'>
                                <option value="">Select size</option>
                                @forelse ($product_brands as $brand)
                                <option value="{{ $brand->brand_code }}">{{ $brand->brand_name }}</option>
                                @empty
                                <option value=""></option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group mb-3" wire:ignore>
                            <label for="">Color </label>
                            <select class="form-select select2" id='product_color'>
                                <option value="">Select color</option>
                                @forelse ($product_brands as $brand)
                                <option value="{{ $brand->brand_code }}">{{ $brand->brand_name }}</option>
                                @empty
                                <option value=""></option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div>
                        <a class="btn btn-sm btn-success">
                            <i class="fa fa-plus"></i>
                            Add
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <div class="mt-5 d-flex justify-content-center">
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
    })

    $('#product_group').on('change', function(){
        let data = $(this).val();
        $wire.dispatch('product_group_change', {id: data});
    })

    $wire.on('product-categories-as-group',(event)=>{
        $('#product_category').html('');
        $('#product_category').append(`<option >Select product category</option>`)
        event.categories.forEach(function(item) {
            $('#product_category').append(
                `<option value='${item.tran_mst_id}'>${item.catagories_name}</option>`
            )
            $('#product_category').select2();
        });
        $('#product_category').select2({
            theme: "bootstrap-5",
        });
    })

    $('#product_category').on('change', function(e){
        @this.set('product_category_id', e.target.value, false);
    })

</script>
@endscript
