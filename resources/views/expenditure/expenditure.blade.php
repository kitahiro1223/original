@extends('layouts.in')
@section('content')
<div class="contents">
<div class="container">
<main class="by-category-main">
    <div class="by-category-title">
        <div class="back">
            <a href="home">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
        </div>        
        <div class="icon-area color1"><!-- アイコンを収める -->
            <i class="icon icon-left fa-solid fa-wallet"></i>
            <i class="icon icon-right icon-half fa-solid fa-right-long"></i>
        </div>
        <h2>支出</h2>
    </div>
    <div class="box total-box">
        <h3>総計</h3>
        <p>{{ "¥".number_format($expenditures_sum) }}</p>
    </div>
    <div class="by-category-inner">
        <div class="box-list">
            @if(!empty($ex_main_cates))
            <?php foreach ($ex_main_cates as $ex_main_cates): ?>
                <div>
                    <form action="ex-sub-category" method="POST" class="box box-link by-category-box main_category_form">
                        @csrf
                        <div class="box-icon-area box-3-items"><!-- アイコンを動かす -->
                            <div class="icon-area"><!-- アイコンを収める -->
                                <i class="box-icon {{ $ex_main_cates['icon']}} "></i>
                                <input type="hidden" name="icon_id" value="{{ $ex_main_cates['icon_id'] }}">
                                <input type="hidden" name="icon" value="{{ $ex_main_cates['icon'] }}">
                            </div>
                        </div>
                        <h3 class="box-name">{{ $ex_main_cates['name'] }}</h3>
                        <p class="box-amount">{{ "¥".number_format($ex_main_cates['sum']) }}</p>
                        <input type="hidden" name="main_category_id" value="{{ $ex_main_cates['id'] }}">
                        <input type="hidden" name="main_category_name" value="{{ $ex_main_cates['name'] }}">
                        <input type="submit" class="main_category_btn" value="">
                    </form>
                </div>
            <?php endforeach; ?>
            @endif
            <a href="category-kinds">
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