@extends('layouts.in')
@section('content')
<div class="contents content-center ">
<div class="container">
<main class="by-category-main">
    <div class="by-category-title">
        <div class="icon-area color2"><!-- アイコンを収める -->
            <i class="icon fa-solid fa-gear"></i>
        </div>
        <h2>カテゴリーの編集</h2>
    </div>
    <p class="category-kinds-summary">編集するカテゴリーの機能を選択してください</p>
    <form action="categories" method="post">
        <div class="by-category-inner">
            <div class="box-list">
                <div>
                    <form action="categories" method="POST" class="box box-link by-category-box main_category_form">
                        @csrf
                        <div class="box box-link by-category-box" style="position: relative;">
                            <div class="box-icon-area box-3-items"><!-- アイコンを動かす -->
                                <div class="icon-area"><!-- アイコンを収める -->
                                    <i class="box-icon box-icon fa-solid fa-piggy-bank color3"></i>
                                </div>
                            </div>
                            <h3 class="box-name box-2-items">所持金</h3>
                            <input type="hidden" name="kind_id" value="1">
                            <input type="hidden" name="kind_name" value="所持金">
                            <input type="submit" name="kind_submit" class="main_category_btn" value="">
                        </div>
                    </form>
                </div>
                <div>
                    <form action="categories" method="POST" class="box box-link by-category-box main_category_form">
                        @csrf
                        <div class="box-icon-area box-2-items">
                            <div class="icon-area color4">
                                <i class="icon box-icon icon-left icon-half fa-solid fa-right-long"></i>
                                <i class="icon box-icon icon-right fa-solid fa-wallet"></i>
                            </div>
                        </div>
                        <h3 class="box-name box-2-items">収入</h3>
                        <input type="hidden" name="kind_id" value="2">
                        <input type="hidden" name="kind_name" value="収入">
                        <input type="submit" name="kind_submit" class="main_category_btn" value="">
                    </form>
                </div>
                <div>
                    <form action="categories" method="POST" class="box box-link by-category-box main_category_form">
                        @csrf
                        <div class="box-icon-area box-2-items">
                            <div class="icon-area color1">
                                <i class="icon box-icon icon-left fa-solid fa-wallet"></i>
                                <i class="icon box-icon icon-right icon-half fa-solid fa-right-long"></i>
                            </div>
                        </div>
                        <h3 class="box-name box-2-items">支出</h3>
                        <input type="hidden" name="kind_id" value="3">
                        <input type="hidden" name="kind_name" value="支出">
                        <input type="submit" name="kind_submit" class="main_category_btn" value="">
                    </form>
                </div>
            </div><!-- .box-list -->
        </div><!-- .by-category-inner -->
    </form>
</main>
@endsection