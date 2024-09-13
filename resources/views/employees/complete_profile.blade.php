@php
    use Illuminate\Support\Carbon;
    $startDate = auth()->user()->start_date;
    $formattedStartDate = null;

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
    <title>กรอกข้อมูลโปรไฟล์</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Kanit', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        .container {
            max-width: 1000px;
        }

        .profile-card,
        .form-card {
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .profile-card:hover,
        .form-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .btn-update {
            background: linear-gradient(45deg, #3490dc, #6574cd);
            transition: all 0.3s ease;
        }

        .btn-update:hover {
            background: linear-gradient(45deg, #2779bd, #5661b3);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        input[type="text"],
        input[type="date"],
        textarea {
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="date"]:focus,
        textarea:focus {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen p-4">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-2 text-gray-800 text-center">สวัสดีคุณพนักงาน " {{ Auth::user()->first_name }}
            "</h1>
        <p class="text-md font-regular mb-6 text-gray-600 text-center">
            กรุณากรอกข้อมูลให้ครบเพื่อเข้าใช้ระบบ (ระบบจะล็อคไม่ให้ใช้งานหากไม่กรอกข้อมูลให้ครบถ้วน)
        </p>

        <div class="profile-card p-8 mb-6">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">ข้อมูลส่วนตัว</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="mb-2 text-gray-700"><strong>ชื่อ-สกุล :</strong> {{ auth()->user()->first_name }}
                        {{ auth()->user()->last_name }}</p>
                    <p class="mb-2 text-gray-700"><strong>เลขบัตรประชาชน :</strong> {{ auth()->user()->id_card_number }}
                    </p>
                    <p class="mb-2 text-gray-700"><strong>เบอร์โทร :</strong> {{ auth()->user()->phone_number }}</p>
                </div>
                <div>
                    <p class="mb-2 text-gray-700"><strong>ชื่อเข้าใช้ :</strong> {{ auth()->user()->username }}</p>
                    <p class="mb-2 text-gray-700"><strong>ประเภท :</strong>
                        {{ auth()->user()->employment_status }}</p>
                    <p class="mb-2 text-gray-700"><strong>วันที่เริ่มทำงาน:</strong> {{ $formattedStartDate }}</p>
                </div>
            </div>
        </div>

        <form action="{{ route('employees.update_profile') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-card p-8">
                <h2 class="text-2xl font-bold mb-6 text-gray-800">กรอกข้อมูลเพิ่มเติม</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="address" class="block text-gray-700 font-medium mb-2">ที่อยู่ปัจจุบัน</label>
                        <input type="text" id="address" name="address"
                            value="{{ old('address', auth()->user()->address) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('address')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="date_of_birth" class="block text-gray-700 font-medium mb-2">วันเดือนปีเกิด</label>
                        <input type="date" id="date_of_birth" name="date_of_birth"
                            value="{{ old('date_of_birth', auth()->user()->date_of_birth) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('date_of_birth')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="profile_picture" class="block text-gray-700 font-medium mb-2">รูปภาพโปรไฟล์</label>
                        <div class="flex items-center space-x-6">
                            <div
                                class="relative w-24 h-24 overflow-hidden rounded-full bg-gray-100 border-4 border-white shadow-lg">
                                @if (auth()->user()->profile_picture)
                                    <img id="preview" src="{{ asset('storage/' . auth()->user()->profile_picture) }}"
                                        alt="Profile Picture" class="w-full h-full object-cover">
                                @else
                                    <img id="preview" src="{{ asset('images/default-avatar.png') }}"
                                        alt="Default Avatar" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="flex-1">
                                <input type="file" id="profile_picture" name="profile_picture" class="hidden"
                                    onchange="previewImage(this);" accept="image/jpeg,image/png">
                                <label for="profile_picture"
                                    class="cursor-pointer bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg inline-block transition duration-300 shadow-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    เลือกรูปภาพ
                                </label>
                                <p class="text-sm text-gray-500 mt-2">อัปโหลดรูปโปรไฟล์ของคุณ (ไฟล์ .jpg, .png
                                    ขนาดไม่เกิน 2MB)</p>
                            </div>
                        </div>
                        @error('profile_picture')
                            <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="previous_experience"
                            class="block text-gray-700 font-medium mb-2">ประสบการณ์ทำงานที่ผ่านมา</label>
                        <textarea id="previous_experience" name="previous_experience"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            rows="3">{{ old('previous_experience', auth()->user()->previous_experience) }}</textarea>
                        @error('previous_experience')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="bank_account" class="block text-gray-700 font-medium mb-2">บัญชีธนาคาร
                            <small>(ธนาคารอะไร)</small>
                        </label>

                        <!-- Dropdown สำหรับเลือกธนาคาร -->
                        <select id="bank_select" name="bank_select"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            onchange="toggleCustomBankInput()">
                            <option value="">-- เลือกธนาคาร --</option>
                            <option value="ธนาคารกรุงเทพ"
                                {{ old('bank_select', auth()->user()->bank_account) == 'ธนาคารกรุงเทพ' ? 'selected' : '' }}>
                                ธนาคารกรุงเทพ</option>
                            <option value="ธนาคารกสิกรไทย"
                                {{ old('bank_select', auth()->user()->bank_account) == 'ธนาคารกสิกรไทย' ? 'selected' : '' }}>
                                ธนาคารกสิกรไทย</option>
                            <option value="ธนาคารไทยพาณิชย์"
                                {{ old('bank_select', auth()->user()->bank_account) == 'ธนาคารไทยพาณิชย์' ? 'selected' : '' }}>
                                ธนาคารไทยพาณิชย์</option>
                            <option value="other"
                                {{ !in_array(auth()->user()->bank_account, ['ธนาคารกรุงเทพ', 'ธนาคารกสิกรไทย', 'ธนาคารไทยพาณิชย์']) ? 'selected' : '' }}>
                                อื่น ๆ</option>
                        </select>

                        <!-- Input สำหรับกรอกธนาคารเอง -->
                        <input type="text" id="bank_account" name="bank_account"
                            value="{{ old('bank_account', auth()->user()->bank_account) }}"
                            class="w-full px-4 py-2 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            style="{{ !in_array(auth()->user()->bank_account, ['ธนาคารกรุงเทพ', 'ธนาคารกสิกรไทย', 'ธนาคารไทยพาณิชย์']) ? '' : 'display: none;' }}">

                        @error('bank_account')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="bank_account_number"
                            class="block text-gray-700 font-medium mb-2">เลขที่บัญชีธนาคาร</label>
                        <input type="text" id="bank_account_number" name="bank_account_number"
                            value="{{ old('bank_account_number', auth()->user()->bank_account_number) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('bank_account_number')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <button type="submit"
                    class="btn-update w-full py-3 px-4 mt-6 text-white rounded-lg text-lg font-semibold">
                    อัพเดทข้อมูล
                </button>
            </div>
        </form>
    </div>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'ยินดีต้อนรับเข้าสู่ระบบ!',
                text: '{{ session('success') }}',
                confirmButtonText: 'ตกลง',
                timer: 3000
            });
        </script>
    @endif

    <script>
        function previewImage(input) {
            const preview = document.getElementById('preview');
            const file = input.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
            }

            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.src = "{{ asset('images/default-avatar.png') }}";
            }
        }

        function toggleCustomBankInput() {
            const bankSelect = document.getElementById('bank_select');
            const bankInput = document.getElementById('bank_account');

            if (bankSelect.value === 'other') {
                bankInput.style.display = 'block';
                bankInput.value = ''; // เคลียร์ค่าเดิมออกเมื่อเลือก "อื่น ๆ"
            } else {
                bankInput.style.display = 'none';
                bankInput.value = bankSelect.value; // ใช้ค่าที่เลือกจาก dropdown
            }
        }
    </script>


</body>

</html>
