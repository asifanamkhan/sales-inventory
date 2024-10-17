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
                <td class="invoice-items-head" style="width:10%;">Date</td>
                <td class="invoice-items-head" style="width:12%;">Damage no</td>
                <td class="invoice-items-head" style="width:16%;text-align: center">Item</td>
                <td class="invoice-items-head" style="width:10%;text-align: center">Branch</td>
                <td class="invoice-items-head" style="width:7%;text-align: center">Qty</td>
                <td class="invoice-items-head" style="width:10%;text-align: center">Rate</td>
                <td class="invoice-items-head" style="width:10%;text-align: center">Vat</td>
                <td class="invoice-items-head" style="width:8%;text-align: center">Disc.</td>
                <td class="invoice-items-head" style="width:12%;text-align: center">Total</td>
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
                $t_qty += $ledger->damage_qty;
                $t_vat += $ledger->vat_amt;
                $t_discount += $ledger->discount;
                $t_total += $ledger->tot_damage_amt;
                @endphp
                <td style="width:5%">{{ $key+1 }}</td>
                <td style="width:10%">{{ date('d-M-y', strtotime($ledger->damage_date)) }}</td>
                <td style="width:12%">{{ $ledger->damage_no }}</td>
                <td style="width:16%; text-align: left">{{ $ledger->item_name }}
                    @if ($ledger->item_size_name)
                    | {{ $ledger->item_size_name }}
                    @endif
                    @if ($ledger->color_name)
                    | {{ $ledger->color_name }}
                    @endif
                </td>
                <td style="width:10%">{{ $ledger->branch_name }}</td>
                <td style="text-align: right; width:7%;">{{ $ledger->damage_qty }}</td>
                <td style="text-align: right; width: 10%">{{ number_format($ledger->pr_rate, 2, '.', '') }}</td>
                <td style="text-align: right; width: 10%">{{ number_format($ledger->vat_amt, 2, '.', '') }}</td>
                <td style="text-align: right; width: 8%">{{ number_format($ledger->discount, 2, '.', '') }}</td>
                <td style="text-align: right; width: 12%">{{ number_format($ledger->tot_damage_amt, 2, '.', '') }}</td>

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
                <th style="text-align: right; font-weight:bold">{{ number_format($t_vat, 2, '.', '') }}</th>
                <th style="text-align: right; font-weight:bold">{{ number_format($t_discount, 2, '.', '') }}</th>
                <th style="text-align: right; font-weight:bold">{{ number_format($t_total, 2, '.', '') }}</th>
            </tr>
        </tbody>
    </table>
</body>

</html>
