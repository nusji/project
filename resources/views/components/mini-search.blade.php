<button {{ $attributes->merge(['type' => 'button', 'class' => 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md inline-flex items-center']) }}>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
    </svg>
    {{ $slot }}
</button>