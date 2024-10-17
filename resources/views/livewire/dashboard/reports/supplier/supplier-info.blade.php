<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
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
    <table class="invoice-items" cellspacing="0" cellpadding="4">
        <thead >
            <tr >
                <th class="invoice-items-head"  style="width:5%">SL</th>
                <th class="invoice-items-head"  style="width:20%">Name</th>
                <th class="invoice-items-head"  style="width:10%">Code</th>
                <th class="invoice-items-head" style="width:15%">Category</th>
                <th class="invoice-items-head" style="width:15%">Email</th>
                <th class="invoice-items-head" style="width:15%">Phone</th>
                <th class="invoice-items-head" style="width:20%">Address</th>
            </tr>
        </thead>
        <tbody>

            @forelse ($suppliers as $supplier)
                <tr nobr="true">
                    <td style="width: 5%">{{ $loop->iteration }}</td>
                    <td style="width: 20%">{{ $supplier->p_name }}</td>
                    <td style="width: 10%">{{ $supplier->supplier_code }}</td>
                    <td style="width: 15%">{{ $supplier->supplier_cat_name  }}</td>
                    <td style="width: 15%">{{ $supplier->email  }}</td>
                    <td style="width: 15%">{{ $supplier->phone  }}</td>
                    <td style="width: 20%">{{ $supplier->address  }}</td>
                </tr>

            @empty
                <tr>
                    <td colspan="7">No data found</td>
                </tr>
            @endforelse
        </tbody>

    </table>
</body>

</html>
