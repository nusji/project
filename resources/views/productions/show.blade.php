@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Production Details</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Production ID: {{ $production->id }}</h5>
            <p class="card-text">Production Date: {{ $production->production_date }}</p>
            <p class="card-text">Comment: {{ $production->comment }}</p>
        </div>
    </div>
    <h2 class="mt-5">Production Details</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Menu ID</th>
                <th>Quantity</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($production->productionDetails as $detail)
            <tr>
                <td>{{ $detail->id }}</td>
                <td>{{ $detail->menu_id }}</td>
                <td>{{ $detail->quantity }}</td>
                <td>{{ $detail->created_at }}</td>
                <td>{{ $detail->updated_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection