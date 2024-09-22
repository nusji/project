<!-- resources/views/productions/create.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>เพิ่มการผลิตใหม่</h1>
    
    <form method="POST" action="{{ route('productions.store') }}">
        @csrf
        <input type="date" name="production_date" class="input" />
        <textarea name="production_detail" class="input" placeholder="รายละเอียดการผลิต"></textarea>
        
        @livewire('search-menu')

        <button type="submit" class="btn btn-primary">บันทึกการผลิต</button>
    </form>
@endsection
