<div>
    <div wire:loading class="spinner-border text-primary custom-loading">
        <span class="sr-only">Loading...</span>
    </div>
    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">
            <i class="fa fa-plus"></i> Sale Details
        </h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">

                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('sale') }}">Sale</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('sale-edit', $sale_id ) }}"
                        style="color: #3C50E0">details</a></li>
            </ol>
        </nav>
    </div>
    <div class="card p-4">
        <div class="row mb-3">
            {{-- <div class="col-auto ">
                <a href='{{ route('sale-edit', $sale_id) }}' class="btn btn-warning">
                    <i class="fa-solid fa-pen-to-square"></i>
                    Edit
                </a>
            </div> --}}
            <div class="col-auto">
                <a target="_blank" href="{{ route('sale-invoice', $sale_id) }}" class="btn btn-success">
                    <i class="fa-solid fa-print"></i>
                    Print
                </a>
            </div>
        </div>
        <div class="responsive-table" style="font-size: 0.9em !important;">
            <div class="responsive-table" style="font-size: 0.9em !important;">
                <table class="table table-bordered">
                    <tbody>
                        <tr style="font-size: 16px; font-weight: 500">
                            <td style="width: 15%;">Sale Date :</td>
                            <td>{{ date('d-M-y', strtotime($sale_mst->tran_date)) }}</td>
                            <td style="width: 10%">Memo No :</td>
                            <td>{{ $sale_mst->memo_no }}</td>
                            <td style="width: 15%">Sale status :</td>
                            <td>
                                <div class="">
                                    <span @php $style='background:#F8D7DA; color:#881C24' ; if
                                        ($sale_mst->status==1)
                                        $style='background:#D4EDDA; color:#275724';
                                        elseif($sale_mst->status==2 || $sale_mst->status==3)
                                        $style='background:#FFF3CD; color:#909173';
                                        @endphp
                                        style="{{ $style }}" class="badge badge-danger badge-pill">
                                        @if ($sale_mst->status==1)
                                            Complete
                                        @elseif ($sale_mst->status==2)
                                            Pending
                                        @elseif ($sale_mst->status==3)
                                            Cancled
                                        @endif
                                    </span>
                                </div>
                            </td>
                            <tr />
                        <tr style="font-size: 16px; font-weight: 500">
                            <td>Supplier :</td>
                            <td>{{ $sale_mst->p_name }}</td>
                            <td>Warehouse :</td>
                            <td>{{ $sale_mst->war_name }}</td>
                        </tr>
                        <tr>

                            {{-- <th>Payment Status</th> --}}
                            {{-- <td>{{ $sale_mst->payment_status }}</td> --}}
                        </tr>
                    </tbody>

                </table>

                <table class="table table-bordered" style="font-size: 15px; font-weight: 400">
                    <thead>
                        <tr class="bg-sidebar">
                            <td class="" style="width:3%">SL</td>
                            <td class="" style="width:35%">Name</td>

                            <td class="text-center" style="width:10%">Qty</td>
                            <td class="text-center" style="width:13%">Price</td>
                            <td class="text-center" style="width:10%">Discount</td>
                            <td class="text-center" style="width:10%">Tax</td>
                            <td class="" style="width:17%; texty">Total Amount</td>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sale_dtl as $sale_key => $sale)
                        <tr>
                            <td>{{ $sale_key + 1 }}</td>
                            <td>{{ $sale->item_name }}</td>
                            <td class="text-center">
                                {{ $sale->item_qty }}</td>
                            <td class="text-center">
                                {{ number_format($sale->mrp_rate, 2, '.', '') }}
                            </td>
                            <td class="text-center">
                                {{ number_format($sale->discount, 2, '.', '') }}
                            </td>
                            <td class="text-center">
                                {{ number_format($sale->vat_amt, 2, '.', '') }}
                            </td>
                            <td style="text-align: right">
                                {{ number_format($sale->tot_payble_amt, 2, '.', '') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No data found</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr style="font-size: 16px; font-weight: 500; text-align: center">
                            <td colspan="2" style="text-align: right">Total</td>
                            <td>{{ $sale_mst->total_qty }}</td>
                            <td></td>
                            <td>{{ number_format($sale_mst->tot_discount, 2, '.', '') }}</td>
                            <td>{{ number_format($sale_mst->tot_vat_amt, 2, '.', '') }}</td>
                            <td style="text-align: right">{{ number_format($sale_mst->net_payable_amt, 2, '.', '') }}</td>
                        </tr>


                    </tfoot>
                </table>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {{-- <h4>Payment Info</h4> --}}
                            <table class="table table-bordered"
                                style="background: #cce5ff82; font-size: 15px; font-weight: 400">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Payment Mode</th>
                                        <th>Details</th>
                                        <th style="text-align: right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($payment_info as $payment)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $payment->p_mode_name }}</td>
                                        <td>
                                            @if ($payment->pay_mode == 1)
                                            Payment by cash
                                            @elseif ($payment->pay_mode == 2)
                                            @php
                                            $bank_info = DB::table('ACC_BANK_INFO')
                                            ->where('bank_code', $payment->bank_code)
                                            ->first();
                                            @endphp
                                            Bank : {{ $bank_info->bank_name }} </br>
                                            Bank Account No : {{ $payment->bank_ac_no }} </br>
                                            Cheque No : {{ $payment->chq_no }} </br>
                                            Cheque Date : {{ $payment->chq_date }}
                                            @elseif ($payment->pay_mode == 3)
                                            -
                                            @elseif ($payment->pay_mode == 4)
                                            -
                                            @endif
                                        </td>
                                        <td style="text-align: right">{{ number_format($payment->tot_paid_amt, 2, '.','') }}</td>
                                    </tr>
                                    @empty

                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" style="text-align: right">Total pay amount: </td>
                                        <td style="text-align: right; font-size: 15px; font-weight: 500">{{
                                            number_format($sale_mst->tot_paid_amt, 2, '.', '') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered" style="background: #e4ede6;">
                            <tbody style="font-size: 16px; font-weight: 500;text-align: right">
                                <tr>
                                    <td>Sub Total</td>
                                    <td>{{ number_format($sale_mst->net_payable_amt, 2, '.', '') }}</td>
                                </tr>
                                <tr>
                                    <td>Shipping</td>
                                    <td>{{ number_format($sale_mst->shipping_amt, 2, '.', '') }}</td>
                                </tr>
                                <tr>
                                    <td>Total Amount</td>
                                    <td>{{ number_format($sale_mst->tot_payable_amt, 2, '.', '') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-2">

                    </div>
                    <div class="col-md-5 mt-3" style="font-size: 25px; font-weight: 500;">
                        <div>
                            <table>
                                <tr style="color: #3C50E0">
                                    <td>Total Amount </td>
                                    <td>&nbsp;:&nbsp;</td>
                                    <td style="text-align: right">{{ number_format($sale_mst->tot_payable_amt, 2,
                                        '.', ',') }}</td>
                                </tr>
                                <tr style="color: darkgreen">
                                    <td>Payment amount </td>
                                    <td>&nbsp;:&nbsp;</td>
                                    <td style="text-align: right">{{ number_format($sale_mst->tot_paid_amt, 2, '.',
                                        ',') }}</td>
                                </tr>
                                <tr style="color: darkred">
                                    <td>Due amount </td>
                                    <td>&nbsp;:&nbsp;</td>
                                    <td style="text-align: right">{{ number_format($sale_mst->tot_due_amt, 2, '.',
                                        ',') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-3 mt-3">
                        <div style="">
                            @if ($sale_mst->payment_status == 'PAID')
                            <img src="{{ asset('public/img/paid.jpg') }}" alt="paid"
                                style="width: 130px; height: 130px;">
                            @else
                            <img src="{{ asset('public/img/due.jpg') }}" alt="due" style="width: 130px; height: 130px;">
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

