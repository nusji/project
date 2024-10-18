@extends('layouts.app')

@section('title', 'จัดการวัตถุดิบ')

@section('content')
    <div class="container mx-auto px-4 py-0">
        <!-- เรียกใช้ breadcrumb component -->
        <x-breadcrumb :paths="[['label' => 'ระบบเงินเดือน', 'url' => route('payrolls.index')],
         ['label' => 'ฐานเงินเดือน']]" />
        <h2 class="text-2xl font-bold text-gray-800 mb-4">ฐานเงินเดือนพนักงาน</h2>
    <div class="p-6">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ลำดับ
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ชื่อพนักงาน
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ตำแหน่งงาน
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ฐานเงินเดือน
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            จัดการ
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($employees as $index => $employee)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $employee->name }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $employee->employment_type }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ number_format($employee->salary) }}</td>
                                
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('salaries.edit', $employee->id) }}"
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    ปรับเรทฐานเงินเดือน
                                </a>
                            </td>
                        </tr>
                    @endforeach
        </div>

    @endsection
