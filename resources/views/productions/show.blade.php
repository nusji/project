@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <x-breadcrumb :paths="[['label' => 'ระบบการผลิต', 'url' => route('productions.index')], ['label' => 'แสดงรายละเอียดการผลิต']]" />
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-semibold mb-6">รายละเอียดการผลิต</h1>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">วันที่ผลิต</label>
                    <p class="mt-1 text-gray-800">{{ $production->production_date }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">รายละเอียดการผลิต</label>
                    <p class="mt-1 text-gray-800">{{ $production->production_detail ?: '-' }}</p>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">รายการเมนูที่ผลิต</label>
                    <div class="space-y-2">
                        @foreach ($production->productionDetails  as $detail)
                            <div class="flex items-center space-x-2 p-2 bg-gray-100 rounded">
                                <span class="flex-grow">{{ $detail->menu->menu_name }}</span>
                                <span>{{ $detail->quantity }} กิโลกรัม</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-end mt-6">
                    <a href="{{ route('productions.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">
                        กลับไปหน้ารายการ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
