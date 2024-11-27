<div>
    <div wire:loading class="spinner-border text-primary custom-loading"
        product-pricing-list-product-pricing-list="status">
        <span class="sr-only">Loading...</span>
    </div>
    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">
            <i class="fa-solid fa-money-check-dollar"></i> Product pricing lists</h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Product</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('product-pricing-list') }}"
                        style="color: #3C50E0">pricing list</a></li>
            </ol>
        </nav>
    </div>

    <div class="card p-4">
        <div class="row g-3 mb-3 align-items-center">
            <div class="col-auto">
                <input type="text" wire:model.live.debounce.300ms='search' class="form-control"
                    placeholder="search here">
            </div>
            <div class="col-auto">
                <select class="form-select" wire:model.live='pagination' name="" id="">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <div class="col-auto">
                {{-- <button @click="$dispatch('create-product-brand-modal')" type="button" class="btn btn-primary"
                    data-toggle="modal" data-target=".{{ $event }}">
                    Add new pricing
                </button> --}}
            </div>

            {{-- modal --}}
            <div wire:ignore.self class="modal fade {{ $event }}" role="dialog"
                aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header" style="justify-content: space-between">
                            <h5 class="modal-title text-center w-100" id="exampleModalLabel">Product pricing</h5>
                            <b type="button" class="modal-close-icon" class="close" data-dismiss="modal"
                                aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </b>
                        </div>
                        <livewire:dashboard.product.pricing-list.pricing-list-form />
                    </div>
                </div>
            </div>


        </div>
        <div class="responsive-table">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="bg-sidebar">
                        <td rowspan="2"  style="width: 5%">#</td>
                        <td rowspan="2" >Product</td>
                        <td colspan="6" style="text-align: center">Rate</td>
                        <td colspan="2" style="text-align: center">Quantity</td>
                        <td rowspan="2" class="text-center">Action</td>
                    </tr>
                    <tr class="bg-sidebar">
                        <td >Purchase</td>
                        <td >Diller</td>
                        <td >Retail</td>
                        <td >MRP</td>
                        <td >Vat (%)</td>
                        <td >Vat amt</td>
                        <td >Alert</td>
                        <td >Stock</td>
                    </tr>
                </thead>
                <tbody>
                    @if (count($this->resultProduct) > 0)
                    @foreach ($this->resultProduct as $key => $product)
                    <tr wire:key='{{ $key }}'>
                        <td>{{ $this->resultProduct->firstItem() + $key }}</td>
                        <td>
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
                        </td>
                        <td style="text-align: center">
                            {{ number_format($product->pr_rate, 1, '.', ',') }}
                        </td>
                        <td style="text-align: center">
                            {{ number_format($product->dp_rate, 1, '.', ',') }}
                        </td>
                        <td style="text-align: center">
                            {{ number_format($product->rp_rate, 1, '.', ',') }}
                        </td>
                        <td style="text-align: center">
                            {{ number_format($product->mrp_rate, 1, '.', ',') }}
                        </td>
                        <td style="text-align: center">
                            {{ number_format($product->vat_rate, 1, '.', ',') }}
                        </td>
                        <td style="text-align: center">
                            {{ number_format($product->vat_amt, 1, '.', ',') }}
                        </td>

                        <td style="text-align: center">
                            {{ $product->max_ch_qty }}
                        </td>
                        <td style="
                        text-align: center;
                        @if ($product->stock_qty <= $product->max_ch_qty)
                        background-color: darkred;color: white;

                        @endif
                        ">
                            {{ $product->stock_qty }}
                        </td>

                        <td style="">
                            <div class="d-flex justify-content-center gap-2">
                                <button @click="$dispatch('pricing-list-add',  {id: {{ $product->st_group_item_id }}})" type="button" class="btn btn-sm btn-success"
                                    data-toggle="modal" data-target=".{{ $event }}">
                                    <i class="fa fa-plus"></i>
                                </button>
                                <button class="btn btn-sm btn-warning">
                                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20"
                                        viewBox="0 0 24 24">
                                        <path fill=white
                                            d="M 10 2 L 9 3 L 3 3 L 3 5 L 4.109375 5 L 5.8925781 20.255859 L 5.8925781 20.263672 C 6.023602 21.250335 6.8803207 22 7.875 22 L 16.123047 22 C 17.117726 22 17.974445 21.250322 18.105469 20.263672 L 18.107422 20.255859 L 19.890625 5 L 21 5 L 21 3 L 15 3 L 14 2 L 10 2 z M 6.125 5 L 17.875 5 L 16.123047 20 L 7.875 20 L 6.125 5 z">
                                        </path>
                                    </svg>
                                </button>

                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <span>{{ $this->resultProduct->links() }}</span>
    </div>
</div>

<script>

</script>
