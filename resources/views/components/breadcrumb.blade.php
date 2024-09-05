<nav class="text-gray-500 mb-4">
    @foreach($paths as $path)
        @if(!$loop->last)
            <a href="{{ $path['url'] }}" class="text-blue-500 hover:underline">{{ $path['label'] }}</a> / 
        @else
            <span>{{ $path['label'] }}</span>
        @endif
    @endforeach
</nav>
