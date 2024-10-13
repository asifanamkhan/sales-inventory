<div>
    <div wire:loading class="spinner-border text-primary custom-loading" Sale-Sale="status">
        <span class="sr-only">Loading...</span>
    </div>
    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">
            <i class="fa-solid fa-scale-balanced"></i>
            Sales
        </h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Sales</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('sale') }}"
                        style="color: #3C50E0">sale list</a></li>
            </ol>
        </nav>
    </div>

    <div class="row" style="padding: 0px 8px 2px">
        <p class="col-auto">
            Total purchase:
            <span class="badge bg-primary">
                {{ number_format($saleGrantAmt, 2, '.', ',') }}
            </span>
        </p>
        <p class="col-auto">
            Total purchase return:
            <span class="badge bg-warning">
                {{ number_format($saleRtAmt, 2, '.', ',') }}
            </span>
        </p>
        <p class="col-auto">
            Total paid:
            <span class='badge bg-success'>{{ number_format($salePaidAmt, 2, '.', ',') }}</span>
        </p>
        <p class="col-auto">
            Total due:
            <span class='badge bg-danger'>{{ number_format($saleDueAmt, 2, '.', ',') }}</span>
        </p>
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
            <div class="col-auto d-flex align-items-center gap-1">
                <input type="text" wire:model='searchDate' class="form-control date-range" id="date-filter">
                <button wire:click='dateFilter' class="btn btn-success">
                    <i class="fa-solid fa-search"></i>
                </button>
            </div>
            @permission(1,'visible_flag')
            <div class="col-auto" style="text-align: right">
                <a href='{{ route('sale-create') }}' type="button" class="btn btn-primary">Create new Sale</a>
            </div>
            @endpermission
        {{-- modal --}}
        <x-large-modal class='payment'>
            <livewire:dashboard.sales.sales.pay-partial.payment>
        </x-large-modal>
        </div>
        <div class="responsive-table">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="bg-sidebar">
                        <td style="">

                        </td>
                        <td style="">#</td>
                        <td style="width:9%">Date</td>
                        <td style="width:11%">Memo no</td>
                        <td style="width:15%">Customer</td>
                        <td style="width:9% ;text-align: center">SL status</td>
                        <td style="text-align: center">Grand amt</td>
                        <td style="text-align: center">Returned</td>
                        <td style="text-align: center">Paid amt</td>
                        <td style="text-align: center">Due amt</td>
                        <td style="text-align: center">Payment</td>
                        <td class="text-center">Action</td>

                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input wire:model.live.debounce.500ms='selectPageRows' type="checkbox"
                                class="form-check-input">
                        </td>
                        <td>

                        </td>
                        <td>

                        </td>
                        <td>
                            <input placeholder="search" wire:model.live.debounce.500ms='searchMemo' type="text" class="form-control">
                        </td>
                        <td>
                            <input placeholder="search" wire:model.live.debounce.500ms='searchSupplier' type="text"
                                class="form-control">
                        </td>
                        <td>
                            <select wire:model.live.debounce='searchStatus' class="form-select">
                                <option value="">ALL</option>
                                <option value="1">Complete</option>
                                <option value="3">Pending</option>
                                <option value="5">Cancled</option>
                            </select>
                        </td>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>
                            <select wire:model.live='searchPayStatus' class="form-select">
                                <option value="">ALL</option>
                                <option value="PAID">PAID</option>
                                <option value="DUE">DUE</option>
                            </select>
                        </th>
                        <th></th>
                    </tr>
                    @if (count($this->resultSale) > 0)
                    @foreach ($this->resultSale as $key => $sale)
                    <tr wire:key='{{ $key }}'>
                        <td>
                            <input wire:model='selectRows' id='{{ $sale->tran_mst_id }}'
                                value="{{ $sale->tran_mst_id }}" type="checkbox" class="form-check-input">
                        </td>
                        <td>{{ $this->resultSale->firstItem() + $key }}</td>
                        <td>
                            {{ date('d-M-y', strtotime($sale->tran_date)) }}
                        </td>
                        <td>{{ $sale->memo_no }}</td>
                        <td>{{ $sale->p_name }}</td>
                        <td>
                            {{-- <div class="d-flex justify-content-center align-items-center">
                                <span @php if ($sale->status==1)
                                    $style='background:#D4EDDA; color:#275724';
                                    elseif($sale->status==2 || $sale->status==3)
                                    $style='background:#FFF3CD; color:#909173';
                                    elseif($sale->status==4)
                                    $style='background:#F8D7DA; color:#881C24';

                                    @endphp
                                    style="{{ $style }}" class="badge badge-danger badge-pill">
                                    {{ App\Service\Purchase:: purchaseStatus($sale->status) }}
                                </span>
                            </div> --}}

                            <select style="
                                font-size: 0.9em !important;
                            @if ($sale->status == 1)
                                background: #D4EDDA;
                            @elseif($sale->status == 2)
                                background: #FFF3CD;
                            @elseif($sale->status == 3)
                                background: #FFF3CD;
                            @endif
                            " class='form-control select-status' name="" id="">
                                <option @if ($sale->status == 1)
                                    selected
                                    @endif value="1">Complete
                                </option>
                                <option @if ($sale->status == 2)
                                    selected
                                    @endif value="2">Pending
                                </option>
                                <option @if ($sale->status == 5)
                                    selected
                                    @endif value="3">Cancled
                                </option>
                            </select>

                        </td>
                        <td style="text-align: right">
                            @php
                            $grand_total += (float)$sale->tot_payable_amt;
                            @endphp
                            {{ number_format($sale->tot_payable_amt, 2, '.', '') }}
                        </td>
                        <td style="text-align: right">
                            @php
                            $rt_total += (float)$sale->prt_amt;
                            @endphp
                            {{ number_format($sale->prt_amt, 2, '.', '') }}
                        </td>
                        <td style="text-align: right">
                            @php
                            $paid_total += (float)$sale->tot_paid_amt;
                            @endphp
                            {{ number_format($sale->tot_paid_amt, 2, '.', '') }}
                        </td>
                        <td style="text-align: right">
                            @php
                            $due = App\Service\Payment::dueAmount($sale->tot_payable_amt, $sale->prt_amt,
                            $sale->tot_paid_amt);
                            $due_total += (float)$due;
                            @endphp
                            {{ number_format($due, 2, '.', '') }}
                        </td>
                        <td style="text-align: right">
                            <div class="d-flex justify-content-center align-items-center">
                                <span style="
                                font-size: 0.9em;
                                @if($sale->payment_status == 'PAID')
                                background: #D4EDDA;
                                color: #155724;
                                @else
                                background: #F8D7DA;
                                color: #721c24;
                                @endif
                                " class="badge badge-pill">
                                    {{ $sale->payment_status }}
                                </span>
                            </div>
                        </td>
                        <td style="">
                            <div class="dropdown show">
                                <a class="btn btn-sm btn-primary dropdown-toggle" href="#" role="button"
                                    id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    Action &nbsp;&nbsp;&nbsp;&nbsp;
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item" href="{{ route('sale-edit', $sale->tran_mst_id) }}">
                                        <i class="fa fa-edit"></i> <span>Edit</span>
                                    </a>
                                    <a class="dropdown-item d-flex gap-1" wire:navigate href="{{ route('sale-details', $sale->tran_mst_id) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-binoculars" viewBox="0 0 16 16">
                                            <path
                                                d="M3 2.5A1.5 1.5 0 0 1 4.5 1h1A1.5 1.5 0 0 1 7 2.5V5h2V2.5A1.5 1.5 0 0 1 10.5 1h1A1.5 1.5 0 0 1 13 2.5v2.382a.5.5 0 0 0 .276.447l.895.447A1.5 1.5 0 0 1 15 7.118V14.5a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 14.5v-3a.5.5 0 0 1 .146-.354l.854-.853V9.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v.793l.854.853A.5.5 0 0 1 7 11.5v3A1.5 1.5 0 0 1 5.5 16h-3A1.5 1.5 0 0 1 1 14.5V7.118a1.5 1.5 0 0 1 .83-1.342l.894-.447A.5.5 0 0 0 3 4.882zM4.5 2a.5.5 0 0 0-.5.5V3h2v-.5a.5.5 0 0 0-.5-.5zM6 4H4v.882a1.5 1.5 0 0 1-.83 1.342l-.894.447A.5.5 0 0 0 2 7.118V13h4v-1.293l-.854-.853A.5.5 0 0 1 5 10.5v-1A1.5 1.5 0 0 1 6.5 8h3A1.5 1.5 0 0 1 11 9.5v1a.5.5 0 0 1-.146.354l-.854.853V13h4V7.118a.5.5 0 0 0-.276-.447l-.895-.447A1.5 1.5 0 0 1 12 4.882V4h-2v1.5a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5zm4-1h2v-.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5zm4 11h-4v.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5zm-8 0H2v.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5z" />
                                        </svg>
                                        <span>Details</span>
                                    </a>
                                    <a @click="$dispatch('sale-payment', {id: {{ $sale->tran_mst_id }}})" data-toggle="modal" data-target=".payment" class="dropdown-item" href="#">
                                        <i class="fa fa-credit-card"></i> Make payment
                                    </a>
                                    <a target="_blank" class="dropdown-item" href="{{ route('sale-invoice', $sale->tran_mst_id) }}">
                                        <i class="fas fa-print"></i> Print
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <i class="fa-solid fa-rotate-left"></i> Return
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <i class="fa-regular fa-copy"></i> Duplicate
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <span>{{ $this->resultSale->links() }}</span>
    </div>
</div>

@script
<script data-navigate-once>
    document.addEventListener('livewire:navigated', () => {
        $(document).ready(function() {
            $('.date-range').daterangepicker();
        });
    });
    $('#date-filter').on('change', function(){
        @this.set('searchDate', $('#date-filter').val(), false);
    })
</script>
@endscript



