@extends('layouts.in')
@section('content')
<div class="contents">
<div class="container">
<main class="by-category-main">
    <div class="by-category-title">
        <div class="icon-area color1"><!-- アイコンを収める -->
            <i class="icon icon-left fa-solid fa-wallet"></i>
            <i class="icon icon-right icon-half fa-solid fa-right-long"></i>
        </div>
        <h2>支出</h2>
    </div>
    <div class="box total-box">
        <h3>総計</h3>
        <p>{{ "¥".number_format($sum) }}</p>
    </div>
    <div class="by-category-inner">
        <div class="box-list">
            <div>
                <form action="ex-sub-category" method="POST" class="box box-link by-category-box main_category_form">
                    @csrf
                    <div class="box-icon-area box-3-items"><!-- アイコンを動かす -->
                        <div class="icon-area"><!-- アイコンを収める -->
                            <i class="box-icon fa-solid fa-utensils color3"></i>
                            <input type="hidden" name="icon" value="fa-solid fa-utensils color3">
                        </div>
                    </div>
                    <h3 class="box-name">食事</h3>
                    <p class="box-amount">{{ "¥".number_format($common_sum['food']) }}</p>
                    <input type="hidden" name="main_category_id" value="7">
                    <input type="hidden" name="main_category_name" value="食事">
                    <input type="submit" class="main_category_btn" value="">
                </form>
            </div>
            <div>
                <form action="ex-sub-category" method="POST" class="box box-link by-category-box main_category_form">
                    @csrf
                    <div class="box-icon-area box-3-items"><!-- アイコンを動かす -->
                        <div class="icon-area"><!-- アイコンを収める -->
                            <i class="box-icon fa-solid fa-cart-shopping color5"></i>
                            <input type="hidden" name="icon" value="fa-solid fa-cart-shopping color5">
                        </div>
                    </div>
                    <h3 class="box-name">日用品</h3>
                    <p class="box-amount">{{ "¥".number_format($common_sum['daily']) }}</p>
                    <input type="hidden" name="main_category_id" value="8">
                    <input type="hidden" name="main_category_name" value="日用品">
                    <input type="submit" class="main_category_btn" value="">
                </form>
            </div>
            <div>
                <form action="ex-sub-category" method="POST" class="box box-link by-category-box main_category_form">
                    @csrf
                    <div class="box-icon-area box-3-items"><!-- アイコンを動かす -->
                        <div class="icon-area"><!-- アイコンを収める -->
                            <i class="box-icon fa-solid fa-house color1"></i>
                            <input type="hidden" name="icon" value="fa-solid fa-house color1">
                        </div>
                    </div>
                    <h3 class="box-name">住まい</h3>
                    <p class="box-amount">{{ "¥".number_format($common_sum['house']) }}</p>
                    <input type="hidden" name="main_category_id" value="9">
                    <input type="hidden" name="main_category_name" value="住まい">
                    <input type="submit" class="main_category_btn" value="">
                </form>
            </div>
            <?php foreach ($expenditures as $expenditure): ?>
                <div>
                    <form action="ex-sub-category" method="POST" class="box box-link by-category-box main_category_form">
                        @csrf
                        <div class="box-icon-area box-3-items"><!-- アイコンを動かす -->
                            <div class="icon-area"><!-- アイコンを収める -->
                                <i class="box-icon {{ $expenditure->icon->code }}"></i>
                                <input type="hidden" name="icon" value="{{ $expenditure->icon->code }}">
                            </div>
                        </div>
                        <h3 class="box-name">{{ $expenditure->main_category->name }}</h3>
                        <p class="box-amount">{{ "¥".number_format($expenditure->sub_category_sum) }}</p>
                        <input type="hidden" name="main_category_id" value="{{ $expenditure->main_category_id }}">
                        <input type="hidden" name="main_category_name" value="{{ $expenditure->main_category_id->name }}">
                        <input type="submit" class="main_category_btn" value="">
                    </form>
                </div>
            <?php endforeach; ?>
            <a href="">
                <div class="box box-link by-category-box">
                    <div class="box-icon-area box-2-items"><!-- アイコンを動かす -->
                        <div class="icon-area color2"><!-- アイコンを収める -->
                            <i class="icon box-icon fa-solid fa-plus"></i>
                        </div>
                    </div>
                    <h3 class="box-name box-2-items">追加</h3>
                </div>
            </a>
        </div><!-- .box-list -->
    </div><!-- .by-category-inner -->
</main>
@endsection