<!-- resources/views/payrolls/create.blade.php -->
@extends('layouts.app')

@section('content')
<h1>Create Payroll</h1>

<form action="{{ route('payrolls.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="payment_date">Payment Date</label>
        <input type="date" name="payment_date" id="payment_date" class="form-control" required>
    </div>

    @foreach ($employees as $employee)
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">{{ $employee->name }}</h5>
            <input type="hidden" name="employee_ids[]" value="{{ $employee->id }}">
            
            <div class="form-group">
                <label>Base Salary: {{ $employee->base_salary }}</label>
            </div>
            
            <div class="form-group">
                <label for="bonus_{{ $employee->id }}">Bonus</label>
                <input type="number" name="bonuses[]" id="bonus_{{ $employee->id }}" class="form-control" step="0.01" min="0" value="0">
            </div>
            
            <div class="form-group">
                <label for="deduction_{{ $employee->id }}">Deductions</label>
                <input type="number" name="deductions[]" id="deduction_{{ $employee->id }}" class="form-control" step="0.01" min="0" value="0">
            </div>
        </div>
    </div>
    @endforeach

    <button type="submit" class="btn btn-primary">Create Payrolls</button>
</form>
@endsection
