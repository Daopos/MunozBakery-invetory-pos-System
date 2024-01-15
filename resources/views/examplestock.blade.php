<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form method="POST" action="{{ route('stock') }}" enctype="multipart/form-data">
        @csrf
        <input name="item_image" type="file">
        <input name="item_name" type="text">
        <input name="stock-type" type="text">
        <input name="qty" type="text">
        <input name="price" type="text">
        <input type="submit">
    </form>
</body>
</html>
