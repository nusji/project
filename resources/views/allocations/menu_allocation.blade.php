<!-- resources/views/menu_allocation.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Allocation Results</title>
</head>
<body>
    <h1>Menu Allocation Results for the Week</h1>

    @foreach($menuAllocation as $day => $menus)
        <h2>Day {{ $day }}</h2>
        <ul>
            @foreach($menus as $menuId)
                <li>{{ \App\Models\Menu::find($menuId)->menu_name }}</li>
            @endforeach
        </ul>
    @endforeach
</body>
</html>
