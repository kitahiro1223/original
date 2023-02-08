<header>
    <a class="h-logo" href="/">
        <div class="logo"><img src="/img/icon/logo_brown.png" alt="logo"></div>
        <h2 class="title">家計簿アプリ</h2>
    </a>    
    <div class="header-items">
        <nav>
            <ul>
                @if(empty($user_role) || $user_role != 1 )
                @else
                <li>
                    <a href="possession">
                        <div class="icon-area header-icon color3">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div class="h-item-name">ユーザー</div>
                    </a>
                </li>
                @endif
                <li>
                    <a href="possession">
                        <div class="icon-area header-icon color3">
                            <i class="icon fa-solid fa-piggy-bank"></i>
                        </div>
                        <div class="h-item-name">所持金</div>
                    </a>
                </li>
                <li>
                    <a href="income">
                        <div class="icon-area header-icon color5"><!-- アイコンを収める -->
                            <i class="icon icon-left icon-half fa-solid fa-right-long"></i>
                            <i class="icon icon-right fa-solid fa-wallet"></i>
                        </div>
                        <div class="h-item-name">収入</div>
                    </a>
                </li>
                <li>
                    <a href="expenditure">
                        <div class="icon-area header-icon color1"><!-- アイコンを収める -->
                            <i class="icon icon-left fa-solid fa-wallet"></i>
                            <i class="icon icon-right icon-half fa-solid fa-right-long"></i>
                        </div>
                        <div class="h-item-name">支出</div>
                    </a>
                </li>
                <li>
                    <a href="setting">
                        <div class="icon-area header-icon color2"><!-- アイコンを収める -->
                            <i class="icon fa-solid fa-gear"></i>
                        </div>
                        <div class="h-item-name">設定</div>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</header>

<div class="bottom-menu">
    <ul>
        <li>
            <a href="/" class="bm-item">
                <div class="bm-home-icon">
                    <img src="/img/icon/logo_brown.png" alt="logo">
                </div>
                <div class="bm-item-name">HOME</div>
            </a>
        </li>
        <li>
            <a href="possession" class="bm-item">
                <div class="icon-area">
                    <i class="icon fa-solid fa-piggy-bank"></i>
                </div>
                <div class="bm-item-name">所持金</div>
            </a>
        </li>
        <li>
            <a href="income" class="bm-item">
                <div class="icon-area"><!-- アイコンを収める -->
                    <i class="icon icon-left icon-half fa-solid fa-right-long"></i>
                    <i class="icon icon-right fa-solid fa-wallet"></i>
                </div>
                <div class="bm-item-name">収入</div>
            </a>
        </li>
        <li>
            <a href="expenditure" class="bm-item">
                <div class="icon-area"><!-- アイコンを収める -->
                    <i class="icon icon-left fa-solid fa-wallet"></i>
                    <i class="icon icon-right icon-half fa-solid fa-right-long"></i>
                </div>
                <div class="bm-item-name">支出</div>
            </a>
        </li>
        <li>
            <a href="setting" class="bm-item">
                <div class="icon-area"><!-- アイコンを収める -->
                    <i class="icon fa-solid fa-gear"></i>
                </div>
                <div class="bm-item-name">設定</div>
            </a>
        </li>
    </ul>
</div>

<div class="main-bg">
    <img src="/img/saving.jpg">
</div>
<div class="main-bg-filter"></div>