<div>
    <div wire:loading class="spinner-border text-primary custom-loading">
        <span class="sr-only">Loading...</span>
    </div>
    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">
            <i class="fa fa-plus"></i> Requisition Details
        </h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">

                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('requisition') }}">Requisition</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('requisition-edit', $requisition_id ) }}"
                        style="color: #3C50E0">edit</a></li>
            </ol>
        </nav>
    </div>
    <div class="card p-4">
        <div class="row mb-3">
            <div class="col-auto ">
                <a href='{{ route('requisition-edit', $requisition_id) }}' class="btn btn-warning">
                    <i class="fa-solid fa-pen-to-square"></i>
                    Edit
                </a>
            </div>
            <div class="col-auto">
                <a target="_blank" href="{{ route('requisition-invoice', $requisition_id) }}" class="btn btn-success">
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
                            <td style="width: 15%;">Requisition Date :</td>
                            <td>{{ date('d-M-y', strtotime($requisition_mst->tran_date)) }}</td>
                            <td style="width: 10%">Memo No :</td>
                            <td>{{ $requisition_mst->memo_no }}</td>
                            <td style="width: 15%">Requisition status :</td>
                            <td>{{ $requisition_mst->status }}</td>
                        <tr />
                        <tr style="font-size: 16px; font-weight: 500">
                            <td>Supplier :</td>
                            <td>{{ $requisition_mst->p_name }}</td>
                        </tr>
                        <tr>

                            {{-- <th>Payment Status</th> --}}
                            {{-- <td>{{ $requisition_mst->payment_status }}</td> --}}
                        </tr>
                    </tbody>

                </table>

                <table class="table table-bordered" style="font-size: 15px; font-weight: 400">
                    <thead>
                        <tr class="bg-sidebar">
                            <td class="" style="width:3%">SL</td>
                            <td class="" style="width:35%">Name</td>
                            <td class="text-center" style="width:10%">Qty</td>
                            <td class="text-center" style="width:10%">Price</td>
                            <td class="text-center" style="width:10%">Discount</td>
                            <td class="text-center" style="width:10%">Tax</td>
                            <td class="" style="width:20%; texty">Total Amount</td>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($requisition_dtl as $requisition_key => $requisition)
                        <tr>
                            <td>{{ $requisition_key + 1 }}</td>
                            <td>{{ $requisition->item_name }}</td>
                            <td class="text-center">
                                {{ $requisition->item_qty }}</td>
                            <td class="text-center">
                                {{ number_format($requisition->pr_rate, 2, '.', '') }}
                            </td>
                            <td class="text-center">
                                {{ number_format($requisition->discount, 2, '.', '') }}
                            </td>
                            <td class="text-center">
                                {{ number_format($requisition->vat_amt, 2, '.', '') }}
                            </td>
                            <td style="text-align: right">
                                {{ number_format($requisition->tot_payble_amt, 2, '.', '') }}
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
                            <td>{{ $requisition_mst->total_qty }}</td>
                            <td></td>
                            <td>{{ number_format($requisition_mst->tot_discount, 2, '.', '') }}</td>
                            <td>{{ $requisition_mst->tot_vat_amt }}</td>
                            <td style="text-align: right">{{ $requisition_mst->net_payable_amt }}</td>
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
                                            number_format($requisition_mst->tot_paid_amt, 2, '.', '') }}</td>
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
                                    <td>{{ number_format($requisition_mst->net_payable_amt, 2, '.', '') }}</td>
                                </tr>
                                <tr>
                                    <td>Shipping</td>
                                    <td>{{ number_format($requisition_mst->shipping_amt, 2, '.', '') }}</td>
                                </tr>
                                <tr>
                                    <td>Total Amount</td>
                                    <td>{{ number_format($requisition_mst->tot_payable_amt, 2, '.', '') }}</td>
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
                                    <td style="text-align: right">{{ number_format($requisition_mst->tot_payable_amt, 2,
                                        '.', ',') }}</td>
                                </tr>
                                <tr style="color: darkgreen">
                                    <td>Advance Payment amount </td>
                                    <td>&nbsp;:&nbsp;</td>
                                    <td style="text-align: right">{{ number_format($requisition_mst->tot_paid_amt, 2, '.',',') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
