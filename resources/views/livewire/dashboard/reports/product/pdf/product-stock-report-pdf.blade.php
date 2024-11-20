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
        <thead class="bg-sidebar">
            <tr >
                <td class="invoice-items-head" rowspan="2" style="width: 4%">#</td>
                <td class="invoice-items-head" rowspan="2" style="width: 12%;text-align: center">Item</td>
                <td class="invoice-items-head" rowspan="2" style="width: 10%;text-align: center">Category</td>
                <td class="invoice-items-head" rowspan="2" style="width: 10%;text-align: center">Brand</td>
                <td class="invoice-items-head" rowspan="2" style="width: 8%;text-align: center">Unit</td>
                <td class="invoice-items-head" colspan="7" style="width: 56%;text-align: center">Qty</td>
            </tr>
            <tr class="bg-sidebar">
                <td class="invoice-items-head" style="text-align: center; width:8%">OP</td>
                <td class="invoice-items-head" style="text-align: center; width:8%">PR</td>
                <td class="invoice-items-head" style="text-align: center; width:8%">PR-RT </td>
                <td class="invoice-items-head" style="text-align: center; width:8%">Sale</td>
                <td class="invoice-items-head" style="text-align: center; width:8%">SL-RT</td>
                <td class="invoice-items-head" style="text-align: center; width:8%">Dmg</td>
                <td class="invoice-items-head" style="text-align: center; width:8%">stock</td>
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
            <tr wire:key='{{ $key }}' style="page-break-inside: avoid">
                @php
                // $t_qty += $ledger->item_qty;
                // $t_vat += $ledger->vat_amt;
                // $t_discount += $ledger->discount;
                // $t_total += $ledger->tot_payble_amt;
                @endphp
                <td style="width:4%">{{ $key+1 }}</td>
                <td style="width:12%">{{ $ledger->item_name }}
                    @if ($ledger->item_size_name)
                    | {{ $ledger->item_size_name }}
                    @endif
                    @if ($ledger->color_name)
                    | {{ $ledger->color_name }}
                    @endif
                </td>
                <td style="width:10%">{{ $ledger->catagories_name }}</td>
                <td style="width:10%">{{ $ledger->brand_name }}</td>
                <td style="width:8%; text-align:center">{{ $ledger->unit_name }}</td>
                <td style="text-align: center; width:8%">{{ $ledger->op_qty }}</td>
                <td style="text-align: center; width:8%">{{ $ledger->rc_qty }}</td>
                <td style="text-align: center; width:8%">{{ $ledger->prt_qty }}</td>
                <td style="text-align: center; width:8%">{{ $ledger->sl_qty }}</td>
                <td style="text-align: center; width:8%">{{ $ledger->srt_qty }}</td>
                <td style="text-align: center; width:8%">{{ $ledger->rj_qty }}</td>
                <td style="text-align: center; width:8%">{{ $ledger->stock_qty }}</td>

            </tr>
            @empty
            <tr>
                <td colspan="7">No data found</td>
            </tr>
            @endforelse
            {{-- <tr>
                <th colspan="5" style="text-align: right; font-weight:bold">Total: </th>
                <th style="text-align: right; font-weight:bold">{{ $t_qty }}</th>
                <th style="text-align: right"></th>
                <th style="text-align: right; font-weight:bold">{{ number_format($t_vat, 2, '.', '') }}</th>
                <th style="text-align: right; font-weight:bold">{{ number_format($t_discount, 2, '.', '') }}</th>
                <th style="text-align: right; font-weight:bold">{{ number_format($t_total, 2, '.', '') }}</th>

            </tr> --}}
        </tbody>

    </table>
</body>

</html>
