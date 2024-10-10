@extends('layouts.pos')

@section('content')
<div class="container">
    <h1>Manage Sold Out Menus for {{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('sales.updateSoldOut', $production->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="date" class="form-label">Select Date:</label>
            <input type="date" id="date" name="date" value="{{ $selectedDate }}" class="form-control" onchange="this.form.submit()">
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Menu Name</th>
                    <th>Sold Out</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productionDetails as $detail)
                <tr>
                    <td>{{ $detail->menu->menu_name }}</td>
                    <td>
                        <input type="checkbox" name="menu_ids[]" value="{{ $detail->menu_id }}" {{ $detail->is_sold_out ? 'checked' : '' }}>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">Update Sold Out Status</button>
    </form>
</div>
@endsection
