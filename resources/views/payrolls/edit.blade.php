<!-- resources/views/payrolls/edit.blade.php -->
@extends('layouts.app')

@section('content')
<h1>Edit Payroll</h1>

<form action="{{ route('payrolls.update', $payroll) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="form-group">
        <label>Employee: {{ $payroll->employee->name }}</label>
    </div>
    
    <div class="form-group">
        <label>Base Salary: {{ $payroll->employee->base_salary }}</label>
    </div>
    
    <div class="form-group">
        <label for="bonus">Bonus</label>
        <input type="number" name="bonus" id="bonus" class="form-control" step="0.01" min="0" value="{{ $payroll->bonus }}" required>
    </div>
    
    <div class="form-group">
        <label for="deductions">Deductions</label>
        <input type="number" name="deductions" id="deductions" class="form-control" step="0.01" min="0" value="{{ $payroll->deductions }}" required>
    </div>
    
    <div class="form-group">
        <label for="payment_date">Payment Date</label>
        <input type="date" name="payment_date" id="payment_date" class="form-control" value="{{ $payroll->payment_date }}" required>
    </div>

    <button type="submit" class="btn btn-primary">Update Payroll</button>
</form>
@endsection