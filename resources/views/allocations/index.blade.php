@extends('layouts.app')
@section('content')
    <h2>การจัดสรรเมนู</h2>
    <a href="{{ route('allocations.create') }}">เพิ่มการจัดสรร</a>

    <table>
        <thead>
            <tr>
                <th>วันที่จัดสรร</th>
                <th>ดำเนินการ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($allocations as $allocation)
                <tr>
                    <td>{{ $allocation->allocation_date }}</td>
                    <td>
                        <a href="{{ route('allocations.show', $allocation->id) }}">ดูรายละเอียด</a>
                        <a href="{{ route('allocations.edit', $allocation->id) }}">แก้ไข</a>
                        <form action="{{ route('allocations.destroy', $allocation->id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">ลบ</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
