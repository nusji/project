<!-- resources/views/feedbacks/index.blade.php -->
@extends('layouts.guest')

@section('content')
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold text-center text-gray-800 mb-12">รีวิวอาหารประจำวัน</h1>
        <a href="{{ route('feedbacks.create') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded-md mb-8 inline-block">รีวิวเมนู</a>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($averageRatings as $menuData)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="bg-blue-600 px-6 py-4">
                        <h2 class="text-xl font-semibold text-white">{{ $menuData['menu']->menu_name }}</h2>
                        <p class="text-sm text-white">{{ $menuData['menu']->menu_description }}</p>
                        <img src="{{ asset('storage/' . $menuData['menu']->menu_image) }}"
                            alt="{{ $menuData['menu']->menu_name }}" class="w-32 h-32 rounded-md mt-4">
                    </div>

                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-lg font-medium text-gray-700">คะแนนเฉลี่ย</span>
                            <div class="flex items-center">
                                <div class="star-rating" data-rating="{{ number_format($menuData['average_rating'], 1) }}">
                                </div>
                                <span class="ml-2 text-lg font-bold text-gray-800">
                                    {{ number_format($menuData['average_rating'], 1) }}
                                </span>
                            </div>
                        </div>

                        <h3 class="text-lg font-semibold text-gray-800 mb-3">รีวิวจากลูกค้า</h3>
                        <div class="space-y-4 max-h-64 overflow-y-auto">
                            @php
                                $feedbacks = $menuData['feedbacks']->sortByDesc('created_at');
                            @endphp
                            @foreach ($feedbacks as $feedback)
                                <div class="bg-gray-50 rounded-md p-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <div class="star-rating" data-rating="{{ $feedback->rating }}"></div>
                                        <span
                                            class="text-sm text-gray-500">{{ $feedback->created_at->format('d/m/Y H:i') }}</span>
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
