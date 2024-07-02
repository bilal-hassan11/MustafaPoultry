<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
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
            background-color: white
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
            <h1>Tawakkal Marketing Traders</h1>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <td colspan="3" class="text-center">
                            <h2>{{ strtoupper($type) }} INVOICE</h2>
                        </td>
                        <td class="text-center">
                            <h2>#{{ $OtherInvoice[0]->invoice_no }}</h2>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th style="width: 20%;">Invoice To :</th>
                        <td> {{ $OtherInvoice[0]->account->name }}</td>
                        <th style="width: 20%;">Date: </th>
                        <td class="text-center"> {{ date('d-M-Y', strtotime($OtherInvoice[0]->date)) }}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td colspan="3">{{ $OtherInvoice[0]->account->address }}</td>
                    </tr>
                    <tr>
                        <th colspan="3" style="text-align: right">Previous Balance</th>
                        <td style="text-align: right">Rs {{ number_format($previous_balance, 2) }}</td>
                    </tr>
                </tbody>
            </table>
            <br>
            <table>
                <thead>
                    <tr>
                        <th class="text-center" style="width:5%">#</th>
                        <th class="text-center">Item</th>
                        <th class="text-center" style="width:10%">Qty</th>
                        <th class="text-center" style="width:10%">Price</th>
                        <th class="text-center" style="width:12%">Discount (Rs)</th>
                        <th class="text-center" style="width:12%">Discount (%)</th>
                        <th class="text-center" style="width:15%">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $subtotal = 0;
                        $totalDiscountRs = 0;
                    @endphp
                    @foreach ($OtherInvoice as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">
                                {{ $item->item->name }}{{ $item->expiry_date ? ' - ' . $item->expiry_date : '' }}
                            </td>

                            <td class="text-right">{{ abs($item->quantity) }}</td>
                            <td class="text-right">Rs
                                {{ number_format($type == 'Purchase' ? $item->purchase_price : $item->sale_price, 2) }}
                            </td>
                            <td class="text-right">Rs {{ number_format($item->discount_in_rs, 2) }}</td>
                            <td class="text-right">{{ number_format($item->discount_in_percent, 2) }}%</td>
                            <td class="text-right">Rs {{ number_format($item->net_amount, 2) }}</td>
                        </tr>
                        @php
                            $subtotal +=
                                abs($item->quantity) *
                                ($type == 'Purchase' ? $item->purchase_price : $item->sale_price);
                            $totalDiscountRs += $item->discount_in_rs;
                        @endphp
                    @endforeach

                    <tr style="border: none;">
                        <td colspan="7"></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;" colspan="4">
                            <h4>Additional Notes</h4>
                        </td>

                        <td class="text-right" colspan="2">
                            <h4>Sub Total :</h4>
                        </td>
                        <td class="text-right">Rs {{ number_format($subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td rowspan="3" colspan="4">
                            {{ $data->additional_notes ?? '' }}
                        </td>
                        <td class="text-right" colspan="2">
                            <h4>Total Discount :</h4>
                        </td>
                        <td class="text-right">Rs {{ number_format($totalDiscountRs, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="2">
                            <h4>Total Bill :</h4>
                        </td>
                        <td class="text-right">Rs {{ number_format($subtotal - $totalDiscountRs, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="2">
                            <h4>Closing Balance :</h4>
                        </td>
                        <td class="text-right">Rs
                            {{ number_format($previous_balance + ($subtotal - $totalDiscountRs), 2) }}</td>
                    </tr>
                    <tr style="border: none;">
                        <td colspan="7"></td>
                    </tr>
                <tbody>
            </table>

            <div class="terms-conditions">
                <div class="terms-cond-div">
                    <h5>Terms & Conditions</h5>
                    <ul>
                        <li>If You Find Any Query Than Contact Under 1 Week Otherwise Any Changes Would Not Be
                            Accepted!
                        </li>
                    </ul>
                </div>
                <div class="sign">
                    <p class="text-start">
                    <ul>Signature______________________________</ul>
                    </p>
                </div>
            </div>
        </div>
</body>

</html>
