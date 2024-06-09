<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $voucher->receipt_ammount > 0 ? 'Receipt Voucher' : 'Payment Voucher' }}</title>
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
            padding: 16px;
            /* Increase padding to make rows larger */
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
            <h1>Tawakkal Marketing Traders</h1>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <td colspan="3" class="text-center">
                            <h2>{{ $voucher->receipt_ammount > 0 ? 'RECEIPT VOUCHER' : 'PAYMENT VOUCHER' }}</h2>
                        </td>
                        <td class="text-center">
                            <h2>#{{ str_pad($voucher->id, 5, '0', STR_PAD_LEFT) }}</h2>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th style="width: 20%;">Account Name :</th>
                        <td>{{ $voucher->account->name ?? '' }}</td>
                        <th style="width: 20%;">Date: </th>
                        <td class="text-center">{{ $voucher->entry_date }}</td>
                    </tr>

                </tbody>
            </table>
            <br>
            <table>
                <thead>
                    <tr>
                        <th class="text-center" style="width:5%">#</th>
                        <th class="text-center">Narration</th>
                        <th class="text-center" style="width:15%">Amount</th>
                    </tr>
                </thead>
                @php
                    $amount = $voucher->receipt_ammount > 0 ? $voucher->receipt_ammount : $voucher->payment_ammount;
                @endphp
                <tbody>
                    <tr>
                        <td class="text-center">{{ 1 }}</td>
                        <td>{{ $voucher->narration }}</td>
                        <td class="text-right">Rs {{ number_format($amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-right"><strong>Total</strong></td>
                        <td class="text-right"><strong>Rs
                                {{ number_format($amount, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
            <div class="terms-conditions">
                <div class="terms-cond-div">
                    <h5>Remarks</h5>
                    <p>{{ $voucher->remarks }}</p>
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
