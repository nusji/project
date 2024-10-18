@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="bg-blue-600 px-6 py-4">
                <h1 class="text-2xl font-bold text-white">แดชบอร์ดเจ้าของร้าน</h1>
            </div>

            <div class="p-6">
                @if (session('status'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                        <p>{{ session('status') }}</p>
                    </div>
                @endif

                <div class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">สวัสดี เจ้าของร้าน คุณ {{ Auth::user()->first_name }}</h2>
                    <p class="text-gray-600">ยินดีต้อนรับสู่แดชบอร์ดของคุณ ที่นี่คุณสามารถดูข้อมูลส่วนตัวและจัดการร้านของคุณได้</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-semibold text-gray-700 mb-3">ข้อมูลส่วนตัว</h3>
                        <ul class="space-y-2">
                            <li><span class="font-medium">ชื่อ:</span> {{ Auth::user()->first_name }}</li>
                            <li><span class="font-medium">นามสกุล:</span> {{ Auth::user()->last_name }}</li>
                            <li><span class="font-medium">ชื่อผู้ใช้:</span> {{ Auth::user()->username }}</li>
                            <li><span class="font-medium">เบอร์โทรศัพท์:</span> {{ Auth::user()->phone_number }}</li>
                            <li><span class="font-medium">วันเดือนปีเกิด:</span> {{ Auth::user()->date_of_birth }}</li>
                            <li><span class="font-medium">ที่อยู่:</span> {{ Auth::user()->address }}</li>
                        </ul>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-semibold text-gray-700 mb-3">ข้อมูลการทำงาน</h3>
                        <ul class="space-y-2">
                            <li><span class="font-medium">สถานะการจ้างงาน:</span> {{ Auth::user()->employment_status }}</li>
                            <li><span class="font-medium">วันที่เริ่มงาน:</span> {{ Auth::user()->start_date }}</li>
                            <li><span class="font-medium">บทบาท:</span> {{ Auth::user()->role }}</li>
                            <li><span class="font-medium">เลขบัตรประชาชน:</span> {{ Auth::user()->id_card_number }}</li>
                            <li><span class="font-medium">ประสบการณ์ทำงาน:</span> {{ Auth::user()->previous_experience }}</li>
                        </ul>
                    </div>
                </div>

                <div class="mt-6 bg-gray-50 p-4 rounded-lg shadow">
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">ข้อมูลบัญชีธนาคาร</h3>
                    <ul class="space-y-2">
                        <li><span class="font-medium">บัญชีธนาคาร:</span> {{ Auth::user()->bank_account }}</li>
                        <li><span class="font-medium">เลขที่บัญชี:</span> {{ Auth::user()->bank_account_number }}</li>
                    </ul>
                </div>

                @if(Auth::user()->profile_picture)
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-3">รูปโปรไฟล์</h3>
                        <img src="{{ Auth::user()->profile_picture }}" alt="รูปโปรไฟล์" class="w-32 h-32 rounded-full shadow-lg">
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
