<!-- resources/views/menu_form.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Allocation Form</title>
</head>
<body>
    <h1>Menu Allocation Form</h1>

    <form action="/allocate-menu" method="POST">
        @csrf
        <!-- ระบุจำนวนเมนูที่ต้องการต่อวัน -->
        <label for="total_menus">Total Menus per Day:</label>
        <input type="number" id="total_menus" name="total_menus" required><br><br>

        <!-- ระบุจำนวนเมนูขายดีที่ต้องตรึงไว้ -->
        <label for="best_seller_count">Number of Best Selling Menus to Fix:</label>
        <input type="number" id="best_seller_count" name="best_seller_count" required><br><br>

        <!-- เลือกประเภทเมนูที่ต้องการ -->
        <label for="menu_types">Select Menu Types:</label><br>
        @foreach ($menuTypes as $menuType)
            <input type="checkbox" id="menu_type_{{ $menuType->id }}" name="menu_types[]" value="{{ $menuType->id }}">
            <label for="menu_type_{{ $menuType->id }}">{{ $menuType->type_name }}</label><br>
        @endforeach

        <br>
        <button type="submit">Generate Menu</button>
    </form>
</body>
</html>
