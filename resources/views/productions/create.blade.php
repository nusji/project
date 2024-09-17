<!-- resources/views/productions/create.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>เพิ่มการผลิต</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('productions.store') }}" method="POST">
        @csrf

        <!-- วันที่ผลิต -->
        <div class="form-group">
            <label for="production_date">วันที่ผลิต</label>
            <input type="date" name="production_date" id="production_date" class="form-control" required>
        </div>

        <!-- รายละเอียดการผลิต -->
        <div class="form-group">
            <label for="production_detail">รายละเอียดการผลิต</label>
            <textarea name="production_detail" id="production_detail" class="form-control"></textarea>
        </div>

        <!-- เลือกเมนูและจำนวนที่ผลิต -->
        <div class="form-group">
            <label for="menu_list">รายการเมนูที่ผลิต</label>

            <div id="menu_list_container">
                <div class="menu-item">
                    <select name="menu_list[0][menu_id]" class="form-control" required>
                        <option value="">เลือกเมนู</option>
                        @foreach($menus as $menu)
                            <option value="{{ $menu->id }}">{{ $menu->menu_name }}</option>
                        @endforeach
                    </select>

                    <input type="number" name="menu_list[0][quantity]" class="form-control" placeholder="จำนวน" required min="1">
                </div>
            </div>

            <button type="button" id="add_menu" class="btn btn-secondary mt-2">เพิ่มเมนู</button>
        </div>

        <button type="submit" class="btn btn-primary">บันทึกการผลิต</button>
    </form>
</div>

<script>
    let menuIndex = 1;

    document.getElementById('add_menu').addEventListener('click', function () {
        const container = document.getElementById('menu_list_container');
        const newMenuItem = `
            <div class="menu-item mt-2">
                <select name="menu_list[${menuIndex}][menu_id]" class="form-control" required>
                    <option value="">เลือกเมนู</option>
                    @foreach($menus as $menu)
                        <option value="{{ $menu->id }}">{{ $menu->menu_name }}</option>
                    @endforeach
                </select>

                <input type="number" name="menu_list[${menuIndex}][quantity]" class="form-control" placeholder="จำนวน" required min="1">
            </div>
        `;
        container.insertAdjacentHTML('beforeend', newMenuItem);
        menuIndex++;
    });
</script>
@endsection
