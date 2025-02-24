<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Net Cash Flow Summary Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        .header {
            margin-bottom: 30px;
        }

        .header h1 {
            color: #9B672A;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>{{ ucfirst($summaryType) }} Net Cash Flow Report</h1>
            <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Period</th>
                    <th>Net Cash Flow</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['labels'] as $index => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td>GMD {{ number_format($data['data'][$index], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>