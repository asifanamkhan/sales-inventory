<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Expense report</title>
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
                <td class="invoice-items-head" style="width:15%;">Exp Dt</td>
                <td class="invoice-items-head" style="width:15%;">Exp no</td>
                <td class="invoice-items-head" style="width:20%;text-align: center">Type</td>
                <td class="invoice-items-head" style="width:15%;text-align: center">Amount</td>
                <td class="invoice-items-head" style="width:15%;text-align: center">Paid</td>
                <td class="invoice-items-head" style="width:15%;text-align: center">Due</td>
            </tr>
        </thead>
        <tbody>
            @php
            $t_amt = 0;
            $t_paid = 0;
            $t_due = 0;
            @endphp
            @forelse ($ledgers as $key => $ledger)
            @php
                $t_amt += $ledger->total_amount;
                $t_paid += $ledger->tot_paid_amt;
                $t_due += $ledger->tot_due_amt;
                @endphp
            <tr>
                <td style="width:5%">{{ $key+1 }}</td>
                <td style="width:15%">{{ date('d-M-y', strtotime($ledger->expense_date)) }}</td>
                <td style="width:15%">{{ $ledger->expense_no }}</td>
                <td style="width:20%">
                    {{ $ledger->expense_name }}
                </td>
                <td style="text-align: right;width:15%">{{ number_format($ledger->total_amount, 1, '.', '') }}</td>
                <td style="text-align: right;width:15%">{{ number_format($ledger->tot_paid_amt, 1, '.', '') }}</td>
                <td style="text-align: right;width:15%">{{ number_format($ledger->tot_due_amt, 1, '.', '') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4">No data found</td>
            </tr>
            @endforelse
            <tr>
                <th colspan="4" style="text-align: right;font-weight:bold">Total: </th>
                <th style="text-align: right;font-weight:bold">{{ number_format($t_amt, 1, '.', '') }}</th>
                <th style="text-align: right;font-weight:bold">{{ number_format($t_paid, 1, '.', '') }}</th>
                <th style="text-align: right;font-weight:bold">{{ number_format($t_due, 1, '.', '') }}</th>
            </tr>
        </tbody>

    </table>
</body>

</html>
