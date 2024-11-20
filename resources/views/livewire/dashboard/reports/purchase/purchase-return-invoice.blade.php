<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
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
        <thead>
            <tr>
                <td style="font-size: 10px; text-align:left;"><b>Bill To:</b></td>
                <td style="text-align: right; font-weight:bold; font-size: 10px">Invoice no: {{ $tran_mst->memo_no }}</td>
            </tr>
            <tr>
                <td>{{ $tran_mst->p_name }}</td>
                <td style="text-align: right; font-weight:bold; font-size: 10px">Purchase date: {{ date('d-M-y',
                    strtotime($tran_mst->tran_date)) }}</td>
            </tr>
            <tr>
                <td>{{ $tran_mst->address }}</td>
                <td></td>
            </tr>
            <tr>
                <td>Phone: {{ $tran_mst->phone }}</td>
                <td></td>
            </tr>
        </thead>

    </table>
    <br />
    <br />
    <table class="invoice-items" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th class="invoice-items-head" style="width: 5%">SL</th>
                <th class="invoice-items-head" style="width: 25%">Item</th>
                <th class="invoice-items-head" style="width: 12%; text-align: center">Qty</th>
                <th class="invoice-items-head" style="width: 13%; text-align: center">Rate</th>
                <th class="invoice-items-head" style="width: 10%; text-align: center">Disc</th>
                <th class="invoice-items-head" style="width: 15%; text-align: center">Tax</th>
                <th class="invoice-items-head" style="width: 20%; text-align: center">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($ledgers as $key => $dtl)
            <tr>
                <td style="width: 5%">{{ $key+1 }}</td>
                <td style="width: 25%">{{ $dtl->item_name }}
                    @if($dtl->color_name) | {{ $dtl->color_name }} @endif
                    @if($dtl->item_size_name) | {{ $dtl->item_size_name }} @endif
                </td>
                <td style="text-align: center; width: 12%">{{ $dtl->item_qty }} {{ $dtl->unit_name }}</td>
                <td style="text-align: right; width: 13%">{{ number_format($dtl->pr_rate, 1, '.', '') }}</td>
                <td style="text-align: right; width: 10%">{{ number_format($dtl->discount, 1, '.', '') }}</td>
                <td style="width: 15%; text-align:right">{{ number_format($dtl->vat_amt, 1, '.', '') }}</td>
                <td style="text-align: right; width: 20%">{{ number_format($dtl->tot_payble_amt, 1, '.', '') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7">No data found</td>
            </tr>
            @endforelse

            <tr>
                <td colspan="2" style="text-align: right"><b>Total :</b></td>
                <td style="text-align: center">
                    <b>{{ $tran_mst->total_qty }} </b>
                </td>
                <td></td>
                <td style="text-align: right">
                    <b>{{ number_format($tran_mst->tot_discount, 1, '.', ',') }}</b>
                </td>
                <td style="text-align: right">
                    <b>{{ number_format($tran_mst->tot_vat_amt, 1, '.', ',') }}</b>
                </td>
                <td style="text-align: right">
                    <b>{{ number_format($tran_mst->net_payable_amt, 1, '.', ',') }}</b>
                </td>
            </tr>
            <tr>
                <td style="border: none" colspan="7"></td>
            </tr>
            <tr>
                <th colspan="6" style="text-align: right; font-weight:bold; border: none">Shipping</th>
                <td style="text-align: right">{{ number_format($tran_mst->shipping_amt, 1, '.', ',') }}</td>
            </tr>
            <tr class="grand-total">
                <th colspan="6" style="text-align: right; font-weight:bold; border: none">Total</th>
                <td style="text-align: right">{{ number_format($tran_mst->tot_payable_amt, 1, '.', ',') }}</td>
            </tr>
            <tr>
                <th colspan="6" style="text-align: right; font-weight:bold; border: none">Paid amount</th>
                <td style="text-align: right"><b>{{ number_format($tran_mst->tot_paid_amt, 1, '.', ',') }}</b></td>
            </tr>
            <tr>
                <th colspan="6" style="text-align: right; font-weight:bold; border: none">Due amount</th>
                <td style="color: darkred;text-align: right"><b>{{ number_format($tran_mst->tot_due_amt, 1, '.', ',')}}</b></td>
            </tr>
        </tbody>


    </table>
</body>

</html>
