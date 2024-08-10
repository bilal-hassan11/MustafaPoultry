<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .invoice-container {
            width: 100%;
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border: 1px solid #000;
        }

        .header {
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0;
        }

        .info {
            margin: 20px 0;
        }

        .info div {
            margin-bottom: 10px;
        }

        .table-container {
            width: 100%;
            border-collapse: collapse;
        }

        .table-container th,
        .table-container td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .table-container th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>

    <div class="invoice-container">
        <div class="header">
            <h1>جی ایچ پولٹری کمیشن شاپ</h1>
            <p>Near Al-Noor Medical Center Khari Quarter Mirpurkhas</p>
            <p>رقم: 128</p>
            <p>نام </p>
        </div>

        <div class="info">
            <div>تاریخ: __________________</div>
        </div>
        <h3>Medicine</h3>
        <table class="table-container">
            <tr>
                <th>Invoice No</th>
                <th>Date</th>
                <th>Amount</th>
            </tr>

            @foreach ($medicineInvoices as $invoice)
                <tr>
                    <td>{{ $invoice->invoice_no }}</td>
                    <td>{{ $invoice->date }}</td>
                    <td>{{ $invoice->amount }}</td>
                </tr>
            @endforeach

        </table>
        <h3>Feed</h3>
        <table class="table-container">
            <tr>
                <th>Invoice No</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>

            @foreach ($feedInvoices as $invoice)
                <td>{{ $invoice->invoice_no }}</td>
                <td>{{ $invoice->item->name }}</td>
                <td>{{ $invoice->sale_price }}</td>
                <td>{{ $invoice->net_amount }}</td>
            @endforeach

        </table>
        <h3>Chick</h3>
        <table class="table-container">
            <tr>
                <th>Invoice No</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>

            @foreach ($chickInvoices as $invoice)
                <td>{{ $invoice->invoice_no }}</td>
                <td>{{ $invoice->item->name }}</td>
                <td>{{ $invoice->sale_price }}</td>
                <td>{{ $invoice->net_amount }}</td>
            @endforeach

        </table>
        <h3>Murghi</h3>
        <table class="table-container">
            <tr>
                <th>Invoice No</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>

            @foreach ($murghiInvoices as $invoice)
                <td>{{ $invoice->invoice_no }}</td>
                <td>{{ $invoice->item->name }}</td>
                <td>{{ $invoice->purchase_price }}</td>
                <td>{{ $invoice->net_amount }}</td>
            @endforeach

        </table>
        <h3>Cashbook</h3>
        <table class="table-container">
            <tr>
                <th>Sr.No</th>
                <th>Narration</th>
                <th>Payment</th>
                <th>Receipt</th>
            </tr>
            @foreach ($cashBook as $index => $book)
                <td>{{ $index + 1 }}</td>
                <td>{{ $book->narration }}</td>
                <td>{{ $book->payment_ammount }}</td>
                <td>{{ $book->receipt_ammount }}</td>
            @endforeach

        </table>
    </div>

</body>

</html>
