<div>
    @push('css')
    <style>
        .productRow {
            color: white;
            cursor: pointer;
            padding: 0rem 1rem !important;
            margin-bottom: 5px !important
        }

        .ql-editor {
            height: 70px;
            max-height: 250px;
            overflow: auto;
        }

        .productRow:hover {
            background: #8f9cff
        }

        .search__container {
            background: #227CE9 !important;
            padding: 0.2rem !important;
            border-bottom-left-radius: 8px !important;
            border-bottom-right-radius: 8px !important;
        }
    </style>
    @endpush
    <div>
        <div wire:loading class="spinner-border text-primary custom-loading"></div>
        <span class="sr-only">Loading...</span>
    </div>
    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">
            <i class="fa-solid fa-cart-shopping"></i> Product sale report
        </h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Reports</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="" style="color: #3C50E0">Product sale
                        report</a></li>
            </ol>
        </nav>
    </div>
    <div class="card p-4">
        <form action="" wire:submit='search' x-data="{edit : false}">
            <div class="row g-3 mb-3 align-items-center">
                <div class="col-md-5">
                    <div class="form-group mb-3">
                        <label for=""> Sale memo search </label>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:10%; border: 1px solid #DFE2E6;padding: 0px 10px;border-radius: 4px;">
                                <i style="font-size: 25px" class="fa fa-barcode"></i>
                            </div>
                            <div class="position-relative" @click.away="edit = false" style="width: 90%">
                                <input autocomplete="off" autofocus='true'
                                    placeholder="please type sales memo or code or scan barcode" @input="edit = true"
                                    wire:model.live.debounce.1000ms='salesesearch'
                                    wire:keydown.escape="hideDropdown" wire:keydown.tab="hideDropdown"
                                    wire:keydown.Arrow-Up.debounce="decrementHighlight" wire:keydown.Arrow-Down="incrementHighlight"
                                    wire:keydown.enter.prevent="selectAccount" type='text' class="form-control">

                                <div class="position-absolute w-full"
                                    style="width:100%; max-height: 250px; overflow-y:scroll">
                                    @if (count($resultSales) > 0)
                                    <div x-show="edit === true" class="search__container">
                                        @forelse ($resultSales as $pk => $resultSale)
                                        <p class="productRow" wire:click='searchRowSelect({{ $pk }})' wire:key='{{ $pk }}'
                                            @click="edit = false"
                                            style="@if($searchSelect === $pk) background: #1e418685; @endif">
                                            {{ $resultSale->memo_no }}
                                            | {{ date('d-M-Y', strtotime($resultSale->tran_date)) }}
                                            | <b>Amt:</b> {{ number_format($resultSale->tot_payable_amt, 2, '.', '') }}

                                        </p>
                                        @empty
                                        <p>No purchase</p>
                                        @endforelse

                                    </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                @if ($type == 'custom')
                <div class="col-md-3 ">
                    <x-input required_mark='' wire:model='state.start_date' name='start_date' type='date'
                        label='Start Date' />
                </div>
                <div class="col-md-3 ">
                    <x-input required_mark='' wire:model='state.end_date' name='end_date' type='date'
                        label='End Date' />
                </div>
                @endif

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
                    <form target="_blank" action="{{route('product-sale-report-pdf')}}" method="post">
                        @csrf
                        <input type="hidden" name="start_date" value="{{ $state['start_date'] }}">
                        <input type="hidden" name="end_date" value="{{ $state['end_date'] }}">
                        <input type="hidden" name="challan_id" value="{{ $state['challan_id'] }}">
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
                    <thead>
                        <tr class="bg-sidebar">
                            <td style="">#</td>
                            <td style="width:9%">Date</td>
                            <td style="width:11%">Sale no</td>
                            <td style="text-align: center">Item</td>
                            <td style="text-align: center">Branch</td>
                            <td style="text-align: center">Qty</td>
                            <td style="text-align: center">Rate</td>
                            <td style="text-align: center">Vat</td>
                            <td style="text-align: center">Discount</td>
                            <td style="text-align: center">Total</td>
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
                            $t_qty += $ledger->sales_qty;
                            $t_vat += $ledger->vat_amt;
                            $t_discount += $ledger->discount;
                            $t_total += $ledger->tot_sales_amt;
                            @endphp
                            <td>{{ $key+1 }}</td>
                            <td>{{ date('d-M-y', strtotime($ledger->sales_date)) }}</td>
                            <td>{{ $ledger->challan_no }}</td>
                            <td>
                                {{ $ledger->item_name }}
                                @if ($ledger->item_size_name)
                                | {{ $ledger->item_size_name }}
                                @endif
                                @if ($ledger->color_name)
                                | {{ $ledger->color_name }}
                                @endif
                            </td>
                            <td>{{ $ledger->challan_name }}</td>
                            <td style="text-align: center">{{ $ledger->sales_qty }}</td>
                            <td style="text-align: right">{{ number_format($ledger->mrp_rate, 2, '.', '') }}</td>
                            <td style="text-align: right">{{ number_format($ledger->vat_amt, 2, '.', '') }}</td>
                            <td style="text-align: right">{{ number_format($ledger->discount, 2, '.', '') }}</td>
                            <td style="text-align: right">{{ number_format($ledger->tot_sales_amt, 2, '.', '') }}</td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">No data found</td>
                        </tr>
                        @endforelse

                    </tbody>
                    <tfoot>
                        <th colspan="5" style="text-align: right">Total: </th>
                        <th style="text-align: center">{{ $t_qty }}</th>
                        <th style="text-align: right"></th>
                        <th style="text-align: right">{{ number_format($t_vat, 2, '.', '') }}</th>
                        <th style="text-align: right">{{ number_format($t_discount, 2, '.', '') }}</th>
                        <th style="text-align: right">{{ number_format($t_total, 2, '.', '') }}</th>

                    </tfoot>

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



</script>
@endscript
