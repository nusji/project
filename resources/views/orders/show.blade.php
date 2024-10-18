@extends('layouts.app')
@section('content')
    <div class="container mx-auto px-4 py-0">
        <x-breadcrumb :paths="[
            ['label' => 'ระบบสั่งซื้อวัตถุดิบ', 'url' => route('orders.index')],
            ['label' => 'รหัสสั่งซื้อที่ ' . $order->id],
        ]" />

        <div class="bg-white rounded-lg overflow-hidden border-2">
            <div class="bg-white-200 text-black p-6">
                <h1 class="text-2xl font-bold">ใบรายละเอียดการสั่งซื้อ</h1>
            </div>
            <!-- ข้อมูลทั่วไป -->
            <div class="pl-10 pr-10">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-md text-gray-600">วันที่/เวลาบันทึกการสั่งซื้อ</p>
                        <p class="font-semibold">{{ $order->order_date->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-md text-gray-600">เลขที่รายการสั่งซื้อ</p>
                        <p class="font-semibold"># {{ $order->id }}</p>
                    </div>
                </div>
                <!-- ข้อมูลพนักงาน -->
                <div class="border-t pt-2 mt-4">
                    <h2 class="text-xl font-semibold mb-4">ข้อมูลพนักงาน</h2>
                    <div class="flex items-center">
                        <div class="mr-4">
                            <img src="{{ $order->employee->profile_image_url ?? asset('images/default-avatar.png') }}"
                                alt="รูปพนักงาน" class="w-16 h-16 object-cover rounded-full border-2 border-gray-300">
                        </div>
                        <div>
                            <p class="font-semibold text-lg">
                                {{ $order->employee->name }}
                                @if ($order->employee->deleted_at)
                                    <span class="text-red-500 text-sm">(ลาออก)</span>
                                @endif
                            </p>
                            <p class="text-gray-500">เบอร์โทร: {{ $order->employee->phone_number }}</p>
                        </div>
                    </div>
                </div>

                <!-- รายละเอียดการสั่งซื้อ -->
                <div class="border-t pt-2 mt-5">
                    <h2 class="text-xl font-semibold mb-4">รายละเอียดการสั่งซื้อ</h2>
                    <p class="text-gray-700 bg-gray-100 p-4 rounded-md">{{ $order->order_detail }}</p>
                </div>

                <!-- ใบเสร็จ -->
                @if ($order->order_receipt)
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 mt-4">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">รูปใบเสร็จ</h2>
                        <div class="flex flex-col sm:flex-row items-center gap-6">
                            <img src="{{ asset('storage/' . $order->order_receipt) }}" alt="รูปใบเสร็จ"
                                class="w-40 h-40 object-cover rounded-md shadow-sm cursor-pointer transition-transform hover:scale-105"
                                onclick="openModal('{{ asset('storage/' . $order->order_receipt) }}')">
                            <div class="flex flex-col gap-3">
                                <button onclick="openModal('{{ asset('storage/' . $order->order_receipt) }}')"
                                    class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded transition-colors">
                                    ดูแบบขยาย
                                </button>
                                <a href="{{ asset('storage/' . $order->order_receipt) }}" download
                                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded transition-colors text-center">
                                    ดาวน์โหลดใบเสร็จ
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="mt-4 border-t">
                    <h2 class="text-xl font-semibold mb-4 mt-4">รายการวัตถุดิบที่สั่งซื้อ</h2>
                    <div class="overflow-x-auto bg-gray-50 rounded-lg border border-gray-200 mb-4">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2 text-left">วัตถุดิบ</th>
                                    <th class="px-4 py-2 text-right">จำนวน</th>
                                    <th class="px-4 py-2 text-left">หน่วย</th>
                                    <th class="px-4 py-2 text-right">ราคาซื้อ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->orderDetails as $detail)
                                    <tr class="border-b border-gray-200">
                                        <td class="px-4 py-2">
                                            @if ($detail->ingredient)
                                                @if ($detail->ingredient->trashed())
                                                    <span
                                                        class="text-red-500">{{ $detail->ingredient->ingredient_name }}</span>
                                                @else
                                                    {{ $detail->ingredient->ingredient_name }}
                                                @endif
                                            @else
                                                <span class="text-red-500">ไม่พบวัตถุดิบ</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 text-right">{{ $detail->quantity }}</td>
                                        <td class="px-4 py-2">
                                            @if ($detail->ingredient)
                                                @if ($detail->ingredient->trashed())
                                                    <span
                                                        class="text-red-500">{{ $detail->ingredient->ingredient_unit }}</span>
                                                @else
                                                    {{ $detail->ingredient->ingredient_unit }}
                                                @endif
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 text-right">{{ number_format($detail->price, 2) }} บาท
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="font-bold bg-gray-100">
                                    <td colspan="3" class="px-4 py-2 text-right">รวมทั้งหมด:</td>
                                    <td class="px-4 py-2 text-right">
                                        {{ number_format($order->orderDetails->sum('price'), 2) }} บาท
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-100 flex justify-between">
                @if (auth()->user()->role === 'owner')
                    <a href="{{ route('orders.edit', $order) }}"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        แก้ไข
                    </a>
                    <form id="delete-form-{{ $order->id }}" action="{{ route('orders.destroy', $order) }}"
                        method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="button"
                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                            onclick="confirmDelete({{ $order->id }})">
                            <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            ลบ
                        </button>
                    </form>
                    <script>
                        function confirmDelete(orderId) {
                            Swal.fire({
                                title: 'คุณแน่ใจหรือไม่?',
                                html: 'การลบรายการสั่งซื้อจะไม่สามารถกู้คืนได้!<br><br>' +
                                    '<span style="color: red; font-weight: bold;">จำนวนวัตถุดิบ จะถูกลบออกตามด้วย</span>',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'ใช่, ลบเลย!',
                                cancelButtonText: 'ยกเลิก'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // หากผู้ใช้ยืนยันการลบ, ส่งแบบฟอร์ม
                                    document.getElementById('delete-form-' + orderId).submit();
                                }
                            });
                        }
                    </script>
                @endif
                <a href="{{ route('orders.index') }}"
                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                    กลับไปหน้ารายการ
                </a>
            </div>
        </div>
    </div>

    <!-- Modal สำหรับแสดงรูปใบเสร็จ -->
    <div id="receiptModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-4 max-w-3xl w-full mx-4 overflow-auto max-h-screen">
            <div class="relative">
                <button onclick="closeModal()"
                    class="absolute top-0 right-0 mt-2 mr-2 text-gray-600 hover:text-gray-800 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
                <img id="modalImage" src="" alt="ใบเสร็จ"
                    class="w-full h-auto max-h-full rounded-lg object-contain">
            </div>
            <div class="mt-4 text-center">
                <a id="downloadButton" href="" download
                    class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded transition-colors">
                    ดาวน์โหลดใบเสร็จ
                </a>
            </div>
        </div>
    </div>


    <script>
        function openModal(imageSrc) {
            const modal = document.getElementById('receiptModal');
            const modalContent = modal.querySelector('div');
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('downloadButton').href = imageSrc;
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.add('opacity-100');
                modalContent.classList.add('scale-100');
            }, 10);
        }

        function closeModal() {
            const modal = document.getElementById('receiptModal');
            const modalContent = modal.querySelector('div');
            modal.classList.remove('opacity-100');
            modalContent.classList.remove('scale-100');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    </script>
@endsection
