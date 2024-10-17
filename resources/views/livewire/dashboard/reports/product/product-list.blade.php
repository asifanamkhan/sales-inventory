<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product list</title>
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
                <th class="invoice-items-head"  style="width:16%">Name</th>
                <th class="invoice-items-head"  style="width:15%">Group</th>
                <th class="invoice-items-head"  style="width:10%">Category</th>
                <th class="invoice-items-head" style="width:10%">Brand</th>
                <th class="invoice-items-head" style="width:6%">Unit</th>
                <th class="invoice-items-head" style="width:5%">Size</th>
                <th class="invoice-items-head" style="width:10%">Color</th>
                <th class="invoice-items-head" style="width:8%; text-align: center">PR</th>
                <th class="invoice-items-head" style="width:8%; text-align: center">MRP</th>
                <th class="invoice-items-head" style="width:7%; text-align: center">Vat</th>
            </tr>
        </thead>
        <tbody>

            @forelse ($products as $product)
                <tr style="page-break-inside: avoid">
                    <td style="width: 5%">{{ $loop->iteration }}</td>
                    <td style="width: 16%">{{ $product->item_name }}</td>
                    <td style="width: 15%">{{ $product->group_name }}</td>
                    <td style="width: 10%">{{ $product->catagories_name }}</td>
                    <td style="width: 10%">{{ $product->brand_name  }}</td>
                    <td style="width: 6%">{{ $product->unit_name  }}</td>
                    <td style="width: 5%">{{ $product->item_size_name  }}</td>
                    <td style="width: 10%">{{ $product->color_name  }}</td>
                    <td style="width: 8%; text-align: center">{{ $product->pr_rate  }}</td>
                    <td style="width: 8%; text-align: center">{{ $product->mrp_rate  }}</td>
                    <td style="width: 7%; text-align: center">
                        @if ($product->vat_rate)
                            {{ $product->vat_rate  }}%
                        @else

                        @endif

                    </td>
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

