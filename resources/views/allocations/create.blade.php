@extends('layouts.app')

@section('content')
<h2>เพิ่มการจัดสรรเมนู</h2>

<form action="{{ route('allocations.store') }}" method="POST">
    @csrf
    <label for="allocation_date">วันที่เริ่มต้น</label>
    <input type="date" name="allocation_date" required>

    <label for="days">จำนวนวัน (สูงสุด 7 วัน)</label>
    <input type="number" name="days" min="1" max="7" required>

    <label for="best_selling_count">จำนวนเมนูขายดีที่ต้องการคงไว้</label>
    <input type="number" name="best_selling_count" min="1" max="10" required>

    <label for="total_menus">จำนวนเมนูทั้งหมดที่ต้องการจัดสรรต่อวัน</label>
    <input type="number" name="total_menus" min="1" required>

    <button type="submit">บันทึก</button>
</form>

@endsection
