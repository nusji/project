<!-- resources/views/sales/pdf.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>รายงานการขาย</title>
    <style>
        @font-face {
            font-family: 'Sarabun';
            src: url('{{ public_path('fonts/Sarabun-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        body {
            font-family: 'Sarabun', sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>รายงานการขาย</h2>
    <table>
        <thead>
            <tr>
                <th>รหัสการขาย</th>
                <th>วันที่เวลาขาย</th>
                <th>พนักงานขาย</th>
                <th>จ่ายด้วย</th>
                <th>ยอดรวม</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales as $sale)
                <tr>
                    <td>{{ $sale->id }}</td>
                    <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('Y-m-d H:i') }}</td>
                    <td>{{ $sale->employee->name }}</td>
                    <td>{{ ucfirst($sale->payment_type) }}</td>
                    <td>{{ number_format($sale->saleDetails->sum(function ($detail) {
                        return $detail->menu ? $detail->menu->menu_price * $detail->quantity : 0;
                    }), 2) }} บาท</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
