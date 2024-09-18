<div class="container">
    <h1>เพิ่มการผลิต</h1>

    <form wire:submit.prevent="saveProduction">
        <!-- วันที่ผลิต -->
        <div class="form-group">
            <label for="production_date">วันที่ผลิต</label>
            <input type="date" wire:model="productionDate" id="production_date" class="form-control" required>
        </div>

        <!-- รายละเอียดการผลิต -->
        <div class="form-group">
            <label for="production_detail">รายละเอียดการผลิต</label>
            <textarea wire:model="productionDetail" id="production_detail" class="form-control"></textarea>
        </div>

        <!-- ค้นหาเมนู -->
        <div class="form-group">
            <label for="search_menu">ค้นหาเมนู</label>
            <input type="text" wire:model="search" id="search_menu" class="form-control" placeholder="พิมพ์เพื่อค้นหาเมนู...">
            
            @if ($menus->isNotEmpty())
                <ul class="list-group mt-2">
                    @foreach ($menus as $menu)
                        <li class="list-group-item" wire:click="addMenu({{ $menu->id }})">{{ $menu->menu_name }}</li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted mt-2">ไม่พบเมนูที่ตรงกับคำค้นหา</p>
            @endif
        </div>
        

        <!-- แสดงรายการเมนูที่เลือก -->
        <div class="form-group">
            <label for="menu_list">รายการเมนูที่ผลิต</label>
            <div>
                @foreach ($menuList as $index => $menu)
                    <div class="menu-item mt-2">
                        <span>{{ $menu['menu_name'] }}</span>
                        <input type="number" wire:model="menuList.{{ $index }}.quantity" class="form-control" placeholder="จำนวน" min="1" required>
                        <button type="button" wire:click="removeMenu({{ $index }})" class="btn btn-danger">ลบ</button>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary">บันทึกการผลิต</button>
    </form>
</div>
