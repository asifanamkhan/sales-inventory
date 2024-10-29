<div>
    <div wire:loading class="spinner-border text-primary custom-loading">
        <span class="sr-only">Loading...</span>
    </div>
    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">
            <i class="fa fa-plus"></i> LC Details
        </h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">

                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('lc') }}">LC</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('lc-edit', $lc_id ) }}"
                        style="color: #3C50E0">edit</a></li>
            </ol>
        </nav>
    </div>
    <div class="card p-4">
        <div class="row mb-3">
            <div class="col-auto ">
                <a href='{{ route('lc-edit', $lc_id) }}' class="btn btn-warning">
                    <i class="fa-solid fa-pen-to-square"></i>
                    Edit
                </a>
            </div>
            {{-- <div class="col-auto">
                <a target="_blank" href="{{ route('lc-invoice', $lc_id) }}" class="btn btn-success">
                    <i class="fa-solid fa-print"></i>
                    Print
                </a>
            </div> --}}
        </div>
        <div class="responsive-table" style="font-size: 0.9em !important;">
            <div class="responsive-table" style="font-size: 0.9em !important;">
                <table class="table table-bordered">
                    <tbody>
                        <tr style="">
                            <th style="width: 15%;">LC no :</th>
                            <td>{{ date('d-M-y', strtotime($lc_mst->lc_no)) }}</td>
                            <th style="width: 15%;">Issue Date :</th>
                            <td>{{ date('d-M-y', strtotime($lc_mst->issue_date)) }}</td>
                            <th style="width: 10%">Memo No :</th>
                            <td>{{ $lc_mst->memo_no }}</td>
                        </tr>
                        <tr style="">
                            <th style="width: 15%">LC type :</th>
                            <td>{{ $lc_mst->lc_type }}</td>
                            <th style="width: 10%">Applicant :</th>
                            <td>{{ $lc_mst->applicant }}</td>
                            <th style="width: 15%">LC status :</th>
                            <td style="@if ($lc_mst->lc_status == 1)
                                    background: #D4EDDA;
                                @elseif($lc_mst->lc_status == 2)
                                    background: #FFF3CD;
                                @elseif($lc_mst->lc_status == 3)
                                    background: #FFF3CD;
                                @endif"
                            >
                                @if ($lc_mst->lc_status == 1)
                                    Active
                                @elseif($lc_mst->lc_status == 2)
                                    Expired
                                @elseif($lc_mst->lc_status == 3)
                                    Utilized
                                @endif
                            </td>
                        </tr>
                        <tr style="">
                            <th style="width: 15%;">LC amount :</th>
                            <td>{{ number_format($lc_mst->lc_amount, 1, '.', '') }}</td>
                            <th style="width: 15%;">Expiry Date :</th>
                            <td>{{ date('d-M-y', strtotime($lc_mst->expiry_date)) }}</td>
                            <th style="width: 10%">Shipment date :</th>
                            <td>{{ $lc_mst->shipment_date }}</td>
                        </tr>

                        <tr style="">
                            <th style="width: 15%;">Beneficiary :</th>
                            <td>{{ $lc_mst->beneficiary }}</td>
                            <th style="width: 15%;">Issuing_banke :</th>
                            <td>{{ $lc_mst->issuing_bank }}</td>
                            <th style="width: 10%">Advising bank :</th>
                            <td>{{ $lc_mst->advising_bank }}</td>
                        </tr>

                        <tr style="">
                            <th style="width: 15%;">Negotiation period :</th>
                            <td>{{ $lc_mst->negotiation_period }}</td>
                            <th style="width: 15%;">Incoterms :</th>
                            <td>{{ $lc_mst->incoterms }}</td>
                            <th style="width: 10%">Port of loading :</th>
                            <td>{{ $lc_mst->port_of_loading }}</td>
                        </tr>

                        <tr style="">
                            <th style="width: 15%;">Port of discharge :</th>
                            <td>{{ $lc_mst->port_of_discharge }}</td>
                            <th style="width: 15%;">Goods description :</th>
                            <td>{!! $lc_mst->goods_description !!}</td>
                        </tr>


                    </tbody>

                </table>
            </div>
        </div>
    </div>

