@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- เรียกใช้ breadcrumb component -->
        <x-breadcrumb :paths="[['label' => 'ระบบรายงานและสถิติ', 'url' => route('reports.index')], ['label' => '']]" />
        <h1 class="text-2xl font-bold mb-4">รายงานปริมาณวัตถุดิบคงเหลือ</h1>

        <!-- ตารางแสดงวัตถุดิบคงเหลือ -->
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">ชื่อวัตถุดิบ</th>
                    <th class="py-2 px-4 border-b">หน่วย</th>
                    <th class="py-2 px-4 border-b">ปริมาณคงเหลือ</th>
                    <th class="py-2 px-4 border-b">ปริมาณขั้นต่ำ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ingredients as $ingredient)
                    <tr
                        class="text-center {{ $ingredient->ingredient_stock <= $ingredient->minimum_quantity ? 'bg-red-100' : '' }}">
                        <td class="py-2 px-4 border-b">{{ $ingredient->ingredient_name }}</td>
                        <td class="py-2 px-4 border-b">{{ $ingredient->ingredient_unit }}</td>
                        <td class="py-2 px-4 border-b">{{ $ingredient->ingredient_stock }}</td>
                        <td class="py-2 px-4 border-b">{{ $ingredient->minimum_quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
