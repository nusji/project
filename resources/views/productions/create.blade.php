@extends('layouts.app')

@section('content')
    <h1>สร้างรายการผลิต</h1>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('productions.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="production_date">วันที่ผลิต</label>
            <input type="date" name="production_date" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="production_detail">รายละเอียดการผลิต</label>
            <input type="text" name="production_detail" class="form-control">
        </div>

        <h3>เลือกเมนู</h3>
        <div id="menu-container">
            <div class="menu-item">
                <select name="menus[0][menu_id]" class="form-control" required>
                    @foreach($menus as $menu)
                        <option value="{{ $menu->id }}">{{ $menu->menu_name }}</option>
                    @endforeach
                </select>
                <input type="number" name="menus[0][quantity]" class="form-control" placeholder="จำนวน" required>
            </div>
        </div>

        <button type="button" id="add-menu-btn" class="btn btn-secondary">เพิ่มเมนู</button>

        <button type="submit" class="btn btn-primary">บันทึก</button>
    </form>

    <script>
        document.getElementById('add-menu-btn').addEventListener('click', function () {
            const container = document.getElementById('menu-container');
            const index = container.children.length;
            const newMenu = `
                <div class="menu-item">
                    <select name="menus[${index}][menu_id]" class="form-control" required>
                        @foreach($menus as $menu)
                            <option value="{{ $menu->id }}">{{ $menu->menu_name }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="menus[${index}][quantity]" class="form-control" placeholder="จำนวน" required>
                </div>`;
            container.insertAdjacentHTML('beforeend', newMenu);
        });
    </script>
@endsection
