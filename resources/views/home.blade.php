@extends('layouts.in')
@section('content')
<div class="contents content-center">
<div class="container">
<main class="home-main">
    <div class="home-box-area">
        <div class="row home-box-row">
            @if($user_role == 0)
            <a href="possession">
                <div class="box box-link home-box">
                    <div class="box-icon-area box-2-items"><!-- アイコンを動かす -->
                        <div class="icon-area color3"><!-- アイコンを収める -->
                            <i class="icon box-icon fa-solid fa-piggy-bank"></i>
                        </div>
                    </div>
                    <h3 class="box-name box-2-items">所持金</h3>
                </div>
            </a>
            @else
            <a href="user-list">
                <div class="box box-link home-box">
                    <div class="box-icon-area box-2-items"><!-- アイコンを動かす -->
                        <div class="icon-area color3"><!-- アイコンを収める -->
                            <i class="box-icon fa-solid fa-user"></i>
                        </div>
                    </div>
                    <h3 class="box-name box-2-items">ユーザー</h3>
                </div>
            </a>
            @endif
            <a href="income">
                <div class="box box-link home-box">
                    <div class="box-icon-area box-2-items"><!-- アイコンを動かす -->
                        <div class="icon-area color5"><!-- アイコンを収める -->
                            <i class="icon box-icon icon-left icon-half fa-solid fa-right-long"></i>
                            <i class="icon box-icon icon-right fa-solid fa-wallet"></i>
                        </div>
                    </div>
                    <h3 class="box-name box-2-items">収入</h3>
                </div>
            </a>
        </div>
        <div class="row home-box-row">
            <a href="expenditure">
                <div class="box box-link home-box">
                    <div class="box-icon-area box-2-items"><!-- アイコンを動かす -->
                        <div class="icon-area color1"><!-- アイコンを収める -->
                            <i class="icon box-icon icon-left fa-solid fa-wallet"></i>
                            <i class="icon box-icon icon-right icon-half fa-solid fa-right-long"></i>
                        </div>
                    </div>
                    <h3 class="box-name box-2-items">支出</h3>
                </div>
            </a>
            <a href="setting">
                <div class="box box-link home-box">
                    <div class="box-icon-area box-2-items"><!-- アイコンを動かす -->
                        <div class="icon-area color2"><!-- アイコンを収める -->
                            <i class="icon box-icon fa-solid fa-gear"></i>
                        </div>
                    </div>
                    <h3 class="box-name box-2-items">設定</h3>
                </div>
            </a>
        </div>
    </div><!-- home-box-area -->
</main>
@endsection