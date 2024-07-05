<!DOCTYPE html>
<html>

<head>
    <title>Income Report PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1,
        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <h1>Income Report</h1>
    <h2>From {{ request()->input('from_date') }} To {{ request()->input('to_date') }}</h2>

    @foreach ($incomeReports as $type => $data)
        <h2>{{ $type }} Income Report</h2>
        <table>
            <thead>
                <tr>
                    <th>Particular</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $key => $value)
                    <tr>
                        <th>{{ $labels[$key] }}</th>
                        <td class="text-right">{{ $value }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</body>

</html>
