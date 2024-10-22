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
            /* position: absolute; */
        }

        .invoice-items th,
        .invoice-items td {
            border: 1px solid #ddd;
        }

        .invoice-items-head {
            background-color: #4CAF50;
            color: #fff;
            font-size: 10px;
            font-weight: bold
        }
    </style>
</head>

<body>

    <table cellspacing="0" cellpadding="2">
        <tr>
            <td style="font-weight: bold; font-size: 10px; text-align:left; width: 60%">Type: {{ $mst->p_name }}</td>
            <td style="font-weight: bold; font-size: 10px; text-align:right; width: 40%">
                Invoice no: {{ $mst->memo_no }}
            </td>
        </tr>
        <tr>

            <td colspan="2" style="font-weight: bold; font-size: 10px; text-align:right">
                Expense date: {{ date('d-M-y', strtotime($mst->expense_date)) }}
            </td>
        </tr>
    </table>
    <br />
    <br />
    <table class="invoice-items" cellspacing="0" cellpadding="7">
        <thead>
            <tr>
                <td class="invoice-items-head" style="width:10%;">SL</td>

                <td class="invoice-items-head" style="width:60%;text-align: center">Description</td>
                <td class="invoice-items-head" style="width:30%;text-align: center">Amount</td>
            </tr>
        </thead>
        <tbody>
            @php
            $t_total = 0;
            @endphp
            @forelse ($ledgers as $key => $ledger)
            <tr wire:key='{{ $key }}'>
                @php
                $t_total += $ledger->item_amount;
                @endphp
                <td style="width:10%">{{ $key+1 }}</td>
                <td style="width:60%; text-align: left">{{$ledger->description}}</td>
                <td style="width:30%; text-align: right">{{ number_format($ledger->item_amount, 2, '.', ',') }}</td>

            </tr>
            @empty
            <tr>
                <td colspan="3">No data found</td>
            </tr>
            @endforelse
            <tr style="border:none">
                <th style="border:none !important" colspan="3"></th>
            </tr>
            <tr>
                <td class="invoice-items-head" colspan="2" style="text-align: right; font-weight:bold">Total: </td>
                <td class="invoice-items-head" style="text-align: right; font-weight:bold">{{ number_format($t_total, 2, '.', ',') }}</td>

            </tr>
            <tr>
                <th colspan="2" style="text-align: right"><b>Total paid</b></th>
                <td class="" style="text-align: right; font-weight:bold">{{ number_format($mst->tot_paid_amt, 2, '.', ',') }}</td>
            </tr>
            <tr>
                <th colspan="2" style="text-align: right"><b>Due</b></th>
                <td colspan="2" class="" style="text-align: right; font-weight:bold">{{ number_format($mst->tot_due_amt, 2, '.', ',') }}</td>
            </tr>
        </tbody>

    </table>
</body>

</html>
