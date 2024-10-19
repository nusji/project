@extends('layouts.guest')

@section('title', 'เกี่ยวกับเรา - ครัวคุณแม่')

@section('content')
<div class="bg-orange-50 min-h-screen rounded-md">
    <div class="container mx-auto px-4 py-12">
        <h1 class="text-4xl font-bold mb-8 text-center text-orange-800">เกี่ยวกับร้านข้าวแกง "ข้าวแกงบังเฮม"</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <section class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-semibold mb-4 text-orange-700">ประวัติร้าน</h2>
                <p class="text-gray-700 leading-relaxed">
                    "ข้าวแกงบังเฮม" ก่อตั้งขึ้นในปี พ.ศ. 2535 โดยคุณบังเฮม ผู้มีใจรักในการทำอาหาร 
                    เริ่มต้นจากรถเข็นข้างถนน จนกระทั่งได้เปิดเป็นร้านถาวรในปี พ.ศ. 2540 
                    ด้วยรสชาติอาหารที่อร่อยเหมือนฝีมือแม่ทำ ทำให้ร้านเราได้รับความนิยมมาอย่างต่อเนื่องกว่า 30 ปี
                </p>
            </section>
            
            <section class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-semibold mb-4 text-orange-700">เมนูเด็ด</h2>
                <ul class="space-y-2">
                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="text-gray-700"><span class="font-semibold">แกงเขียวหวานไก่</span> - เข้มข้นด้วยเครื่องแกงสูตรโบราณ หอมกลิ่นใบมะกรูด</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="text-gray-700"><span class="font-semibold">ผัดกะเพราหมูกรอบ</span> - หมูกรอบทอดกรอบ คลุกเคล้ากับใบกะเพราสด</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="text-gray-700"><span class="font-semibold">แกงส้มชะอมกุ้ง</span> - รสเปรี้ยวหวานกำลังดี เนื้อกุ้งสดเด้ง</span>
                    </li>
                </ul>
            </section>
        </div>
        
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            <section class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-semibold mb-4 text-orange-700">วัตถุดิบคุณภาพ</h2>
                <p class="text-gray-700 leading-relaxed">
                    เราใส่ใจในการคัดสรรวัตถุดิบคุณภาพดี สด สะอาด จากตลาดสดทุกเช้า 
                    และใช้ผักปลอดสารพิษจากเกษตรกรในชุมชน เพื่อรสชาติอาหารที่อร่อยและปลอดภัยสำหรับลูกค้าทุกท่าน
                </p>
            </section>
            
            <section class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-semibold mb-4 text-orange-700">พนักงานของเรา</h2>
                <p class="text-gray-700 leading-relaxed">
                    ทีมงานของเราประกอบด้วยเชฟมืออาชีพที่ผ่านการอบรมจากบังเฮมโดยตรง 
                    และพนักงานที่พร้อมให้บริการด้วยรอยยิ้มและความเป็นกันเอง เสมือนคุณมารับประทานอาหารที่บ้าน
                </p>
            </section>
        </div>
        
        <div class="mt-8 bg-white rounded-lg shadow-md p-6 h-96">
            <h2 class="text-2xl font-semibold mb-4 text-orange-700">ติดต่อเรา</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <p class="text-gray-700 leading-relaxed">
                        <strong>ที่อยู่:</strong> 123 ถนนอร่อยดี ตำบลสุขสันต์ อำเภอเมือง จังหวัดอิ่มท้อง 12345<br>
                        <strong>เวลาเปิด-ปิด:</strong> ทุกวัน 06.00 - 14.00 น.<br>
                        <strong>โทรศัพท์:</strong> 02-345-6789<br>
                        <strong>Line Official:</strong> @kruakhunyamae
                    </p>
                </div>
                <div class="w-full md:h-auto rounded-md">
                    <!-- เพิ่ม Google Maps iframe ตรงนี้ -->
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3875.5438561037287!2d100.56324715!3d13.7455676!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTPCsDQ0JzQ0LjAiTiAxMDDCsDMzJzQ3LjciRQ!5e0!3m2!1sen!2sth!4v1635739358503!5m2!1sen!2sth" 
                        width="100%" 
                        height="180%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection