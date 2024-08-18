@extends('layouts.app')
@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-8 text-gray-800">จัดการเมนูข้าวแกง</h1>

        <a href="{{ route('menus.create') }}"
            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300 ease-in-out">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                </path>
            </svg> เพิ่มเมนูใหม่
        </a>
        <a href="{{ route('menu_types.index') }}"
            class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition duration-300 ease-in-out">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16">
                </path>
            </svg>
            จัดการประเภท
        </a>

        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 uppercase text-sm leading-normal">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ชื่อเมนูข้าวแกง</th>
                        <th  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">รายละเอียด</th>
                        <th  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ประเภทเมนูข้าวแกง</th>
                        <th  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ราคา</th>
                        <th  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">สถานะขาย</th>
                        <th  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">รูปเมนูข้าวแกง</th>
                        <th  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($menus as $menu)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <div class="font-medium">{{ $menu->menu_name }}</div>
                            </td>
                            <td class="py-3 px-6 text-left">
                                <div class="truncate w-48">{{ $menu->menu_detail }}</div>
                            </td>
                            <td class="py-3 px-6 text-left">
                                <span class="bg-blue-200 text-blue-600 py-1 px-3 rounded-full text-xs">
                                    {{ $menu->menuType->menu_type_name }}
                                </span>
                            </td>
                            <td class="py-3 px-6 text-left">
                                <div class="font-medium">{{ number_format($menu->menu_price, 2) }}</div>
                            </td>
                            <td class="py-3 px-6 text-left">
                                <span
                                    class="@if ($menu->menu_status) bg-green-200 text-green-600 @else bg-red-200 text-red-600 @endif py-1 px-3 rounded-full text-xs">
                                    {{ $menu->menu_status ? 'Available' : 'Not Available' }}
                                </span>
                            </td>
                            <td class="py-3 px-6 text-left">
                                @if ($menu->menu_image)
                                    <img src="{{ asset('storage/menu_pictures/' . $menu->menu_image) }}"
                                        alt="{{ $menu->menu_name }}" class="w-16 h-16 object-cover rounded-md">
                                @else
                                    <span class="text-gray-400">No Image</span>
                                @endif
                            </td>
                            <td class="py-3 px-6 text-left">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('menus.edit', $menu) }}"
                                        class="text-blue-500 hover:text-blue-600 transition duration-300 ease-in-out">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('menus.destroy', $menu) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-500 hover:text-red-600 transition duration-300 ease-in-out">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
