@extends('layouts.in')
@section('content')
<div class="contents">
<div class="container">
<main class="by-category-main">
    <div class="by-category-title">
        <div class="icon-area color3"><!-- アイコンを収める -->
            <i class="icon fa-solid fa-piggy-bank"></i>
        </div>
        <h2>所持金</h2>
    </div>
    <div class="box total-box">
        <h3>総計</h3>
        <p>{{ "¥".number_format(array_sum(array_column($po_by_cates, 'sum'))) }}</p>
    </div>
    <div class="by-category-inner">
        <div class="box-list">
            <?php foreach ($po_by_cates as $po_by_cate): ?>
            <div>
                <form action="po-category" method="POST" class="box box-link by-category-box main_category_form">
                    @csrf
                    <div class="box-icon-area box-3-items"><!-- アイコンを動かす -->
                        <div class="icon-area"><!-- アイコンを収める -->
                            <i class="box-icon {{ $po_by_cate['icon'] }}"></i>
                            <input type="hidden" name="icon_id" value="{{ $po_by_cate['icon_id'] }}">
                            <input type="hidden" name="icon" value="{{ $po_by_cate['icon'] }}">
                        </div>
                    </div>
                    <h3 class="box-name">{{ $po_by_cate['name'] }}</h3>
                    <p class="box-amount">{{ "¥".number_format($po_by_cate['sum']) }}</p>
                    <input type="hidden" name="main_category_id" value="{{ $po_by_cate['id'] }}">
                    <input type="hidden" name="main_category_name" value="{{ $po_by_cate['name'] }}">
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