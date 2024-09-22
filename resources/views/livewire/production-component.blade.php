<!-- resources/views/livewire/production-component.blade.php -->
<div>
    <form wire:submit.prevent="saveProduction">
        <div>
            <label for="production_date">วันที่ผลิต:</label>
            <input type="date" wire:model="productionDate" id="production_date">
        </div>

        <div>
            <label for="production_detail">รายละเอียดการผลิต:</label>
            <textarea wire:model="productionDetail" id="production_detail"></textarea>
        </div>

        <div>
            <h3>ค้นหาเมนู</h3>
            <input type="text" wire:model="search" placeholder="ค้นหาเมนู...">
            <ul>
                @foreach($menus as $menu)
                    <li wire:click="addMenu({{ $menu->id }})">
                        {{ $menu->name }}
                    </li>
                @endforeach
            </ul>
        </div>

        <div>
            <h3>เมนูที่เลือก</h3>
            <ul>
                @foreach($menuList as $index => $menu)
                    <li>
                        {{ $menu['name'] }} 
                        <input type="number" wire:model="menuList.{{ $index }}.quantity" placeholder="จำนวนผลิต (กิโลกรัม)">
                        <span>ผลิตได้ {{ $menu['quantity_sales'] }} ทัพพี</span>
                        <button type="button" wire:click="removeMenu({{ $index }})">ลบ</button>
                    </li>
                @endforeach
            </ul>
        </div>

        <button type="submit">บันทึกการผลิต</button>
    </form>
</div>
