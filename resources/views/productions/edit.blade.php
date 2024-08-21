@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Production</h1>
    <form action="{{ route('productions.update', $production) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="production_date">Production Date</label>
            <input type="text" class="form-control" id="production_date" name="production_date" value="{{ $production->production_date }}" required>
        </div>
        <div class="form-group">
            <label for="comment">Comment</label>
            <textarea class="form-control" id="comment" name="comment">{{ $production->comment }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection