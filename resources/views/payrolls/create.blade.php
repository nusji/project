<!-- resources/views/payrolls/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>เพิ่มรายการเงินเดือนใหม่</h1>
    
    <form action="{{ route('payrolls.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="employee_id">พนักงาน</label>
            <select name="employee_id" id="employee_id" class="form-control" required>
                @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}">{{ $employee->first_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="bonus">โบนัส</label>
            <input type="number" name="bonus" id="bonus" class="form-control" step="0.01" min="0" value="0">
        </div>
        <div class="form-group">
            <label for="deductions">หักเงิน</label>
            <input type="number" name="deductions" id="deductions" class="form-control" step="0.01" min="0" value="0">
        </div>
        <div class="form-group">
            <label for="net_salary">เงินเดือนสุทธิ</label>
            <input type="number" name="net_salary" id="net_salary" class="form-control" step="0.01" min="0" required>
        </div>
        <div class="form-group">
            <label for="payment_date">วันที่จ่าย</label>
            <input type="date" name="payment_date" id="payment_date" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">บันทึก</button>
    </form>
</div>
@endsection
