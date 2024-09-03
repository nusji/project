@extends('layouts.app')

@section('content')
<div class="container">
    <h1>แก้ไขคำสั่งซื้อ</h1>
    
    <form action="{{ route('orders.update', $order->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="order_date">วันที่คำสั่งซื้อ</label>
            <input type="date" name="order_date" id="order_date" class="form-control" value="{{ old('order_date', $order->order_date) }}" required>
            @error('order_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="order_detail">รายละเอียดคำสั่งซื้อ</label>
            <textarea name="order_detail" id="order_detail" class="form-control" rows="3" required>{{ old('order_detail', $order->order_detail) }}</textarea>
            @error('order_detail')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="order_receipt">ใบเสร็จ</label>
            <input type="file" name="order_receipt" id="order_receipt" class="form-control-file">
            @if($order->order_receipt)
                <img src="{{ asset('storage/' . $order->order_receipt) }}" alt="Current Receipt" class="mt-2" style="max-width: 200px;">
            @endif
            @error('order_receipt')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <h3>รายการส่วนผสม</h3>
        <div id="ingredients-container">
            @foreach($order->orderDetails as $orderDetail)
                <div class="form-group ingredient-group">
                    <input type="hidden" name="ingredients[{{ $loop->index }}][id]" value="{{ $orderDetail->ingredient_id }}">
                    <label for="ingredient_{{ $loop->index }}">ส่วนผสม</label>
                    <select name="ingredients[{{ $loop->index }}][id]" id="ingredient_{{ $loop->index }}" class="form-control">
                        @foreach($ingredients as $ingredient)
                            <option value="{{ $ingredient->id }}" {{ $orderDetail->ingredient_id == $ingredient->id ? 'selected' : '' }}>
                                {{ $ingredient->ingredient_name }}
                            </option>
                        @endforeach
                    </select>

                    <label for="quantity_{{ $loop->index }}">จำนวน</label>
                    <input type="number" name="ingredients[{{ $loop->index }}][quantity]" id="quantity_{{ $loop->index }}" class="form-control" value="{{ old('ingredients.' . $loop->index . '.quantity', $orderDetail->quantity) }}" min="0" required>

                    <label for="price_{{ $loop->index }}">ราคา</label>
                    <input type="number" name="ingredients[{{ $loop->index }}][price]" id="price_{{ $loop->index }}" class="form-control" value="{{ old('ingredients.' . $loop->index . '.price', $orderDetail->price) }}" min="0" required>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">บันทึก</button>
    </form>
</div>
@endsection
