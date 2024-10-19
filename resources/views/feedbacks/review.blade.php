@extends('layouts.guest')

@section('content')
<div class="max-w-2xl mx-auto p-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        {{-- Menu Header --}}
        <div class="flex items-center gap-4 mb-6">
            @if($menu->image_url)
                <img src="{{ $menu->image_url }}" alt="{{ $menu->name }}" class="w-24 h-24 rounded-lg object-cover">
            @endif
            <div>
                <h1 class="text-2xl font-bold text-gray-900">รีวิวเมนู: {{ $menu->name }}</h1>
                <p class="text-gray-600">{{ $menu->description ?? '' }}</p>
            </div>
        </div>

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
                <div class="text-red-700">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- Review Form --}}
        <form action="{{ route('feedbacks.store') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="menu_id" value="{{ $menu->id }}">
            <input type="hidden" name="rating" id="ratingInput" value="{{ old('rating', 0) }}">

            {{-- Star Rating --}}
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">ให้คะแนน:</label>
                <div class="flex items-center gap-2" id="starRating">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button" 
                                data-rating="{{ $i }}"
                                class="star-btn text-3xl focus:outline-none transition-colors">
                            ★
                        </button>
                    @endfor
                </div>
                <p class="text-sm text-gray-500 hidden" id="ratingText">คะแนนที่ให้: <span></span> ดาว</p>
            </div>

            {{-- Comment Section --}}
            <div class="space-y-2">
                <label for="comment" class="block text-sm font-medium text-gray-700">
                    แสดงความคิดเห็น:
                </label>
                <textarea 
                    name="comment" 
                    id="comment"
                    rows="4"
                    class="mt-1 block w-full text-gray-500 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors"
                    placeholder="แชร์ความคิดเห็นของคุณ..."
                >{{ old('comment') }}</textarea>
            </div>

            {{-- Submit Button --}}
            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('feedbacks.create') }}" 
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    ยกเลิก
                </a>
                <button 
                    type="submit"
                    id="submitBtn"
                    disabled
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                    ส่งคำติชม
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const starButtons = document.querySelectorAll('.star-btn');
        const ratingInput = document.getElementById('ratingInput');
        const ratingText = document.getElementById('ratingText');
        const submitBtn = document.getElementById('submitBtn');
        let currentRating = {{ old('rating', 0) }};
    
        // Function to update stars display
        function updateStars(rating, isHover = false) {
            starButtons.forEach(btn => {
                const btnRating = parseInt(btn.dataset.rating);
                if (btnRating <= rating) {
                    btn.classList.add(isHover ? 'hover' : 'active');
                } else {
                    btn.classList.remove(isHover ? 'hover' : 'active');
                }
            });
        }
    
        // Initialize rating if there's an old value
        if (currentRating > 0) {
            updateStars(currentRating);
            ratingText.classList.remove('hidden');
            ratingText.querySelector('span').textContent = currentRating;
            submitBtn.disabled = false;
        }
    
        // Handle star hover
        starButtons.forEach(btn => {
            btn.addEventListener('mouseover', () => {
                const rating = parseInt(btn.dataset.rating);
                updateStars(rating, true);
            });
    
            btn.addEventListener('mouseout', () => {
                starButtons.forEach(btn => btn.classList.remove('hover'));
                updateStars(currentRating);
            });
    
            // Handle star click
            btn.addEventListener('click', () => {
                currentRating = parseInt(btn.dataset.rating);
                ratingInput.value = currentRating;
                updateStars(currentRating);
                ratingText.classList.remove('hidden');
                ratingText.querySelector('span').textContent = currentRating;
                submitBtn.disabled = false;
    
                // Add animation effect
                btn.classList.add('scale-110');
                setTimeout(() => btn.classList.remove('scale-110'), 200);
            });
        });
    
        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', (e) => {
            if (currentRating === 0) {
                e.preventDefault();
                alert('กรุณาให้คะแนนก่อนส่งรีวิว');
            }
        });
    });
    </script>
@endsection

@push('styles')
<style>
    .star-btn {
        color: #d1d5db;
        cursor: pointer;
    }
    .star-btn.active {
        color: #fbbf24;
    }
    .star-btn:hover,
    .star-btn.hover {
        transform: scale(1.1);
    }
</style>
@endpush
