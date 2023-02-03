@include ('common.function')
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="定期的に購入する物の在庫管理をLINEと連携して通知で知らせてくれる家計簿アプリです。" >
    <title>お知らせ家計簿</title>
    <link rel="icon" href="/img/icon/logo_brown.png" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/base.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/common.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/header.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/media_930.css') }}">
    <script src="https://kit.fontawesome.com/15e4490473.js" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    <!-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) -->
</head>
<body>
    <header>
        <a class="h-logo" href="{{ route('login') }}" >
            <div class="logo"><img src="/img/icon/logo_brown.png" alt="logo"></div>
            <h2 class="title" style="">お知らせ家計簿</h2>
        </a>
        <div class="header-items">
            <nav>
                <ul>
                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a href="{{ route('login') }}">
                                    <div class="icon-area header-icon color4">
                                        <i class="icon fa-solid fa-arrow-right-to-bracket"></i>
                                    </div>
                                    <div class="h-item-name">{{ __('ログイン') }}</div>
                                </a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">
                                    <div class="icon-area header-icon color2">
                                        <i class="icon fa-solid fa-user-plus"></i>
                                    </div>
                                    <div class="h-item-name">{{ __('新規登録') }}</div>
                                </a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown" id="logout-btn">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                    <div class="icon-area header-icon color2">
                                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                    </div>
                                    <div class="h-item-name">{{ __('ログアウト') }}</div>
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </nav>
            <?= links() ?>
        </div>
    </header>
    <div class="bottom-menu">
        <ul>
            <li>
                <a href="{{ route('login') }}" class="bm-item">
                    <div class="icon-area">
                        <i class="fa-solid fa-arrow-right-to-bracket"></i>
                    </div>
                    <div class="bm-item-name">{{ __('ログイン') }}</div>
                </a>
            </li>
            <li>
                <a href="{{ route('register') }}" class="bm-item">
                    <div class="icon-area">
                        <i class="icon fa-solid fa-user-plus"></i>
                    </div>
                    <div class="bm-item-name">{{ __('新規登録') }}</div>
                </a>
            </li>
        </ul>
    </div>
    <div class="main-bg">
        <img src="/img/saving.jpg">
    </div>
    <div class="main-bg-filter"></div>
    <div class="contents content-center">
    <div class="container">
        @yield('content')
    </div>
    </div>
    <script src="{{ asset('/js/script.js') }}"></script>
</body>
</html>
