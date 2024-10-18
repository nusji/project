<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Slip</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 80%; margin: 0 auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Salary Slip</h1>
        <table>
            <tr>
                <th>Employee Name</th>
                <td>{{ $payroll->employee->name }}</td>
            </tr>
            <tr>
                <th>Payment Date</th>
                <td>{{ \Carbon\Carbon::parse($payroll->payment_date)->format('Y-m-d') }}</td>
            </tr>
            <tr>
                <th>Base Salary</th>
                <td>{{ number_format($payroll->employee->base_salary, 2) }}</td>
            </tr>
            <tr>
                <th>Bonus</th>
                <td>{{ number_format($payroll->bonus, 2) }}</td>
            </tr>
            <tr>
                <th>Deductions</th>
                <td>{{ number_format($payroll->deductions, 2) }}</td>
            </tr>
            <tr>
                <th>Net Salary</th>
                <td>{{ number_format($payroll->net_salary, 2) }}</td>
            </tr>
        </table>
    </div>
</body>
</html>