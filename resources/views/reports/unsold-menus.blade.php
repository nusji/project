@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-0">
    <!-- Breadcrumb -->
    <x-breadcrumb :paths="[
        ['label' => 'ระบบรายงานและสถิติ', 'url' => route('reports.index')],
        ['label' => 'รายงานเมนูที่ขายไม่หมดและขายหมด'],
    ]" />

    <!-- Page Header -->
    <h1 class="text-2xl font-bold mb-6">รายงานเมนูที่ขายไม่หมดและขายหมด วันที่
        {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</h1>

    <!-- Date Selection Form -->
    <div class="mb-6 flex items-center">
        <label for="date" class="mr-2 font-medium">เลือกวันที่:</label>
        <input type="date" name="date" id="date" value="{{ $date }}" class="border rounded p-2 mr-2">
        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
            ดูรายงาน
        </button>
    </div>

    <!-- Date Navigation -->
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('reports.unsoldMenus', ['date' => \Carbon\Carbon::parse($date)->subDay()->toDateString()]) }}"
            class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded">
            ← วันที่ก่อนหน้า
        </a>
        <a href="{{ route('reports.unsoldMenus', ['date' => \Carbon\Carbon::parse($date)->addDay()->toDateString()]) }}"
            class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded">
            วันที่ถัดไป →
        </a>
    </div>

    <!-- Unsold Menus -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-4">เมนูที่ขายไม่หมด</h2>
        <canvas id="unsoldMenusChart" class="mb-4"></canvas>
        <table class="min-w-full bg-white rounded-lg overflow-hidden shadow">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-3 px-4 text-left">ชื่อเมนู</th>
                    <th class="py-3 px-4 text-right">ปริมาณที่เหลือ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($unsoldMenus as $menu)
                <tr class="hover:bg-gray-100">
                    <td class="py-3 px-4 text-left">{{ $menu->menu->menu_name }}</td>
                    <td class="py-3 px-4 text-right">{{ $menu->remaining_amount }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="py-4 text-center text-gray-500">
                        ไม่มีเมนูที่ขายไม่หมดในวันนี้
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Sold Out Menus -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-4">เมนูที่ขายหมด</h2>
        <canvas id="soldOutMenusChart" class="mb-4"></canvas>
        <table class="min-w-full bg-white rounded-lg overflow-hidden shadow">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-3 px-4 text-left">ชื่อเมนู</th>
                    <th class="py-3 px-4 text-right">จำนวนครั้งที่ขายหมดในเดือนนี้</th>
                </tr>
            </thead>
            <tbody>
                @forelse($soldOutMenus as $menu)
                <tr class="hover:bg-gray-100">
                    <td class="py-3 px-4 text-left">{{ $menu->menu->menu_name }}</td>
                    <td class="py-3 px-4 text-right">
                        {{ $soldOutCounts[$menu->menu_id] ?? 0 }} ครั้ง
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="py-4 text-center text-gray-500">
                        ไม่มีเมนูที่ขายหมดในวันนี้
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Summary of Sold Out Menus -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-4">สรุปจำนวนครั้งที่เมนูขายหมดในเดือนนี้</h2>
        <canvas id="summarySoldOutChart" class="mb-4"></canvas>
        <table class="min-w-full bg-white rounded-lg overflow-hidden shadow">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-3 px-4 text-left">ชื่อเมนู</th>
                    <th class="py-3 px-4 text-right">จำนวนครั้งที่ขายหมด</th>
                </tr>
            </thead>
            <tbody>
                @forelse($menus as $menu)
                <tr class="hover:bg-gray-100">
                    <td class="py-3 px-4 text-left">{{ $menu->menu_name }}</td>
                    <td class="py-3 px-4 text-right">{{ $soldOutCounts[$menu->id] }} ครั้ง</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="py-4 text-center text-gray-500">
                        ไม่มีข้อมูลการขายหมดของเมนูในเดือนนี้
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

    <script>
        // กราฟเมนูที่ขายไม่หมด
        var ctxUnsold = document.getElementById('unsoldMenusChart').getContext('2d');
        var unsoldMenusChart = new Chart(ctxUnsold, {
            type: 'bar',
            data: {
                labels: {!! json_encode($unsoldMenuNames) !!},
                datasets: [{
                    label: 'ปริมาณที่เหลือ',
                    data: {!! json_encode($unsoldMenuQuantities) !!},
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        beginAtZero: true
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // กราฟเมนูที่ขายหมด
        var ctxSoldOut = document.getElementById('soldOutMenusChart').getContext('2d');
        var soldOutMenusChart = new Chart(ctxSoldOut, {
            type: 'pie',
            data: {
                labels: {!! json_encode($soldOutMenuNames) !!},
                datasets: [{
                    label: 'จำนวนเมนูที่ขายหมด',
                    data: {!! json_encode(array_fill(0, count($soldOutMenuNames), 1)) !!},
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        // เพิ่มสีตามจำนวนเมนู
                    ],
                }]
            },
            options: {
                responsive: true,
            }
        });

        // กราฟสรุปจำนวนครั้งที่เมนูขายหมดในเดือนนี้
        var ctxSummary = document.getElementById('summarySoldOutChart').getContext('2d');
        var summarySoldOutChart = new Chart(ctxSummary, {
            type: 'bar',
            data: {
                labels: {!! json_encode($summaryMenuNames) !!},
                datasets: [{
                    label: 'จำนวนครั้งที่ขายหมด',
                    data: {!! json_encode($summarySoldOutCounts) !!},
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        beginAtZero: true
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
