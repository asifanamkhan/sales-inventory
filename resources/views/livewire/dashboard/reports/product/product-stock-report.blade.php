<div>
    <div>
        <div wire:loading class="spinner-border text-primary custom-loading"></div>
        <span class="sr-only">Loading...</span>
    </div>
    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">
            <i class="fa-solid fa-cart-shopping"></i> Product stock report
        </h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Reports</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="" style="color: #3C50E0">Product stock
                        report</a></li>
            </ol>
        </nav>
    </div>
    <div class="card p-4">
        <form action="" wire:submit='search'>
            <div class="row g-3 mb-3 align-items-center">
                <div class="col-md-3">
                    <div class="form-group mb-2" wire:ignore>
                        <label for="">Branch</label>
                        <select class="form-select select2" id='branch'>
                            <option value="">Select branch</option>
                            @forelse ($branchs as $branch)
                            <option wire:key='{{ $branch->branch_id }}' value="{{ $branch->branch_id }}">
                                {{ $branch->branch_name }}
                            </option>
                            @empty
                            <option value=""></option>
                            @endforelse
                        </select>
                    </div>
                    @error('st_group_item_id')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-4">
                    <div class="form-group mb-2" wire:ignore>
                        <label for="">Category</label>
                        <select class="form-select select2" id='catagories_id'>
                            <option value="">Select categry</option>
                            @forelse ($catagories as $catagory)
                            <option wire:key='{{ $catagory->tran_mst_id }}' value="{{ $catagory->tran_mst_id }}">
                                {{ $catagory->catagories_name }}
                            </option>
                            @empty
                            <option value=""></option>
                            @endforelse
                        </select>
                    </div>
                    @error('st_group_item_id')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-3">
                    <div class="form-group mb-3" wire:ignore>
                        <label for="">Product</label>
                        <select class="form-select select2" id='product'>
                            <option value="">Select product</option>
                            @forelse ($products as $product)
                            <option wire:key='{{ $product->st_group_item_id }}' value="{{ $product->st_group_item_id }}">
                                {{ $product->item_name }} | {{ $product->catagories_name }}
                                @if ($product->item_size_name)
                                | {{ $product->item_size_name }}
                                @endif
                                @if ($product->color_name)
                                | {{ $product->color_name }}
                                @endif
                            </option>
                            @empty
                            <option value=""></option>
                            @endforelse
                        </select>
                    </div>
                    @error('st_group_item_id')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>



                <div class="col-md-1 ">
                    <button class="btn btn-primary" id='search'>Search</button>
                </div>
            </div>
        </form>
        @if (count($ledgers) > 0)
        <div>
            <div style="display: flex; justify-content: space-between" class="p-2">
                <div></div>
                <div style="float: right">
                    <form target="_blank" action="{{route('product-stock-report-pdf')}}" method="post">
                        @csrf
                         <input type="hidden" name="branch_id" value="{{ $state['branch_id'] }}">
                        <input type="hidden" name="catagories_id" value="{{ $state['catagories_id'] }}">
                        <input type="hidden" name="st_group_item_id" value="{{ $state['st_group_item_id'] }}">
                        <button class="btn btn-sm btn-success">
                            <i class="fa-solid fa-file-pdf"></i> Generate PDF
                        </button>
                    </form>

                </div>
            </div>
            <div class="responsive-table" style="font-size: 0.9em !important;">
                <table class="table table-bordered table-hover">
                    <thead class="bg-sidebar">
                        <tr >
                            <td rowspan="2" style="">#</td>
                            <td rowspan="2" style="text-align: center">Item</td>
                            <td rowspan="2" style="text-align: center">Category</td>
                            <td rowspan="2" style="text-align: center">Brand</td>
                            <td colspan="7" style="text-align: center">Qty</td>
                        </tr>
                        <tr class="bg-sidebar">
                            <td style="text-align: center">Opening</td>
                            <td style="text-align: center">Purchase</td>
                            <td style="text-align: center">Purchase rt </td>
                            <td style="text-align: center">Sale</td>
                            <td style="text-align: center">Sale rt</td>
                            <td style="text-align: center">Damage</td>
                            <td style="text-align: center">Current</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $t_qty = 0;
                        $t_vat = 0;
                        $t_discount = 0;
                        $t_total = 0;
                        @endphp
                        @forelse ($ledgers as $key => $ledger)
                        <tr wire:key='{{ $key }}'>
                            @php
                            // $t_qty += $ledger->item_qty;
                            @endphp
                            <td>{{ $key+1 }}</td>
                            <td>
                                {{ $ledger->item_name }}
                                @if ($ledger->item_size_name)
                                | {{ $ledger->item_size_name }}
                                @endif
                                @if ($ledger->color_name)
                                | {{ $ledger->color_name }}
                                @endif
                            </td>
                            <td>{{ $ledger->catagories_name }}</td>
                            <td>{{ $ledger->brand_name }}</td>
                            <td style="text-align: center">{{ $ledger->op_qty }}</td>
                            <td style="text-align: center">{{ $ledger->rc_qty }}</td>
                            <td style="text-align: center">{{ $ledger->prt_qty }}</td>
                            <td style="text-align: center">{{ $ledger->sl_qty }}</td>
                            <td style="text-align: center">{{ $ledger->srt_qty }}</td>
                            <td style="text-align: center">{{ $ledger->rj_qty }}</td>
                            <td style="text-align: center">{{ $ledger->stock_qty }}</td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">No data found</td>
                        </tr>
                        @endforelse

                    </tbody>
                    {{-- <tfoot>
                        <th colspan="5" style="text-align: right">Total: </th>
                        <th style="text-align: right">{{ number_format($t_qty, 2, '.', '') }}</th>
                        <th style="text-align: right"></th>
                        <th style="text-align: right">{{ number_format($t_vat, 2, '.', '') }}</th>
                        <th style="text-align: right">{{ number_format($t_discount, 2, '.', '') }}</th>
                        <th style="text-align: right">{{ number_format($t_total, 2, '.', '') }}</th>

                    </tfoot> --}}

                </table>
            </div>
        </div>
        @else
        {{-- <div class="alert alert-danger">
            No data found
        </div> --}}
        @endif

    </div>
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

    $('#product').on('change', function(e){
        @this.set('state.st_group_item_id', e.target.value, false);
    });
    $('#branch').on('change', function(e){
        @this.set('state.branch_id', e.target.value, false);
    });
    $('#catagories_id').on('change', function(e){
        @this.set('state.catagories_id', e.target.value, false);
    });
</script>
@endscript
