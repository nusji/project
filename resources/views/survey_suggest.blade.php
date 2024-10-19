@extends('layouts.guest')

@section('title', 'ยินดีต้อนรับ KAOKANG')

@section('content')
    <div class="w-96 mx-auto mt-0 p-6 bg-white rounded-lg shadow-xl text-black">
        <div class="flex justify-between items-center mb-8">
            <div class="progress-step active">1</div>
            <div class="progress-line"></div>
            <div class="progress-step">2</div>
            <div class="progress-line"></div>
            <div class="progress-step">3</div>
            <div class="progress-line"></div>
            <div class="progress-step">4</div>
        </div>

        <form id="multi-step-form" method="POST" action="{{ route('survey-suggest.queryMenus') }}" class="space-y-6 ">
            @csrf
            <div id="step1" class="step active ">
                <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">ปกติชอบทานรสไหนเป็นพิเศษฮะ</h2>
                <img src="{{ asset('images/cat.png') }}" alt="Cat Chef" class="mx-auto mb-6 w-32 h-32">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <label class="block">
                        <input type="radio" name="flavor_preference" value="เผ็ด" required class="hidden peer">
                        <div
                            class="p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition duration-300 cursor-pointer peer-checked:bg-red-100 peer-checked:ring-2 peer-checked:ring-red-500 h-full flex items-center justify-center">
                            <span class="text-gray-700 font-medium">เผ็ด</span>
                        </div>
                    </label>
                    <label class="block">
                        <input type="radio" name="flavor_preference" value="หวาน" required class="hidden peer">
                        <div
                            class="p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition duration-300 cursor-pointer peer-checked:bg-pink-100 peer-checked:ring-2 peer-checked:ring-pink-500 h-full flex items-center justify-center">
                            <span class="text-gray-700 font-medium">หวาน</span>
                        </div>
                    </label>
                    <label class="block">
                        <input type="radio" name="flavor_preference" value="เปรี้ยว" required class="hidden peer">
                        <div
                            class="p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition duration-300 cursor-pointer peer-checked:bg-yellow-100 peer-checked:ring-2 peer-checked:ring-yellow-500 h-full flex items-center justify-center">
                            <span class="text-gray-700 font-medium">เปรี้ยว</span>
                        </div>
                    </label>
                    <label class="block">
                        <input type="radio" name="flavor_preference" value="เค็ม" required class="hidden peer">
                        <div
                            class="p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition duration-300 cursor-pointer peer-checked:bg-blue-100 peer-checked:ring-2 peer-checked:ring-blue-500 h-full flex items-center justify-center">
                            <span class="text-gray-700 font-medium">เค็ม</span>
                        </div>
                    </label>
                    <label class="block">
                        <input type="radio" name="flavor_preference" value="เค็ม" required class="hidden peer">
                        <div
                            class="p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition duration-300 cursor-pointer peer-checked:bg-blue-100 peer-checked:ring-2 peer-checked:ring-blue-500 h-full flex items-center justify-center">
                            <span class="text-gray-700 font-medium">จืด</span>
                        </div>
                    </label>
                    <label class="block">
                        <input type="radio" name="flavor_preference" value="ทั้งหมด" required class="hidden peer">
                        <div
                            class="p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition duration-300 cursor-pointer peer-checked:bg-blue-100 peer-checked:ring-2 peer-checked:ring-blue-500 h-full flex items-center justify-center">
                            <span class="text-gray-700 font-medium">ทั้งหมด</span>
                        </div>
                    </label>
                </div>
            </div>

            <div id="step2" class="step">
                <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">ชอบกินเนื้ออะไรเป็นพิเศษละ</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach ($meats as $meat)
                        <label class="block">
                            <input type="radio" name="meat_preference" value="{{ $meat->id }}" required
                                class="hidden peer">
                            <div
                                class="p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition duration-300 cursor-pointer peer-checked:bg-green-100 peer-checked:ring-2 peer-checked:ring-green-500 h-full flex items-center justify-center">
                                <span class="text-gray-700 font-medium">{{ $meat->ingredient_name }}</span>
                            </div>
                        </label>
                    @endforeach
                    <label class="block">
                        <input type="radio" name="meat_preference" value="none" required class="hidden peer">
                        <div
                            class="p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition duration-300 cursor-pointer peer-checked:bg-purple-100 peer-checked:ring-2 peer-checked:ring-purple-500 h-full flex items-center justify-center">
                            <span class="text-gray-700 font-medium">ไม่กินเนื้อสัตว์</span>
                        </div>
                    </label>
                </div>
            </div>

            <div id="step3" class="step">
                <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">มีประเภทไหนที่ชอบเป็นพิเศษไหม</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach ($menuType as $type)
                        <label class="block">
                            <input type="radio" name="food_type_preference" value="{{ $type->id }}" required
                                class="hidden peer"> <!-- แก้ไขที่นี่ -->
                            <div
                                class="p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition duration-300 cursor-pointer peer-checked:bg-green-100 peer-checked:ring-2 peer-checked:ring-green-500 h-full flex items-center justify-center">
                                <span class="text-gray-700 font-medium">{{ $type->menu_type_name }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
            
            <!-- แอนิเมชันโหลด -->
            <div id="loading-animation"
                class="hidden fixed inset-0 bg-gray-100 bg-opacity-75 flex items-center justify-center z-50">
                <div
                    class="spinner-border animate-spin inline-block w-12 h-12 border-4 border-t-blue-500 border-blue-200 rounded-full">
                </div>
                <p class="mt-4 text-lg text-gray-700">กำลังโหลดข้อมูล...</p>
            </div>
            <div id="step4" class="step">
                <img src="{{ asset('images/cat.png') }}" alt="Cat Chef" class="mx-auto mb-4" style="width: 150px;">
                <button type="submit"
                    class="w-full px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors">
                    ดูผลลัพธ์แนะนำเมนู
                </button>
            </div>

            <div class="flex justify-between mt-6">
                <button type="button" id="prevBtn"
                    class="hidden px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                    ก่อนหน้า
                </button>
                <button type="button" id="nextBtn"
                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors ml-auto">
                    ถัดไป
                </button>
            </div>
        </form>
    </div>

    <script>
        console.log('Script is running');
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM is loaded');
            const form = document.getElementById('multi-step-form');
            const steps = Array.from(form.getElementsByClassName('step'));
            const progressSteps = document.querySelectorAll('.progress-step');
            const progressLines = document.querySelectorAll('.progress-line');
            const nextBtn = document.getElementById('nextBtn');
            const prevBtn = document.getElementById('prevBtn');
            let currentStep = 0;

            console.log('Steps:', steps.length);
            console.log('Next button:', nextBtn);
            console.log('Prev button:', prevBtn);

            function showStep(stepIndex) {
                console.log('Showing step:', stepIndex);
                steps.forEach((step, index) => {
                    if (index === stepIndex) {
                        step.classList.add('active');
                        step.style.display = 'block';
                    } else {
                        step.classList.remove('active');
                        step.style.display = 'none';
                    }
                });

                progressSteps.forEach((step, index) => {
                    if (index <= stepIndex) {
                        step.classList.add('active');
                    } else {
                        step.classList.remove('active');
                    }
                });

                progressLines.forEach((line, index) => {
                    if (index < stepIndex) {
                        line.classList.add('active');
                    } else {
                        line.classList.remove('active');
                    }
                });

                prevBtn.style.display = stepIndex > 0 ? 'block' : 'none';
                nextBtn.style.display = stepIndex < steps.length - 1 ? 'block' : 'none';
            }

            function validateStep(stepIndex) {
                // Get all required inputs in the current step
                const inputs = steps[stepIndex].querySelectorAll('input[required]');

                // Check if at least one input is checked (for radio buttons)
                for (const input of inputs) {
                    if (input.type === 'radio' && input.checked) {
                        return true;
                    }
                }
                return false;
            }

            nextBtn.addEventListener('click', async function() {
                // Validate the current step before moving forward
                if (validateStep(currentStep)) {
                    if (currentStep === 2) {
                        // แสดงแอนิเมชันโหลด
                        document.getElementById('loading-animation').classList.remove('hidden');
                        // เก็บข้อมูลที่ผู้ใช้กรอก
                        const formData = new FormData(form);
                        // พิมพ์ข้อมูลที่ถูกเก็บใน FormData
                        for (const [key, value] of formData.entries()) {
                            console.log(`${key}: ${value}`);
                        }
                        try {
                            // ส่งข้อมูลไปยัง backend
                            const response = await fetch('{{ route('survey-suggest.queryMenus') }}', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('input[name=_token]')
                                        .value
                                }
                            });
                            const result = await response.json();
                            // ใช้ SweetAlert เพื่อแสดงผลลัพธ์
                            if (result.success) {
                                // สร้างรายการเมนูที่แนะนำ
                                const menuResults = result.menus;
                                let menuHtml =
                                    '<h2 class="text-xl font-semibold mb-4">เมนูแนะนำของคุณ</h2><ul>';
                                menuResults.forEach(menu => {
                                    menuHtml += `<li class="mb-2">${menu.menu_name}</li>`;
                                });
                                menuHtml += '</ul>';

                                // แสดงผลในขั้นตอนที่ 4
                                const step4 = document.getElementById('step4');
                                step4.innerHTML = menuHtml;

                                // ซ่อนแอนิเมชันโหลดและแสดงขั้นตอนที่ 4
                                document.getElementById('loading-animation').classList.add('hidden');
                                currentStep++;
                                showStep(currentStep);
                            } else {
                                swal("ข้อผิดพลาด", result.message || 'ไม่พบเมนูที่แนะนำ', "error");
                                document.getElementById('loading-animation').classList.add('hidden');
                            }
                        } catch (error) {
                            console.error('Error querying menus:', error);
                            swal("เกิดข้อผิดพลาด", "เกิดข้อผิดพลาดในการดึงข้อมูล", "error");
                            document.getElementById('loading-animation').classList.add('hidden');
                        }
                    } else if (currentStep < steps.length - 1) {
                        currentStep++;
                        showStep(currentStep);
                    }
                } else {
                    swal("กรุณาเลือกตัวเลือก", "กรุณาเลือกตัวเลือกก่อนที่จะไปขั้นตอนถัดไป", "warning");
                }
            });


            prevBtn.addEventListener('click', function() {
                console.log('Prev button clicked');
                if (currentStep > 0) {
                    currentStep--;
                    showStep(currentStep);
                }
            });

            showStep(currentStep);
        });
    </script>
@endsection

@push('styles')
    <style>
        .step {
            display: none;
        }

        .step.active {
            display: block;
        }

        .progress-step {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #fff;
        }

        .progress-step.active {
            background-color: #4CAF50;
        }

        .progress-line {
            flex-grow: 1;
            height: 2px;
            background-color: #e0e0e0;
        }

        .progress-line.active {
            background-color: #4CAF50;
        }
    </style>
@endpush
