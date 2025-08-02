<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FashionablyLate</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <h1>FashionablyLate</h1>

        @if(request()->is('register'))
        <div class="login-link">
            <a href="/login">login</a>
        </div>
        @elseif(request()->is('login'))
        <div class="login-link">
            <a href="/register">register</a>
        </div>
        @elseif(auth()->check())
        <div class="login-link">
            <form action="/logout" method="post" style="display: inline;">
                @csrf
                <button type="submit" class="header-nav__button">logout</button>
            </form>
        </div>
        @endif
    </header>

    <main class="main-content">
        @yield('content')
    </main>
</body>

</html>