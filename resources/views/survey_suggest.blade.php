@extends('layouts.guest')

@section('title', 'ยินดีต้อนรับ KAOKANG')

@section('content')
<div class="w-96 mx-auto mt-10 p-6 bg-white rounded-lg shadow-xl text-black">
    <div class="flex justify-between items-center mb-8">
        <div class="progress-step active">1</div>
        <div class="progress-line"></div>
        <div class="progress-step">2</div>
        <div class="progress-line"></div>
        <div class="progress-step">3</div>
        <div class="progress-line"></div>
        <div class="progress-step">4</div>
    </div>

    <form id="multi-step-form" method="POST" action="#" class="space-y-6 ">
        @csrf
        <div id="step1" class="step active">
            <h2 class="text-xl font-semibold mb-4 text-black">ปกติชอบทานรสไหนเป็นพิเศษฮะ</h2>
            <img src="{{ asset('images/cat.png') }}" alt="Cat Chef" class="mx-auto mb-4" style="width: 150px;">
            <select name="flavor_preference" class="w-full p-2 border rounded-md" required>
                <option value="">เลือกรสชาติ</option>
                <option value="spicy">เผ็ด</option>
                <option value="sweet">หวาน</option>
                <option value="sour">เปรี้ยว</option>
                <option value="salty">เค็ม</option>
            </select>
        </div>

        <div id="step2" class="step">
            <h2 class="text-xl font-semibold mb-4">ชอบกินเนื้อไหน</h2>
            <img src="{{ asset('images/cat-chef.png') }}" alt="Cat Chef" class="mx-auto mb-4" style="width: 150px;">
            <select name="meat_preference" class="w-full p-2 border rounded-md" required>
                <option value="">เลือกเนื้อสัตว์</option>
                @foreach($meats as $meat)
                    <option value="{{ $meat->ingredient_name }}">{{ $meat->ingredient_name }}</option>
                @endforeach
                <option value="none">ไม่กินเนื้อสัตว์</option>
            </select>
        </div>

        <div id="step3" class="step">
            <h2 class="text-xl font-semibold mb-4">กินแค่ไหนสักหละ</h2>
            <img src="{{ asset('images/cat-chef.png') }}" alt="Cat Chef" class="mx-auto mb-4" style="width: 150px;">
            <select name="food_type_preference" class="w-full p-2 border rounded-md" required>
                <option value="">เลือกประเภทอาหาร</option>
                <option value="fried">ทอด</option>
                <option value="stir-fried">ผัด</option>
                <option value="curry">แกง</option>
                <option value="boiled">ต้ม</option>
                <option value="spicy-salad">ยำ</option>
            </select>
        </div>

        <div id="step4" class="step">
            <h2 class="text-xl font-semibold mb-4">โห้ยลพรียงมะ</h2>
            <img src="{{ asset('images/cat-chef.png') }}" alt="Cat Chef" class="mx-auto mb-4" style="width: 150px;">
            <button type="submit" class="w-full px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors">
                ขอดูอาหารเลย
            </button>
        </div>

        <div class="flex justify-between mt-6">
            <button type="button" id="prevBtn" class="hidden px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                ก่อนหน้า
            </button>
            <button type="button" id="nextBtn" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors ml-auto">
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

    nextBtn.addEventListener('click', function() {
        console.log('Next button clicked');
        if (currentStep < steps.length - 1) {
            currentStep++;
            showStep(currentStep);
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