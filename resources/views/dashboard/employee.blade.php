@extends('layouts.app')

@section('content')
<div class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">แดชบอร์ดพนักงาน</h1>
        
        @if (session('status'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('status') }}</p>
            </div>
        @endif

        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('images/default-avatar.png') }}" alt="Profile Picture" class="w-20 h-20 rounded-full mr-4">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">สวัสดี, คุณ {{ Auth::user()->name }}</h2>
                        <p class="text-gray-600">ประเภทการจ้างงาน : {{ Auth::user()->employment_type }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <p class="text-gray-700"><span class="font-semibold">ชื่อ-นามสกุล:</span> {{ Auth::user()->name }}</p>
                        <p class="text-gray-700"><span class="font-semibold">เบอร์โทรศัพท์:</span> {{ Auth::user()->phone_number }}</p>
                        <p class="text-gray-700"><span class="font-semibold">ชื่อผู้ใช้:</span> {{ Auth::user()->username }}</p>
                        <p class="text-gray-700"><span class="font-semibold">วันที่เริ่มงาน:</span> {{ \Carbon\Carbon::parse(Auth::user()->start_date)->format('d/m/Y') }}</p>
                    </div>
                    <div class="space-y-3">
                        <p class="text-gray-700"><span class="font-semibold">เลขบัตรประชาชน:</span> {{ Auth::user()->id_card_number }}</p>
                        <p class="text-gray-700"><span class="font-semibold">ที่อยู่:</span> {{ Auth::user()->address }}</p>
                        <p class="text-gray-700"><span class="font-semibold">วันเกิด:</span> {{ \Carbon\Carbon::parse(Auth::user()->date_of_birth)->format('d/m/Y') }}</p>
                        <p class="text-gray-700"><span class="font-semibold">บัญชีธนาคาร:</span> {{ Auth::user()->bank_account }}</p>
                        <p class="text-gray-700"><span class="font-semibold">เลขที่บัญชี:</span> {{ Auth::user()->bank_account_number }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-3">กะการทำงานวันนี้</h3>
                <p class="text-gray-700">08:00 - 16:00</p>
            </div>
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-3">ยอดขายของคุณ</h3>
                <p class="text-2xl font-bold text-green-600">฿5,280</p>
            </div>
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-3">คะแนนความพึงพอใจ</h3>
                <p class="text-2xl font-bold text-blue-600">4.8/5</p>
            </div>
        </div>
    </div>
</div>
@endsection