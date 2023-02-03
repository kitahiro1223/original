@extends('layouts.in')
@section('content')
<div class="contents content-center">
<div class="container">
<main class="by-category-main">
    <div class="by-category-title">
        <div class="icon-area color2"><!-- アイコンを収める -->
            <i class="icon fa-solid fa-gear"></i>
        </div>
        <h2>設定</h2>
    </div>
    <div class="by-category-inner">
        <div class="box-list">
            <a href="account">
                <div class="box box-link by-category-box">
                    <div class="box-icon-area box-2-items"><!-- アイコンを動かす -->
                        <div class="icon-area color3"><!-- アイコンを収める -->
                            <i class="icon box-icon fa-solid fa-user"></i>
                        </div>
                    </div>
                    <h3 class="box-name box-2-items">アカウント</h3>
                </div>
            </a>
            <a href="category-kinds">
                <div class="box box-link by-category-box">
                    <div class="box-icon-area box-2-items"><!-- アイコンを動かす -->
                        <div class="icon-area color4"><!-- アイコンを収める -->
                            <i class="icon box-icon fa-solid fa-plus"></i>
                        </div>
                    </div>
                    <h3 class="box-name box-2-items default">カテゴリーの追加</h3>
                    <h3 class="box-name box-2-items short">カテゴリーの<br>追加</h3>
                </div>
            </a>
            <a class="dropdown-item" href="logout_check" id="logout-btn">
                <div class="box box-link by-category-box">
                    <div class="box-icon-area box-2-items">
                        <div class="icon-area color1">
                            <i class="icon box-icon fa-solid fa-arrow-right-from-bracket"></i>
                        </div>
                    </div>
                    <div class="box-name box-2-items">{{ __('ログアウト') }}</div>
                </div>
            </a>
        </div><!-- .box-list -->
    </div><!-- .by-category-inner -->
</main>
@endsection