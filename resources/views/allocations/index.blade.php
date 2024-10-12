@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-4">Menu Allocations</h1>

        <a href="{{ route('allocations.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Create New Allocation</a>

        <table class="min-w-full mt-4">
            <thead>
                <tr>
                    <th>Allocation Date</th>
                    <th>Menu</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allocations as $allocation)
                    <tr>
                        <td>{{ $allocation->allocation_date }}</td>
                        <td></td>
                        <td>
                            <!-- ปุ่มลิงก์ไปยังหน้าแสดงรายละเอียด -->
                            <a href="{{ route('allocations.show', $allocation->id) }}" class="text-blue-500 hover:underline">
                                View Details
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
