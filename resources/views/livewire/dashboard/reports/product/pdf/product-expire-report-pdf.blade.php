<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product list</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 9px;
            color: #333;
        }

        .invoice-items {
            width: 100%;
            border-collapse: collapse;
            position: absolute;
        }

        .invoice-items th,
        .invoice-items td {
            border: 1px solid #ddd;
        }

        .invoice-items-head {
            background-color: #4CAF50;
            color: #fff;
        }
    </style>
</head>

<body>
    <br />
    <table class="invoice-items" cellspacing="0" cellpadding="4">
        <thead>
            <tr>
                <td class="invoice-items-head" style="width:5%;">#</td>
                <td class="invoice-items-head" style="width:15%;">PR Dt</td>
                <td class="invoice-items-head" style="width:15%;">PR no</td>
                <td class="invoice-items-head" style="width:20%;text-align: center">Item</td>
                <td class="invoice-items-head" style="width:15%;text-align: center">Branch</td>
                <td class="invoice-items-head" style="width:15%;text-align: center">PR Qty</td>
                <td class="invoice-items-head" style="width:15%;text-align: center">Exp Dt</td>

            </tr>
        </thead>
        <tbody>
            @php
            $t_qty = 0;

            @endphp
            @forelse ($ledgers as $key => $ledger)
            <tr wire:key='{{ $key }}'>
                @php
                $t_qty += $ledger->item_qty;
                
                @endphp
                <td style="width:5%">{{ $key+1 }}</td>
                <td style="width:15%">{{ date('d-M-y', strtotime($ledger->tran_date)) }}</td>
                <td style="width:15%">{{ $ledger->purchase_no }}</td>
                <td style="width:20%; text-align: left">{{ $ledger->item_name }}
                    @if ($ledger->item_size_name)
                    | {{ $ledger->item_size_name }}
                    @endif
                    @if ($ledger->color_name)
                    | {{ $ledger->color_name }}
                    @endif
                </td>
                <td style="width:15%">{{ $ledger->branch_name }}</td>
                <td style="text-align: right; width:15%;">{{ $ledger->item_qty }}</td>
                <td style="width:15%">{{ date('d-M-y', strtotime($ledger->expire_date)) }}</td>

            </tr>
            @empty
            <tr>
                <td colspan="7">No data found</td>
            </tr>
            @endforelse
            <tr>
                <th colspan="5" style="text-align: right; font-weight:bold">Total: </th>
                <th style="text-align: right; font-weight:bold">{{ $t_qty }}</th>
                <th style="text-align: right"></th>
                {{-- <th style="text-align: right; font-weight:bold">{{ number_format($t_vat, 2, '.', '') }}</th>
                <th style="text-align: right; font-weight:bold">{{ number_format($t_discount, 2, '.', '') }}</th>
                <th style="text-align: right; font-weight:bold">{{ number_format($t_total, 2, '.', '') }}</th> --}}

            </tr>
        </tbody>

    </table>
</body>

</html>
