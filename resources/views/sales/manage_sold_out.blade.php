@extends('layouts.pos')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6">จัดการสินค้าที่หมด (Production Details)</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($soldOutDetails->isEmpty())
            <p>ไม่มีสินค้าที่ถูกตั้งค่าเป็นหมด</p>
        @else
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">ID</th>
                        <th class="py-2 px-4 border-b">ชื่อเมนู</th>
                        <th class="py-2 px-4 border-b">ประเภทเมนู</th>
                        <th class="py-2 px-4 border-b">ราคา</th>
                        <th class="py-2 px-4 border-b">จำนวนเหลือ</th>
                        <th class="py-2 px-4 border-b">วันผลิต</th>
                        <th class="py-2 px-4 border-b">สถานะ</th>
                        <th class="py-2 px-4 border-b">การกระทำ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($soldOutDetails as $detail)
                        <tr id="production-detail-row-{{ $detail->id }}">
                            <td class="py-2 px-4 border-b">{{ $detail->id }}</td>
                            <td class="py-2 px-4 border-b">{{ $detail->menu->menu_name }}</td>
                            <td class="py-2 px-4 border-b">{{ $detail->menu->menuType->menu_type_name }}</td>
                            <td class="py-2 px-4 border-b">{{ number_format($detail->menu->menu_price, 2) }} บาท</td>
                            <td class="py-2 px-4 border-b">{{ number_format($detail->remaining_amount, 1) }} กิโลกรัม</td>
                            <td class="py-2 px-4 border-b">{{ \Carbon\Carbon::parse($detail->production_date)->format('d/m/Y') }}</td>
                            <td class="py-2 px-4 border-b">
                                <button onclick="resetSoldOut({{ $detail->id }})" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">
                                    รีเซ็ตสถานะ
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <script>
        function resetSoldOut(detailId) {
            if (!confirm('คุณต้องการรีเซ็ตสถานะสินค้านี้ใช่ไหม?')) {
                return;
            }

            axios.post(`/sales/reset-sold-out/${detailId}`, {
                _token: '{{ csrf_token() }}'
            })
            .then(response => {
                if (response.status === 200) {
                    // ลบแถวที่ถูกรีเซ็ตออกจากตาราง
                    const row = document.getElementById(`production-detail-row-${detailId}`);
                    if (row) {
                        row.remove();
                    }

                    // แสดงข้อความแจ้งเตือน
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ',
                        text: 'รีเซ็ตสถานะสินค้าสำเร็จแล้ว!',
                    });
                }
            })
            .catch(error => {
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถรีเซ็ตสถานะสินค้านี้ได้',
                });
            });
        }
    </script>
@endsection
