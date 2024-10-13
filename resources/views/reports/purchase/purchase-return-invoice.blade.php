<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
            color: #333;
        }

        .invoice-container {
            /* width: 750px */
            /* Fixed width to prevent overflow in PDF */
            margin: 0 auto;
            /* padding: 30px; */
            /* border: 1px solid #ddd; */
            /* background-color: #f9f9f9; */
        }

        .invoice-header {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }

        .invoice-header .logo {
            float: left;
        }

        .invoice-header .company-details {
            float: right;
            text-align: right;
        }

        .logo img {
            max-width: 120px;
            height: auto;
        }

        .company-details h2 {
            margin: 0;
            font-size: 30px;
        }

        .company-details p {
            margin: 5px 0;
        }

        .invoice-title {
            text-align: center;
            margin-bottom: 20px;
        }

        .invoice-title h3 {
            font-size: 25px;
            margin: 0;
            text-transform: uppercase;
            color: #4CAF50;
        }

        .invoice-to {
            margin-bottom: 30px;
        }

        .invoice-to p {
            margin: 5px 0;
            font-size: 14px;
        }

        .invoice-details {
            width: 100%;
            margin-bottom: 30px;
        }

        .invoice-details td {
            padding: 10px;
            font-size: 14px;
            border-bottom: 1px solid #ddd;
        }

        .invoice-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .invoice-items th,
        .invoice-items td {
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 13px;
        }

        .invoice-items th {
            background-color: #4CAF50;
            color: #fff;
        }

        .total-section {
            text-align: right;
            /* margin-top: 10px; */
        }

        .total-section table {
            width: 100%;
            border-collapse: collapse;
        }

        .total-section th,
        .total-section td {
            padding: 10px;
            font-size: 13px;
            border: 1px solid #ddd;
            text-align: right;
        }

        .total-section th {
            /* background-color: #f5f5f5; */
        }

        .total-section .grand-total th,
        .total-section .grand-total td {
            background-color: #4CAF50;
            color: #fff;
            font-weight: bold;
            padding: 10px;
            font-size: 13px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
        }

        .footer p {
            margin: 5px 0;
            color: #888;
        }

        .payment-status {
            display: inline-block;
            padding: 15px 30px;
            font-size: 28px;
            font-weight: bold;
            border: 4px solid;
            text-align: center;
            border-radius: 8px;
            margin: 20px 0;
            width: 200px;
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <div class="logo">
                <!-- Replace with your logo image -->
                <img src="data:image/jpeg;base64,{{$base64Logo}}">
            </div>
            <div class="company-details">
                <h2>{{ $company->comp_name }}</h2>
                <p>{{ $company->comp_add }}</p>
                <p><b>Email:</b> {{ $company->comp_email }} | <b></b> {{ $company->comp_phone }}</p>
                <div class="invoice-title">
                    <h3 style="text-align: right">Purchase Return Invoice</h3>
                </div>
                <table class="invoice-details">
                    <tr style="border-bottom: none">
                        <td style="border-bottom: none; text-align:right; padding: 0"><strong>Invoice Number:</strong>
                            {{ $tran_mst->memo_no }}</td>
                    </tr>
                    <tr style="border-bottom: none">
                        <td style="border-bottom: none; text-align:right;padding: 0"><strong>Invoice Date:</strong> {{
                            date('d-M-y', strtotime($tran_mst->tran_date)) }}</td>
                    </tr>
                </table>
            </div>

        </div>

        <div style="padding-top: 120px" class="invoice-to">
            <p><strong>Bill To:</strong></p>
            <p>{{ $tran_mst->p_name }}</p>
            <p>{{ $tran_mst->address }}</p>
            <p>Phone: {{ $tran_mst->phone }}</p>
        </div>


        <div style="min-height: 680px">
            <table class="invoice-items">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Description</th>
                        <th>Qty</th>
                        <th>Rate</th>
                        <th>Discount</th>
                        <th>Tax</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($resultDtls as $key => $dtl)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $dtl->item_name }}
                            @if($dtl->color_name) | {{ $dtl->color_name }} @endif
                            @if($dtl->item_size_name) | {{ $dtl->item_size_name }} @endif
                        </td>
                        <td style="text-align: center">{{ $dtl->item_qty }}</td>
                        <td style="text-align: center">{{ number_format($dtl->pr_rate, 2, '.', '') }}</td>
                        <td style="text-align: center">{{ number_format($dtl->discount, 2, '.', '') }}</td>
                        <td style="text-align: center">{{ number_format($dtl->vat_amt, 2, '.', '') }}</td>
                        <td style="text-align: right">{{ number_format($dtl->tot_payble_amt, 2, '.', '') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">No data found</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2"></td>
                        <td style="text-align: center">
                            <b>{{ $tran_mst->total_qty }}</b>
                        </td>
                        <td></td>
                        <td style="text-align: center">
                            <b>{{ number_format($tran_mst->tot_discount, 2, '.', '') }}</b>
                        </td>
                        <td style="text-align: center">
                            <b>{{ number_format($tran_mst->tot_vat_amt, 2, '.', '') }}</b>
                        </td>
                        <td style="text-align: right">
                            <b>{{ number_format($tran_mst->net_payable_amt, 2, '.', '') }}</b>
                        </td>
                    </tr>
                </tfoot>
            </table>

            <div class="total-section">
                <table>
                    {{-- <tr>
                        <th rowspan="5" style="border: none;text-align: center;">
                        </th>
                    </tr> --}}
                    <tr>
                        <th>Shipping</th>
                        <td>{{ number_format($tran_mst->shipping_amt, 2, '.', '') }}</td>
                    </tr>
                    <tr class="grand-total">
                        <th>Total</th>
                        <td>{{ number_format($tran_mst->tot_payable_amt, 2, '.', '') }}</td>
                    </tr>
                    <tr>
                        <th>Received amount</th>
                        <td><b>{{ number_format($tran_mst->tot_paid_amt, 2, '.', '') }}</b></td>
                    </tr>
                    <tr>
                        <th>Due amount</th>
                        <td style="color: darkred"><b>{{ number_format($tran_mst->tot_due_amt, 2, '.', '') }}</b></td>
                    </tr>
                </table>

                <div style="text-align: center; padding-top: 20px">
                    <img style="width: 120px; " src="data:image/jpeg;base64,{{$base64PaymentImg}}">

                </div>
            </div>



        </div>



        <div class="footer">
            <p>Design & Developed By: InfoTech IT Solutions, www.infotechitsolutionsbd.com </p>
            <p></p>
        </div>
    </div>
</body>

</html>
