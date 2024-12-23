<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <title>Rese</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/shop.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/myPage.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/detail.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/done.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin/index.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin/store.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        body {
            margin: 0;
            background-color: #EEEEEE;
        }
        header {
            width: 100%;
        }
        .content {
            padding: 20px 80px;
            width: 100%;
        }
        .nav {
            display: inline-block;
            margin-right: 10px;
        }
        .header__title {
            display: inline-block;
            color:blue;
        }
        .nav__menu {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            background-color: white;
            width: 100%;
            height: 100%;
            z-index: 1000;
            box-sizing: border-box;
            padding: 50px;
        }
        .logout-form {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .logout-form button {
            border: none;
            background-color: white;
            font-size: 30px;
            font-weight: bold;
        }
        .nav__menu-item {
            display: block;
            margin: 10px 0;
            color: blue;
            text-decoration: none;
            text-align: center;
            margin: 50px;
            font-size: 30px;
            font-weight: bold;
        }
        .nav__menu-remove a {
            color: blue;
            font-size: 30px;
            text-decoration: none;
        }
        .nav__icon {
            background-color: blue;
            color: white;
            font-size: 25px;
            border: none;
            box-shadow: 1px 1px 3px black;
            border-radius: 3px;
            margin-right: 10px;
        }
        .nav__icon i {
            padding: 7px;
        }
        /* レスポンシブ (768px以下)*/
        @media screen and (max-width: 768px) {
        .content {
            padding: 10px 20px;
            width:100%;
        }
        .nav__icon {
            font-size: 13px;
        }
        .nav__icon i {
            padding: 4px 1px;
        }
        .header__title {
            font-size: 20px;
        }
        }
    </style>
</head>
<body>

    <header>
        <div class="content">
            <nav class="nav">
                <button class="nav__icon" onclick="toggleMenu()">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <!-- ログイン済みのユーザー向けの表示 -->
                <div class="nav__menu" id="navMenu">
                    <div class="nav__menu-remove">
                        <a  href="javascript:void(0)" class="fa-regular fa-circle-xmark" onclick="closeMenu()"></a>
                    </div>
                    @if (Auth::check())
                    <a class="nav__menu-item" href="/">Home</a>
                    <form class="logout-form" action="{{ route('logout') }}" method="post">
                        @csrf
                        <button type="submit" class="nav__menu-item">Logout</button>
                    </form>
                    <a class="nav__menu-item" href="/myPage">Mypage</a>
                    @else
                <!-- 会員登録していない人向けの表示 -->
                    <a class="nav__menu-item" href="/register">Registration</a>
                    <a class="nav__menu-item" href="/login">Login</a>
                </div>
                    @endif
            </nav>
            <h1 class="header__title">Rese</h1>
        </div>
    </header>

        <main>
            <div class="content">
                @yield('content')
            </div>
        </main>
    </div>
    <script>
        function toggleMenu() {
            const navMenu = document.getElementById("navMenu");
            if (navMenu.style.display == "block") {
                navMenu.style.display = "none"; //メニュー非表示
            } else {
                navMenu.style.display = "block"; //メニュー表示
            }
        }

        function closeMenu() {
            const navMenu = document.getElementById("navMenu");
            navMenu.style.display = "none"; // メニューを閉じる
        }
    </script>
</body>
</html>