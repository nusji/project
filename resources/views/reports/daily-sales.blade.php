<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายงานยอดขายประจำวัน</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-4 text-center">รายงานยอดขายประจำวัน: {{ $date }}</h1>

        <p class="mb-4 text-center"><strong>ยอดขายรวม:</strong> ฿{{ number_format($totalAmount, 2) }}</p>

        <!-- ฟอร์มเลือกวันที่ -->
        <form action="{{ route('reports.dailySales') }}" method="GET" class="flex justify-center items-center mb-6">
            <label for="date" class="mr-2 font-semibold">เลือกวันที่:</label>
            <input type="date" name="date" id="date" value="{{ $date }}" class="shadow appearance-none border rounded-l py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-r">
                เรียกดู
            </button>
        </form>

        <!-- ตารางแสดงรายละเอียดยอดขาย -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">หมายเลขขาย</th>
                        <th class="py-2 px-4 border-b">พนักงาน</th>
                        <th class="py-2 px-4 border-b">เมนู</th>
                        <th class="py-2 px-4 border-b">จำนวน</th>
                        <th class="py-2 px-4 border-b">ราคา</th>
                        <th class="py-2 px-4 border-b">รวม</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sales as $sale)
                        @foreach ($sale->saleDetails as $detail)
                            <tr class="text-center">
                                <td class="py-2 px-4 border-b">#{{ $sale->id }}</td>
                                <td class="py-2 px-4 border-b">{{ $sale->employee->name }}</td>
                                <td class="py-2 px-4 border-b">{{ $detail->menu->menu_name }}</td>
                                <td class="py-2 px-4 border-b">{{ $detail->quantity }}</td>
                                <td class="py-2 px-4 border-b">฿{{ number_format($detail->price, 2) }}</td>
                                <td class="py-2 px-4 border-b">฿{{ number_format($detail->price * $detail->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center">ไม่มีข้อมูลการขายสำหรับวันนี้</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- ปุ่มกลับไปยังหน้ารายงาน -->
        <div class="mt-6 text-center">
            <a href="{{ route('reports.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                กลับสู่หน้ารายงาน
            </a>
        </div>
    </div>
</body>
</html>
