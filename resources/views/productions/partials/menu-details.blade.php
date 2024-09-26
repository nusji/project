<!-- resources/views/productions/partials/menu-details.blade.php -->

@foreach($menus as $menu)
    <div class="mb-4">
        <h3 class="text-lg font-semibold">{{ $menu->name }}</h3>
        <label>จำนวนผลิต (กิโลกรัม)</label>
        <input type="number" name="menus[{{ $menu->id }}][quantity]" class="input w-full" placeholder="กรอกจำนวนผลิต">
    </div>
@endforeach
