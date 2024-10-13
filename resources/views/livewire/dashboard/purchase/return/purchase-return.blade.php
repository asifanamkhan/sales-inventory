<div>
    <div wire:loading class="spinner-border text-primary custom-loading">
        <span class="sr-only">Loading...</span>
    </div>
    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">Purchase return</h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Purchase return</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('purchase-return') }}"
                        style="color: #3C50E0">purchase return list</a></li>
            </ol>
        </nav>
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
    <div class="row" style="padding: 0px 8px 2px">
        <p class="col-auto">
            Total return:
            <span class="badge bg-primary">
                {{ number_format($purchaseGrantAmt, 2, '.', ',') }}
            </span>
        </p>

        <p class="col-auto">
            Total received:
            <span class='badge bg-success'>{{ number_format($purchasePaidAmt, 2, '.', ',') }}</span>
        </p>
        <p class="col-auto">
            Total receive ddue:
            <span class='badge bg-danger'>{{ number_format($purchaseDueAmt, 2, '.', ',') }}</span>
        </p>
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
            <div class="col-auto d-flex align-items-center gap-1">
                <input type="text" wire:model='searchDate' class="form-control date-range" id="date-filter">
                <button wire:click='dateFilter' class="btn btn-success">
                    <i class="fa-solid fa-search"></i>
                </button>
            </div>
            @permission(1,'visible_flag')
            <div class="col-auto">
                <a wire:navigate href='{{ route('purchase-return-create') }}' type="button"
                    class="btn btn-primary">Create purchase return</a>
            </div>
            @endpermission
            <x-large-modal class='purchase-return-payment'>
                <livewire:dashboard.purchase.return.pay-partial.payment-return>
            </x-large-modal>
        </div>
        
        <div class="responsive-table">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="bg-sidebar">
                        <td style="">#</td>
                        <td style="">Date</td>
                        <td style="">Memo no</td>
                        <td style="">Supplier</td>
                        <td style="text-align: center">PR status</td>
                        <td style="text-align: center">Returnd amt</td>
                        <td style="text-align: center">Received amt</td>
                        <td style="text-align: center">Due amt</td>
                        <td class="text-center">Action</td>
                    </tr>

                </thead>
                <tbody>

                    @if (count($this->resultPurchaseReturn) > 0)
                    @foreach ($this->resultPurchaseReturn as $key => $purchase_return)
                    <tr wire:key='{{ $key }}'>
                        <td>{{ $this->resultPurchaseReturn->firstItem() + $key }}</td>
                        <td>{{ date('d-M-Y', strtotime($purchase_return->tran_date)) }}</td>
                        <td>{{ $purchase_return->memo_no }}</td>
                        <td>{{ $purchase_return->p_name }}</td>
                        <td>
                            <select style="
                                font-size: 0.9em !important;
                            @if ($purchase_return->status == 1)
                                background: #D4EDDA;
                            @elseif($purchase_return->status == 2)
                                background: #FFF3CD;
                            @endif

                            " class='form-control select-status' name="" id="">
                                <option @if ($purchase_return->status == 1)
                                    selected
                                    @endif value="1">Full returend
                                </option>
                                <option @if ($purchase_return->status == 2)
                                    selected
                                    @endif value="2">Partial returend
                                </option>
                            </select>

                        </td>
                        <td style="text-align: right">
                            @php
                            $rt_total += (float)$purchase_return->tot_payable_amt;
                            @endphp
                            {{ number_format($purchase_return->tot_payable_amt, 2, '.', '') }}

                        </td>
                        <td style="text-align: right">
                            @php
                            $received_total += (float)$purchase_return->tot_paid_amt;
                            @endphp
                            {{ number_format($purchase_return->tot_paid_amt, 2, '.', '') }}
                        </td>
                        <td style="text-align: right">
                            @php
                            $due_total += (float)$purchase_return->tot_due_amt;
                            @endphp
                            {{ number_format($purchase_return->tot_due_amt, 2, '.', '') }}
                        </td>
                        <td style="">
                            <div class="dropdown show">
                                <a class="btn btn-sm btn-primary dropdown-toggle" href="#" role="button"
                                    id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    Action &nbsp;&nbsp;
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    {{-- <a class="dropdown-item"
                                        href="{{ route('purchase-edit', $purchase_return->tran_mst_id) }}">
                                        <i class="fa fa-edit"></i> <span>Edit</span>
                                    </a> --}}
                                    <a class="dropdown-item d-flex gap-1" wire:navigate
                                        href="{{ route('purchase-return-details', $purchase_return->tran_mst_id) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-binoculars" viewBox="0 0 16 16">
                                            <path
                                                d="M3 2.5A1.5 1.5 0 0 1 4.5 1h1A1.5 1.5 0 0 1 7 2.5V5h2V2.5A1.5 1.5 0 0 1 10.5 1h1A1.5 1.5 0 0 1 13 2.5v2.382a.5.5 0 0 0 .276.447l.895.447A1.5 1.5 0 0 1 15 7.118V14.5a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 14.5v-3a.5.5 0 0 1 .146-.354l.854-.853V9.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v.793l.854.853A.5.5 0 0 1 7 11.5v3A1.5 1.5 0 0 1 5.5 16h-3A1.5 1.5 0 0 1 1 14.5V7.118a1.5 1.5 0 0 1 .83-1.342l.894-.447A.5.5 0 0 0 3 4.882zM4.5 2a.5.5 0 0 0-.5.5V3h2v-.5a.5.5 0 0 0-.5-.5zM6 4H4v.882a1.5 1.5 0 0 1-.83 1.342l-.894.447A.5.5 0 0 0 2 7.118V13h4v-1.293l-.854-.853A.5.5 0 0 1 5 10.5v-1A1.5 1.5 0 0 1 6.5 8h3A1.5 1.5 0 0 1 11 9.5v1a.5.5 0 0 1-.146.354l-.854.853V13h4V7.118a.5.5 0 0 0-.276-.447l-.895-.447A1.5 1.5 0 0 1 12 4.882V4h-2v1.5a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5zm4-1h2v-.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5zm4 11h-4v.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5zm-8 0H2v.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5z" />
                                        </svg>
                                        <span>Details</span>
                                    </a>
                                    <a @click="$dispatch('purchase-return-payment', {id: {{ $purchase_return->tran_mst_id }}})"
                                        data-toggle="modal" data-target=".purchase-return-payment" class="dropdown-item"
                                        href="#">
                                        <i class="fa fa-credit-card"></i> Receive payment
                                    </a>
                                    <a target="_blank" class="dropdown-item"
                                        href="{{ route('purchase-return-invoice', $purchase_return->tran_mst_id) }}">
                                        <i class="fas fa-print"></i> Print
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
                <tfoot>
                    <tr style="text-align: right; font-weight:600">
                        <td colspan="5">Total</td>
                        <td>
                            {{ number_format($rt_total, 2, '.', ',') }}
                        </td>
                        <td>
                            {{ number_format($received_total, 2, '.', ',') }}
                        </td>
                        <td>
                            {{ number_format($due_total, 2, '.', ',') }}
                        </td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <span>{{ $this->resultPurchaseReturn->links() }}</span>
    </div>
</div>

<script>
    document.addEventListener('livewire:navigated', () => {
        $(document).ready(function() {
            $('.date-range').daterangepicker();
        });
    });
    $('#date-filter').on('change', function(){
        @this.set('searchDate', $('#date-filter').val(), false);
    })
</script>
