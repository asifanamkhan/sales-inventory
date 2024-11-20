<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Supplier Ledger</title>
    <style>
        body{
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
    <table class="invoice-items" cellspacing="0" cellpadding="2">
        <thead>
            <tr>
                <th colspan="8" cellpadding="0" style="border:none">
                    <b>Supplier:</b> {{ $ledgers[0]->supplier_name }}<br>
                    <b>Contact:</b> {{ $ledgers[0]->email }} | {{ $ledgers[0]->phone }}<br>
                    <b>Address:</b> {{ $ledgers[0]->address }}<br>
                </th>
            </tr>
            <tr >
                <th class="invoice-items-head" style="width:4%">#</th>
                <th class="invoice-items-head" style="width:10%">Date</th>
                <th class="invoice-items-head" style="width:13%">Memo no</th>
                <th class="invoice-items-head" style="text-align: center;width:15%">Total amt</th>
                <th class="invoice-items-head" style="text-align: center;width:14%">Paid amt</th>
                <th class="invoice-items-head" style="text-align: center;width:11%">Return</th>
                <th class="invoice-items-head" style="text-align: center;width:11%">Rt rec</th>
                <th class="invoice-items-head" style="text-align: center;width:11%">Rt due</th>
                <th class="invoice-items-head" style="text-align: center;width:11%">Due amt</th>
            </tr>
        </thead>
        <tbody>
            @php
            $t_tot_payable_amt = 0;
            $t_total_paid = 0;
            $t_return_amt = 0;
            $t_return_paid_amt = 0;
            $t_receivable_amt = 0;
            $t_total_due = 0;
            @endphp
            @forelse ($ledgers as $key => $ledger)
            <tr style="page-break-inside: avoid">
                @php
                $t_tot_payable_amt += (float)$ledger->tot_payable_amt;
                $t_total_paid += (float)$ledger->total_paid;
                $t_return_amt += (float)$ledger->return_amt;
                $t_return_paid_amt += (float)$ledger->return_paid_amt;
                $t_receivable_amt += (float)$ledger->receivable_amt;
                $t_total_due += (float)$ledger->total_due;
                @endphp
                <td style="width:4%">{{ $key+1 }}</td>
                <td style="width:10%">{{ date('d-M-y', strtotime($ledger->tran_date)) }}</td>
                <td style="width:13%">{{ $ledger->memo_no }}</td>
                <td style="text-align: right;width:15%">{{ number_format($ledger->tot_payable_amt, 1, '.', ',') }}</td>
                <td style="text-align: right;width:14%">{{ number_format($ledger->total_paid, 1, '.', ',') }}</td>
                <td style="text-align: right;width:11%">{{ number_format($ledger->return_amt, 1, '.', ',') }}</td>
                <td style="text-align: right;width:11%">{{ number_format($ledger->return_paid_amt, 1, '.', ',') }}</td>
                <td style="text-align: right;width:11%">{{ number_format($ledger->receivable_amt, 1, '.', ',') }}</td>
                <td style="text-align: right;width:11%">{{ number_format($ledger->total_due, 1, '.', ',') }}</td>

            </tr>
            @empty
            <tr>
                <td colspan="7">No data found</td>
            </tr>
            @endforelse
            <tr>
                <th colspan="3" style="text-align: right; font-weight: bold">Total: </th>
                <th style="text-align: right; font-weight: bold">{{ number_format($t_tot_payable_amt, 1, '.', ',') }}</th>
                <th style="text-align: right; font-weight: bold">{{ number_format($t_total_paid, 1, '.', ',') }}</th>
                <th style="text-align: right; font-weight: bold">{{ number_format($t_return_amt, 1, '.', ',') }}</th>
                <th style="text-align: right; font-weight: bold">{{ number_format($t_return_paid_amt, 1, '.', ',') }}</th>
                <th style="text-align: right; font-weight: bold">{{ number_format($t_receivable_amt, 1, '.', ',') }}</th>
                <th style="text-align: right; font-weight: bold">{{ number_format($t_total_due, 1, '.', ',') }}</th>
            </tr>
        </tbody>


    </table>
</body>

</html>
