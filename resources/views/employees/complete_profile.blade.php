@php
    use Illuminate\Support\Carbon;
    $startDate = auth()->user()->start_date;
    $formattedStartDate = null;

    // ตรวจสอบว่า start_date เป็นสตริงและทำการแปลง
    if ($startDate && is_string($startDate)) {
        $formattedStartDate = Carbon::parse($startDate)->format('d/m/Y');
    } elseif ($startDate instanceof Carbon) {
        $formattedStartDate = $startDate->format('d/m/Y');
    }
@endphp

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Profile</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">สวัสดี {{ Auth::user()->first_name }}</h1>
        <h3 class="text-md font-regular mb-6"> กรุณากรอกข้อมูลให้ครบเพื่อเข้าใช้ระบบ ( ระบบจะล็อคไม่ให้ใช้งาน
            หากไม่กรอกข้อมูลให้ครบถ้วน )</h3>
        <div class="bg-white border border-gray-300 shadow-lg rounded-lg p-8 mb-3">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <h4 class="text-xl font-bold mb-4">ชื่อ-สกุล: {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h4>
                    <p class="mb-2 text-gray-700 "><strong>เลขบัตรประชาชน:</strong> {{ auth()->user()->id_card_number }}</p>
                    <p class="mb-2 text-gray-700"><strong>เบอร์โทร:</strong> {{ auth()->user()->phone_number }}</p>
                    <p class="mb-2 text-gray-700"><strong>ชื่อเข้าใช้/username:</strong> {{ auth()->user()->username }}</p>
                    <p class="mb-2 text-gray-700"><strong>ประเภทพนักงาน  :</strong> {{ auth()->user()->employment_status }}</p>
                    <p class="mb-2 text-gray-700"><strong>วันที่เริ่มทำงาน    :</strong> {{ $formattedStartDate }}</p>
                </div>
            </div>
        </div>
        <form action="{{ route('employees.update_profile') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="bg-white border border-gray-300 shadow-lg rounded-lg p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="address" class="block text-gray-700 font-medium mb-1">ที่อยู่ปัจจุบัน</label>
                        <input type="text" id="address" name="address"
                            value="{{ old('address', auth()->user()->address) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('address')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="date_of_birth" class="block text-gray-700 font-medium mb-1">วันเดือนปีเกิด</label>
                        <input type="date" id="date_of_birth" name="date_of_birth"
                            value="{{ old('date_of_birth', auth()->user()->date_of_birth) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('date_of_birth')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="profile_picture" class="block text-gray-700 font-medium mb-1">รูปภาพโปรไฟล์</label>
                        <input type="file" id="profile_picture" name="profile_picture"
                            class="w-full text-gray-700 border border-gray-300 rounded-lg focus:outline-none">
                        @error('profile_picture')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="previous_experience"
                            class="block text-gray-700 font-medium mb-1">ประสบการณ์ทำงานที่ผ่านมา</label>
                        <textarea id="previous_experience" name="previous_experience"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('previous_experience', auth()->user()->previous_experience) }}</textarea>
                        @error('previous_experience')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="relevant_education"
                            class="block text-gray-700 font-medium mb-1">ประวัติการศึกษา</label>
                        <textarea id="relevant_education" name="relevant_education"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('relevant_education', auth()->user()->relevant_education) }}</textarea>
                        @error('relevant_education')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="bank_account" class="block text-gray-700 font-medium mb-1">บัญชีธนาคาร
                            <small>(ธนาคารอะไร)</small></label>
                        <input type="text" id="bank_account" name="bank_account"
                            value="{{ old('bank_account', auth()->user()->bank_account) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('bank_account')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="bank_account_number"
                            class="block text-gray-700 font-medium mb-1">เลขที่บัญชีธนาคาร</label>
                        <input type="text" id="bank_account_number" name="bank_account_number"
                            value="{{ old('bank_account_number', auth()->user()->bank_account_number) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('bank_account_number')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="emergency_contact"
                            class="block text-gray-700 font-medium mb-1">คนติดต่อฉุกเฉินและเบอร์โทร</label>
                        <input type="text" id="emergency_contact" name="emergency_contact"
                            value="{{ old('emergency_contact', auth()->user()->emergency_contact) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('emergency_contact')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="health_info" class="block text-gray-700 font-medium mb-1">ปัญหาสุขภาพต่างๆ</label>
                        <textarea id="health_info" name="health_info"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('health_info', auth()->user()->health_info) }}</textarea>
                        @error('health_info')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="religion" class="block text-gray-700 font-medium mb-1">ศาสนา</label>
                        <input type="text" id="religion" name="religion"
                            value="{{ old('religion', auth()->user()->religion) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('religion')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <button type="submit"
                    class="w-full py-2 px-4 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Update
                    Profile</button>
            </div>
        </form>
    </div>
</body>

</html>
