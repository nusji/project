<div id="manageSoldOutModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" 
    aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">จัดการเมนูขายหมด</h3>
            <div class="mt-2 px-7 py-3">
                <form id="manageSoldOutForm">
                    @foreach ($menus as $menu)
                        <div class="flex items-center justify-between my-2">
                            <label for="menu-{{ $menu->id }}">{{ $menu->menu_name }}</label>
                            <input type="checkbox" id="menu-{{ $menu->id }}" name="sold_out_menus[{{ $menu->id }}]" value="1" {{ $menu->is_sold_out ? 'checked' : '' }}>
                        </div>
                    @endforeach
                </form>
            </div>
            <div class="items-center px-4 py-3">
                <button id="saveSoldOut" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700">บันทึก</button>
                <button id="cancelSoldOut" class="mt-2 px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-700">ยกเลิก</button>
            </div>
        </div>
    </div>
</div>
