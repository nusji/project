@extends('layouts.app')

@section('content')
<div class="p-4">
    <div class="max-w-lg mx-auto">
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="bg-red-100 border-b border-red-200 py-3 px-4">
                <h2 class="text-lg font-semibold text-red-700">{{ __('Access Denied') }}</h2>
            </div>

            <div class="p-4">
                <p class="text-gray-600">{{ __('คุณไม่มีสิทธิเข้าถึงหน้านี้') }}</p>
            </div>

            <div class="bg-gray-50 px-4 py-3">
                <button onclick="window.history.back()" class="text-sm text-blue-500 hover:underline">
                    {{ __('ย้อนกลับ') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
