<!-- resources/views/livewire/search-menu.blade.php -->
<div>
    <input type="text" wire:model="search" placeholder="ค้นหาเมนู..." class="input" />
    
    <ul>
        @foreach($menus as $menu)
            <li wire:click="addMenu({{ $menu->id }})">
                {{ $menu->name }}
            </li>
        @endforeach
    </ul>

    <h3>เมนูที่เลือก</h3>
    <ul>
        @foreach($selectedMenus as $menu)
            <li>{{ $menu['name_name'] }} - {{ $menu['quantity'] }} กิโล ({{ $menu['quantity_sales'] }} ทัพพี)</li>
        @endforeach
    </ul>
</div>
