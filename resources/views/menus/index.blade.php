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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ชื่อเมนู</th>
                        <th  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ประเภทเมนู</th>
                        <th  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ราคา</th>
                        <th  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">สถานะขาย</th>
                        <th  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">รูปเมนู</th>
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

                            {{-- Menu Image --}}
                            <td class="py-3 px-6 text-left">
                                @if ($menu->menu_image)
                                    <img src="{{ asset('storage/menu_pictures/' . $menu->menu_image) }}"
                                        alt="{{ $menu->menu_name }}" class="w-16 h-16 object-cover rounded-md">
                                @else
                                    <span class="text-gray-400">ไม่มีรูปภาพ</span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="py-3 px-6 text-left">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('menus.show', $menu) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-indigo-700 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        View
                                    </a>
                                    <a href="{{ route('menus.edit', $menu) }}"
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                        แก้ไขเมนู
                                    </a>
                                    <form action="{{ route('menus.destroy', $menu) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        ลบเมนู
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $menus->links() }}
            </div>
        </div>
    </div>
@endsection
