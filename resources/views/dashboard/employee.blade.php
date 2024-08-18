@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <!-- แสดงชื่อของ user ที่เข้าสู่ระบบ -->
                        <h2>สวัสดี คุณพนักงาน คุณ {{ Auth::user()->first_name }}</h2>
                        <p>ชื่อ : {{ Auth::user()->first_name }}</p>
                        <p>นามสกุล : {{ Auth::user()->last_name }}</p>
                        <p>เบอร์โทรศัพท์ : {{ Auth::user()->phone_number }}</p>
                        <p>ชื่อผู้ใช้ : {{ Auth::user()->username }}</p>
                        <p>สถานะการจ้างงาน : {{ Auth::user()->employment_status }}</p>
                        <p>วันที่เริ่มงาน : {{ Auth::user()->start_date }}</p>
                        <p>เลขบัตรประจำตัวประชาชน : {{ Auth::user()->id_card_number }}</p>
                        <p>โทรศัพท์ : {{ Auth::user()->phone_number }}</p> <!-- แก้ไขจาก 'tel' เป็น 'phone_number' -->
                        <p>บทบาทพนักงาน : {{ Auth::user()->role }}</p> <!-- แก้ไขจาก 'employee_role' เป็น 'role' -->
                        <p>ที่อยู่ : {{ Auth::user()->address }}</p>
                        <p>วันเดือนปีเกิด : {{ Auth::user()->date_of_birth }}</p>
                        <p>รูปถ่าย : {{ Auth::user()->profile_picture }}</p>
                        <p>ประวัติการทำงาน : {{ Auth::user()->previous_experience }}</p>
                        <p>บัญชีธนาคาร : {{ Auth::user()->bank_account }}</p>
                        <p>เลขที่บัญชีธนาคาร: {{ Auth::user()->bank_account_number }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
