
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
        
        <div class="p-6 space-y-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-md text-gray-600">วันที่/เวลาบันทึกการสั่งซื้อ</p>
                    <p class="font-semibold">{{ $order->order_date }}</p>
                </div>
                <div class="text-right">
                    <p class="text-md text-gray-600">เลขที่รายการสั่งซื้อ</p>
                    <p class="font-semibold"># {{ $order->id }}</p>
                </div>
            </div>

            <div  class="p-6 space-y-6">
                <div class="inline-box">
                    <p class="text-md text-gray-600">พนักงานบันทึกรายการ</p>
                    <p class="font-semibold">ชื่อ : {{ $order->employee->name }} <p class="font-medium text-gray-400" >เบอร์โทร {{ $order->employee->phone_number }}</p></p>
                </div>
                <div>
                    <h2 class="text-xl font-semibold mb-2">รายละเอียด</h2>
                    <p class="text-gray-700 bg-gray-50 p-4 rounded-md">{{ $order->order_detail }}</p>
                </div>
            </div>
            

            @if ($order->order_receipt)
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">รูปใบเสร็จ</h2>
                    <div class="flex flex-col sm:flex-row items-center gap-6">
                        <img src="{{ asset('storage/' . $order->order_receipt) }}" 
                             alt="รูปใบเสร็จ"
                             class="w-40 h-40 object-cover rounded-md shadow-sm cursor-pointer transition-transform hover:scale-105"
                             onclick="openModal('{{ asset('storage/' . $order->order_receipt) }}')">
                        <div class="flex flex-col gap-3">
                            <button onclick="openModal('{{ asset('storage/' . $order->order_receipt) }}')"
                                    class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded transition-colors">
                                ดูแบบขยาย
                            </button>
                            <a href="{{ asset('storage/' . $order->order_receipt) }}" 
                               download
                               class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded transition-colors text-center">
                                ดาวน์โหลดใบเสร็จ
                            </a>
                        </div>
                    </div>
                </div>
            @endif
            <div>
                <h2 class="text-xl font-semibold mb-4">รายการวัตถุดิบ</h2>
                <div class="overflow-x-auto bg-gray-50 rounded-lg border border-gray-200">
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
                                                <span class="text-red-500">{{ $detail->ingredient->ingredient_name }}</span>
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
                                                <span class="text-red-500">{{ $detail->ingredient->ingredient_unit }}</span>
                                            @else
                                                {{ $detail->ingredient->ingredient_unit }}
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-right">{{ number_format($detail->price, 2) }} บาท</td>
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
            <a href="{{ route('orders.edit', $order) }}"
               class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded transition duration-300">
                แก้ไข
            </a>
            <form action="{{ route('orders.destroy', $order) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded transition duration-300"
                        onclick="return confirm('คุณแน่ใจหรือไม่ที่จะลบรายการนี้?')">
                    ลบ
                </button>
            </form>
            <a href="{{ route('orders.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition duration-300">
                กลับไปหน้ารายการ
            </a>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="receiptModal" 
     class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center transition-opacity duration-300">
    <div class="bg-white rounded-lg p-6 max-w-3xl w-full mx-4 transform transition-transform duration-300 scale-95">
        <div class="relative">
            <button onclick="closeModal()" 
                    class="absolute top-0 right-0 mt-2 mr-2 text-gray-600 hover:text-gray-800 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <img id="modalImage" src="" alt="ใบเสร็จ" class="w-full rounded-lg">
        </div>
        <div class="mt-4 text-center">
            <p class="text-gray-600 mb-2">คลิกที่ปุ่มด้านล่างเพื่อดาวน์โหลดใบเสร็จ</p>
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
