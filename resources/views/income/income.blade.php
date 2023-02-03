@extends('layouts.in')
@section('content')
<div class="contents">
<div class="container">
<main class="by-category-main">
    <div class="by-category-title">
        <div class="icon-area color5"><!-- アイコンを収める -->
            <i class="icon icon-left icon-half fa-solid fa-right-long"></i>
            <i class="icon icon-right fa-solid fa-wallet"></i>
        </div>        
        <h2>収入</h2>
    </div>
    <div class="box total-box">
        <h3>総計</h3>
        <p>{{ "¥".number_format($sum) }}</p>
    </div>
    <div class="by-category-inner">
        <div class="box-list">
            <div>
                <form action="in-category" method="POST" class="box box-link by-category-box main_category_form">
                    @csrf
                    <div class="box-icon-area box-3-items"><!-- アイコンを動かす -->
                        <div class="icon-area"><!-- アイコンを収める -->
                            <i class="box-icon fa-solid fa-building color3"></i>
                            <input type="hidden" name="icon" value="fa-solid fa-building color3">
                        </div>
                    </div>
                    <h3 class="box-name">給料</h3>
                    <p class="box-amount">{{ "¥".number_format($common_sum['salary']) }}</p>
                    <input type="hidden" name="main_category_id" value="4">
                    <input type="hidden" name="main_category_name" value="給料">
                    <input type="submit" class="main_category_btn" value="">
                </form>
            </div>
            <div>
                <form action="in-category" method="POST" class="box box-link by-category-box main_category_form">
                    @csrf
                    <div class="box-icon-area box-3-items"><!-- アイコンを動かす -->
                        <div class="icon-area"><!-- アイコンを収める -->
                            <i class="box-icon fa-solid fa-house-chimney-user color5"></i>
                            <input type="hidden" name="icon" value="fa-solid fa-house-chimney-user color5">
                        </div>
                    </div>
                    <h3 class="box-name">副業</h3>
                    <p class="box-amount">{{ "¥".number_format($common_sum['side_job']) }}</p>
                    <input type="hidden" name="main_category_id" value="5">
                    <input type="hidden" name="main_category_name" value="副業">
                    <input type="submit" class="main_category_btn" value="">
                </form>
            </div>
            <div>
                <form action="in-category" method="POST" class="box box-link by-category-box main_category_form">
                    @csrf
                    <div class="box-icon-area box-3-items"><!-- アイコンを動かす -->
                        <div class="icon-area"><!-- アイコンを収める -->
                            <i class="box-icon fa-solid fa-yen-sign color1"></i>
                            <input type="hidden" name="icon" value="fa-solid fa-yen-sign color1">
                        </div>
                    </div>
                    <h3 class="box-name">臨時収入</h3>
                    <p class="box-amount">{{ "¥".number_format($common_sum['extra']) }}</p>
                    <input type="hidden" name="main_category_id" value="6">
                    <input type="hidden" name="main_category_name" value="臨時収入">
                    <input type="submit" class="main_category_btn" value="">
                </form>
            </div>
            <?php foreach ($incomes as $income): ?>
            <div>
                <form action="in-category" method="POST" class="box box-link by-category-box main_category_form">
                    @csrf
                    <div class="box-icon-area box-3-items"><!-- アイコンを動かす -->
                        <div class="icon-area"><!-- アイコンを収める -->
                            <i class="box-icon {{ $income->icon->code }}"></i>
                            <input type="hidden" name="icon" value="{{ $income->icon->code }}">
                        </div>
                    </div>
                    <h3 class="box-name">{{ $income->main_category->name }}</h3>
                    <p class="box-amount">{{ "¥".number_format($income->category_sum) }}</p>
                    <input type="hidden" name="main_category_id" value="{{ $income->main_category_id }}">
                    <input type="hidden" name="main_category_name" value="{{ $income->main_category_id->name }}">
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