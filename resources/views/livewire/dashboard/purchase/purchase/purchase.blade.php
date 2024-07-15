<div>

    <div wire:loading class="spinner-border text-primary custom-loading">
        <span class="sr-only">Loading...</span>
    </div>
    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">
            <i class="fa-solid fa-cart-shopping"></i> Purchase
        </h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Purchase</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('purchase') }}"
                        style="color: #3C50E0">purchase list</a></li>
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
            @permission(1,'visible_flag')
            <div class="col-auto">
                <a href='{{route('purchase-create') }}' type="button" class="btn btn-primary">Create new purchase</a>
            </div>
            @endpermission

        </div>
        <div class="table-responsive" style="font-size: 0.9em !important;">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="bg-sidebar">
                        <td style="">#</td>
                        <td >Date</td>
                        <td>Memo no</td>
                        <td>Supplier</td>
                        <td style="text-align: center" >PR status</td>
                        <td style="text-align: center">Grand amt</td>
                        <td style="text-align: center">Returned</td>
                        <td style="text-align: center">Paid amt</td>
                        <td style="text-align: center">Due amt</td>
                        <td style="text-align: center">Payment</td>
                        <td class="text-center">Action</td>
                    </tr>
                </thead>
                <tbody>
                    @if (count($this->resultPurchase) > 0)
                    @foreach ($this->resultPurchase as $key => $purchase)
                    <tr wire:key='{{ $key }}'>
                        <td>{{ $this->resultPurchase->firstItem() + $key }}</td>
                        <td>
                            {{ date('d-M-y', strtotime($purchase->tran_date)) }}
                        </td>
                        <td>{{ $purchase->memo_no }}</td>
                        <td>{{ $purchase->p_name }}</td>
                        <td>
                            {{-- <div class="d-flex justify-content-center align-items-center">
                                <span @php if ($purchase->status==1)
                                    $style='background:#D4EDDA; color:#275724';
                                    elseif($purchase->status==2 || $purchase->status==3)
                                    $style='background:#FFF3CD; color:#909173';
                                    elseif($purchase->status==4)
                                    $style='background:#F8D7DA; color:#881C24';

                                    @endphp
                                    style="{{ $style }}" class="badge badge-danger badge-pill">
                                    {{ App\Service\Purchase:: purchaseStatus($purchase->status) }}
                                </span>
                            </div> --}}

                            <select style="
                                font-size: 0.9em !important;
                            @if ($purchase->status == 1)
                                background: #D4EDDA;
                            @elseif($purchase->status == 2)
                                background: #FFF3CD;
                            @elseif($purchase->status == 3)
                                background: #FFF3CD;
                            @elseif($purchase->status == 4)
                                background: #FFF3CD;
                            @endif

                            "
                            class='form-control select-status' name="" id="">
                                <option
                                    @if ($purchase->status == 1)
                                        selected
                                    @endif value="1">Recieved
                                </option>
                                <option
                                    @if ($purchase->status == 2)
                                        selected
                                    @endif value="2">Partial
                                </option>
                                <option
                                    @if ($purchase->status == 3)
                                        selected
                                    @endif value="2">Pending
                                </option>
                                <option
                                    @if ($purchase->status == 4)
                                        selected
                                    @endif value="2">Ordered
                                </option>
                            </select>

                        </td>
                        <td style="text-align: right">
                            {{ number_format($purchase->tot_payable_amt, 2, '.', '') }}
                        </td>
                        <td style="text-align: right">
                            {{ number_format($purchase->rt_amt, 2, '.', '') }}
                        </td>
                        <td style="text-align: right">
                            {{ number_format($purchase->tot_paid_amt, 2, '.', '') }}
                        </td>
                        <td style="text-align: right">
                            {{ number_format((App\Service\Payment::dueAmount($purchase->tot_payable_amt, $purchase->rt_amt, $purchase->tot_paid_amt)), 2, '.', '') }}
                        </td>
                        <td style="text-align: right">
                            @php
                                $pay_status = App\Service\Payment::paymentSatus($purchase->tot_payable_amt, $purchase->rt_amt, $purchase->tot_paid_amt);
                            @endphp
                            <div class="d-flex justify-content-center align-items-center">
                                <span
                                style="
                                font-size: 0.9em;
                                @if($pay_status == 'PAID')
                                background: #D4EDDA;
                                color: #155724;
                                @else
                                background: #F8D7DA;
                                color: #721c24;
                                @endif
                                "
                                 class="badge badge-danger badge-pill">
                                    {{ $pay_status }}
                                </span>
                            </div>
                        </td>
                        <td style="">
                            <div class="d-flex justify-content-center gap-2">
                                <a wire:navigate href="{{ route('purchase-edit',$purchase->tran_mst_id) }}"
                                    class="btn btn-sm btn-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20px" height="20px"
                                        viewBox="0 0 50 50">
                                        <path fill="white"
                                            d="M 43.050781 1.9746094 C 41.800781 1.9746094 40.549609 2.4503906 39.599609 3.4003906 L 38.800781 4.1992188 L 45.699219 11.099609 L 46.5 10.300781 C 48.4 8.4007812 48.4 5.3003906 46.5 3.4003906 C 45.55 2.4503906 44.300781 1.9746094 43.050781 1.9746094 z M 37.482422 6.0898438 A 1.0001 1.0001 0 0 0 36.794922 6.3925781 L 4.2949219 38.791016 A 1.0001 1.0001 0 0 0 4.0332031 39.242188 L 2.0332031 46.742188 A 1.0001 1.0001 0 0 0 3.2578125 47.966797 L 10.757812 45.966797 A 1.0001 1.0001 0 0 0 11.208984 45.705078 L 43.607422 13.205078 A 1.0001 1.0001 0 1 0 42.191406 11.794922 L 9.9921875 44.09375 L 5.90625 40.007812 L 38.205078 7.8085938 A 1.0001 1.0001 0 0 0 37.482422 6.0898438 z">
                                        </path>
                                    </svg>
                                </a>
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
        <span>{{ $this->resultPurchase->links() }}</span>
    </div>
</div>

<script>

</script>
