<form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="first_name">ชื่อ</label>
        <input type="text" id="first_name" name="first_name" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="last_name">นามสกุล</label>
        <input type="text" id="last_name" name="last_name" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="id_card_number">เลขบัตรประชาชน</label>
        <input type="text" id="id_card_number" name="id_card_number" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="phone_number">เบอร์โทร</label>
        <input type="text" id="phone_number" name="phone_number" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="username">ชื่อเข้าใช้</label>
        <input type="text" id="username" name="username" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="password">รหัสผ่านเข้าใช้</label>
        <input type="password" id="password" name="password" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="employment_status">ประเภทพนักงาน</label>
        <select id="employment_status" name="employment_status" class="form-control" required>
            <option value="permanent">พนักงานประจำ</option>
            <option value="temporary">พนักงานชั่วคราว</option>
        </select>
    </div>
    <div class="form-group">
        <label for="start_date">วันที่เริ่มทำงาน</label>
        <input type="date" id="start_date" name="start_date" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">เพิ่มข้อมูลพนักงานใหม่</button>
</form>
