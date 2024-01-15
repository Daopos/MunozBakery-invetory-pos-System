<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{  asset('assets/css/login.css') }}">
</head>
<body>
    <div class="center-box">
        <div class="intro-box">
            <img src="{{  asset('assets/photos/bakery-shop.png') }}" alt="">
            <h1>Muñoz Bakery</h1>
            <h1>Employee page</h1>
        </div>
        <div class="login-box">
            <form method="POST" action="{{ route('employeelogin') }}">
                @csrf
                <h1>Log in</h1>
                <div>
                    <label for="username">username</label>
                    <input type="text" name="username" placeholder="Enter your username" autocomplete="off">
                </div>
                <div>
                    <label for="password">password</label>
                    <input type="password" name="password" placeholder="Enter your password">
                </div>
                <input type="submit" value="Log in">
            </form>
        </div>
    </div>
</body>
</html>
