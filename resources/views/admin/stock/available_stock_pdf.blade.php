<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Stock</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 0;
            font-size: 12px;
        }

        .company-info {
            text-align: center;
            padding: 0;
        }

        .table-container {
            margin-top: 10px;
        }

        .company-info h1 {
            margin: 0;
        }

        h1,
        h2,
        h5 {
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
            background-color: white;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .container {
            width: 100%;
            padding: 0 15px;
            margin-right: auto;
            margin-left: auto;
        }

        .terms-conditions {
            margin-top: 20px;
        }

        .terms-cond-div {
            border: 1px solid #dee2e6;
            padding: 10px;
            border-radius: 5px;
        }

        .sign {
            margin-top: 20px;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="company-info">
            <h1>Al Mustafa Poultry</h1>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <td colspan="6" class="text-center">
                            <h2>Available Stock</h2>
                        </td>
                    </tr>
                </thead>
            </table>
            <br>
            <table>
                <thead>
                    <tr>
                        <th class="text-center" style="width:5%">#</th>
                        <th class="text-center">Item</th>
                        <th class="text-center" style="width:15%">Expiry Date</th>
                        <th class="text-center" style="width:10%">Quantity</th>
                        <th class="text-center" style="width:15%">Sale Price</th>
                        <th class="text-center" style="width:15%">Purchase Price</th>
                        <th class="text-center" style="width:15%">Amount</th>
                        <th class="text-center" style="width:15%">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $counter = 1;
                    @endphp
                    @foreach ($stocks as $index => $stock)
                        <tr>
                            <td class="text-center">{{ $counter++ }}</td>
                            <td class="text-center">{{ $stock->name }}</td>
                            <td class="text-center">{{ $stock->expiry_date }}</td>
                            <td class="text-right">{{ $stock->quantity }}</td>
                            <td class="text-right">Rs {{ number_format($stock->last_sale_price ?? 0, 2) }}</td>
                            <td class="text-right">Rs {{ number_format($stock->last_purchase_price ?? 0, 2) }}</td>
                            <td class="text-right">Rs {{ $stock->total_cost }}</td>
                            <td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6" class="text-right">
                            <h4>Grand Total :</h4>
                        </td>
                        <td class="text-right">Rs {{ number_format($stocks->sum('total_cost'), 2) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>

            <div class="terms-conditions">
                <div class="sign">
                    <p class="text-start">
                    <ul>Signature______________________________</ul>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
