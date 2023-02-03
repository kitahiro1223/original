@extends('layouts.in')
@section('content')
<div class="contents">
<div class="container">
<main class="by-category-main">
    <div class="by-category-title">
        <div class="icon-area color1"><!-- アイコンを収める -->
            <i class="{{ $main['icon'] }}"></i>
        </div>
        <h2>{{ $main['title'] }}</h2>
    </div>
    <div class="box total-box">
        <h3>総計</h3>
        <p>{{ "¥".number_format($amounts) }}</p>
    </div>
    <div class="by-category-inner">
        <div class="box-list">
            <?php foreach ($common as $common_one): ?>
                <div>
                    <form action="ex-by-category" method="POST" class="box box-link by-category-box main_category_form">
                        @csrf
                        <div class="box-icon-area box-3-items"><!-- アイコンを動かす -->
                            <div class="icon-area"><!-- アイコンを収める -->
                                <i class="box-icon {{ $main['icon'] }}"></i>    
                            </div>
                        </div>
                        <h3 class="box-name">{{ $common_one['name'] }}</h3>
                        <p class="box-amount">{{ "¥".number_format($common_one['amount']); }}</p>
                        <input type="hidden" name="sub_cate_id" value="{{ $common_one['sub_cate_id'] }}">
                        <input type="hidden" name="sub_cate_name" value="{{ $common_one['name'] }}">
                        <input type="submit" class="main_category_btn" value="">
                    </form>
                </div>
            <?php endforeach; ?>
            <!-- < foreach ($ex_sub_categories as $ex_sub_category): ?> -->
                <!-- <div>
                    <form action="ex-by-category" method="POST" class="box box-link by-category-box main_category_form">
                        @csrf
                        <div class="box-icon-area box-3-items">
                            <div class="icon-area">
                                <i class="box-icon { $ex_sub_category['icon'] }"></i>    
                            </div>
                        </div>
                        <h3 class="box-name">{ $ex_sub_category['sub_category_name'] }</h3>
                        <p class="box-amount">{ "¥".number_format($ex_sub_category['by_category_sum']); }</p>
                        <input type="hidden" name="sub_category_id" value="{ $ex_sub_category['sub_category_id'] }">
                        <input type="hidden" name="sub_category_name" value="{ $ex_sub_category['sub_category_name'] }">
                        <input type="submit" class="main_category_btn" value="">
                    </form>
                </div> -->
            <!-- < endforeach; ?> -->
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