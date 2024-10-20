@extends('layouts.app')
@section('content')
    <div class="container mx-auto px-4 py-0">
        <!-- เรียกใช้ breadcrumb component -->
        <x-breadcrumb :paths="[['label' => 'ระบบช่วยเหลือจัดสรรเมนู', 'url' => route('allocations.index')], ['label' => '']]" />
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">การจัดสรรเมนู</h2>
            <a href="{{ route('allocations.create') }}"
                class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                เพิ่มการจัดสรร
            </a>
        </div>

        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            วันที่จัดสรร
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ดำเนินการ
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($allocations as $allocation)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $allocation->allocation_date }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('allocations.show', $allocation->id) }}"
                                    class="text-blue-600 hover:text-blue-800">
                                    ดูรายละเอียด
                                </a>
                                <a href="{{ route('allocations.edit', $allocation->id) }}"
                                    class="text-yellow-600 hover:text-yellow-800">
                                    แก้ไข
                                </a>
                                <form action="{{ route('allocations.destroy', $allocation->id) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800"
                                        onclick="return confirm('คุณแน่ใจหรือไม่ที่จะลบรายการนี้?')">
                                        ลบ
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
                    <!-- Pagination Links -->
        <div class="mt-4">
            {{ $allocations->links() }}
        </div>
        </div>

    </div>
@endsection
