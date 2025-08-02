@extends('layouts.guest')

@section('content')
    <div class="container mx-auto px-4 py-12">
        <h1 class="text-5xl font-bold text-center text-orange-800 mb-12 tracking-tight">รีวิวอาหารประจำวัน</h1>

        <!-- ปุ่มรีวิวเมนู -->
        <div class="text-center mb-12">
            <a href="{{ route('feedbacks.create') }}" class="inline-block bg-gradient-to-r from-orange-800 to-orange-900 border-4 hover:from-blue-700 hover:to-blue-800 text-white text-lg px-8 py-4 rounded-full shadow-lg transition duration-300 ease-in-out transform hover:scale-110">
                กดเพื่อรีวิวเมนูเลย!!
            </a>
        </div>

        <!-- กริดเมนู -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            @foreach ($averageRatings as $menuData)
                <div class="bg-white rounded-2xl shadow-xl hover:shadow-2xl overflow-hidden transform hover:scale-105 transition duration-300 ease-in-out">
                    <div class="bg-white p-6">
                        <h2 class="text-2xl font-semibold text-black">{{ $menuData['menu']->menu_name }}</h2>
                        <p class="text-sm text-gray-700 mt-2">{{ $menuData['menu']->menu_description }}</p>
                        <img src="{{ asset('storage/' . $menuData['menu']->menu_image) }}" alt="{{ $menuData['menu']->menu_name }}" class="w-40 h-40 rounded-lg mt-6 mx-auto object-cover">
                    </div>

                    <div class="p-6">
                        <div class="flex align-items-center">
                            <h3 class="text-lg font-semibold text-gray-800 mr-4">คะแนนเฉลี่ย </h3>
                            <div class="star-rating flex" data-rating="{{ number_format($menuData['average_rating'], 1) }}"></div>
                            <span class="ml-2 text-lg font-bold text-gray-800">{{ number_format($menuData['average_rating'], 1) }}</span>
                        </div>
                        

                        <h3 class="text-lg font-semibold text-gray-800 mb-3">รีวิวจากลูกค้า</h3>
                        <div class="space-y-4 max-h-64 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100">
                            @php
                                $feedbacks = $menuData['feedbacks']->sortByDesc('created_at');
                            @endphp
                            @foreach ($feedbacks as $feedback)
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 shadow-sm">
                                    <div class="flex justify-between items-center mb-2">
                                        <div class="star-rating flex" data-rating="{{ $feedback->rating }}"></div>
                                        <span class="text-sm text-gray-500">{{ $feedback->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <p class="text-gray-700">{{ $feedback->comment ?: 'ไม่มีความคิดเห็น' }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const starRatings = document.querySelectorAll('.star-rating');
            starRatings.forEach(function(ratingElement) {
                const rating = parseFloat(ratingElement.dataset.rating);
                let starsHtml = '';
                for (let i = 1; i <= 5; i++) {
                    if (i <= rating) {
                        starsHtml +=
                            '<svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>';
                    } else {
                        starsHtml +=
                            '<svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>';
                    }
                }
                ratingElement.innerHTML = starsHtml;
            });
        });
    </script>
@endsection
