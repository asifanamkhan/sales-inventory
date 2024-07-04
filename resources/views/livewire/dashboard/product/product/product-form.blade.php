<div>
    <div wire:loading class="spinner-border text-primary custom-loading">
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
    <form action="" wire:submit='save'>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group mb-3" wire:ignore>
                    <label for="">Product group <span style="color: red"> * </span></label>
                    <select class="form-select select2" id='product_group'>
                        <option value="">Select type</option>
                        @forelse ($product_groups as $group)
                        <option
                        @if ($group->st_group_id == @$edit_select['edit_group_id'])
                            selected
                        @endif
                        value="{{ $group->st_group_id }}">{{ $group->group_name }}</option>
                        @empty
                        <option value=""></option>
                        @endforelse
                    </select>
                </div>
                @error('group_group_id')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3" wire:ignore>
                    <label for="">Product Category <span style="color: red"> * </span></label>
                    <select class="form-select select2" id='product_category'>
                        <option value="">Select type</option>
                    </select>
                </div>
                @error('catagories_id')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3" wire:ignore>
                    <label for="">Product Brand </label>
                    <select class="form-select select2" id='product_brand'>
                        <option value="">Select type</option>
                        @forelse ($product_brands as $brand)
                        <option
                        @if ($brand->brand_code == @$edit_select['edit_brand_id'])
                            selected
                        @endif
                        value="{{ $brand->brand_code }}">{{ $brand->brand_name }}</option>
                        @empty
                        <option value=""></option>
                        @endforelse

                    </select>
                </div>
                @error('brand_code')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="">Product Name <span style="color: red"> * </span></label>
                    <input wire:model='state.item_name' type='text' label='Name'
                        class="form-control @error('item_name') is-invalid @enderror">
                    @error('item_name')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3" wire:ignore>
                    <label for="">Product Unit </label>
                    <select class="form-select select2" id='product_unit'>
                        <option value="">Select type</option>
                        @forelse ($product_units as $unit)
                        <option
                        @if ($unit->st_unit_convert_id == @$edit_select['edit_unit_id'])
                            selected
                        @endif
                        value="{{ $unit->st_unit_convert_id }}">{{ $unit->unit_name }}</option>
                        @empty
                        <option value=""></option>
                        @endforelse
                    </select>
                </div>
                @error('unit_id')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="">Model </label>
                    <input wire:model='state.model' type='text' label='Name'
                        class="form-control @error('model') is-invalid @enderror">
                    @error('model')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="form-group">
                    <label for="">Product description </label>
                    <livewire:quill-text-editor wire:model="state.description" theme="snow" />
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div>
                    @if (count($this->editPhotos) > 0 )
                       <div class="row ">

                        @foreach ($editPhotos as $k => $p)
                            <div wire:key='{{ $k }}' class="col-3 d-flex align-items-center justify-content-center">

                                <a target="_blank" href="{{ asset('storage/app/upload/product/'.$p)}}">
                                    <img style="max-width:100px; height: auto" class="img-thumbnail m-2" src="{{ asset('storage/app/upload/product/'.$p)}}" alt="">
                                </a>
                                <a href='' style="cursor: pointer" wire:click.prevent='editImgRemove({{ $k }})'>
                                    <div class="dz-flex dz-items-center dz-mr-3">
                                        <button type="button" wire:click.prevent='editImgRemove({{ $k }})'>
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="red" class="dz-w-6 dz-h-6 dz-text-black dark:dz-text-white">
                                                <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                       </div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="">Product images </label>
                    <livewire:dropzone wire:model="photos" :rules="['mimes:jpg,svg,png,jpeg,gif']" :multiple='true'
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
            <div id='varient_area' class="row" wire:ignore.self>
                <div class="col-md-4">
                    <livewire:dashboard.product.product.product-variant />
                </div>
                <div class="col-8">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr class="bg-sidebar">
                                <td style="width: 45%">Size</td>
                                <td style="width: 45%">Color</td>
                                <td style="width: 10%">Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($this->variant_cart) > 0)
                            @foreach ($this->variant_cart as $key => $cart)
                            @php
                                $c = (array)$cart;
                            @endphp
                            @if($c['item_size_name'] || $c['color_name'])
                            <tr wire:key='{{ $key }}'>
                                <td>
                                    {{ $c['item_size_name'] }}
                                </td>
                                <td>
                                    {{ $c['color_name'] }}
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center align-items-center">
                                        <a style="cursor: pointer" wire:click.prevent='variant_cart_remove({{ $key }})'>
                                            <i style="color: red" class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endif
                            @endforeach
                            @endif
                        </tbody>
                    </table>

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
    });

    $('#product_group').on('change', function(){
        let data = $(this).val();
        $wire.dispatch('product_group_change', {id: data});
    });

    $wire.on('product-categories-as-group',(event)=>{
        $('#product_category').html('');
        $('#product_category').append(`<option >Select product category</option>`)
        if(event.categories.length > 0){
            event.categories.forEach(function(item) {
            $('#product_category').append(
                `<option value='${item.tran_mst_id}'>${item.catagories_name}</option>`
            );
        });
        }
    });

    $('#product_category').on('change', function(e){
        @this.set('state.catagories_id', e.target.value, false);
    });

    $('#product_brand').on('change', function(e){
        @this.set('state.brand_code', e.target.value, false);
    });

    $('#product_unit').on('change', function(e){
        @this.set('state.unit_id', e.target.value, false);
    });

</script>
@endscript
