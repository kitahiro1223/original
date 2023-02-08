@extends('layouts.in')
@section('content')
<div class="contents">
<div class="container">
<main class="by-category-main">
    <div class="by-category-title">
        <div class="back">
            <a href="category-kinds">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
        </div>         
        <div class="icon-area color2">
            <i class="icon fa-solid fa-gear"></i>
        </div>
        <h2>カテゴリーの編集</h2>
    </div>
    <div class="box categories">
        <div class="by-category-title categories">
            @if ($kind['id'] == 1)
            <div class="icon-area">
                <i class="icon fa-solid fa-piggy-bank color3"></i>
            </div>
            @elseif($kind['id'] == 2)
            <div class="icon-area">
                <i class="icon icon-left icon-half fa-solid fa-right-long color5"></i>
                <i class="icon icon-right fa-solid fa-wallet color5"></i>
            </div>        
            @elseif($kind['id'] == 3)
            <div class="icon-area">
                <i class="icon icon-left fa-solid fa-wallet color1"></i>
                <i class="icon icon-right icon-half fa-solid fa-right-long color1"></i>
            </div>
            @endif
            <h3>{{ $kind['name']}}</h3>
        </div>
        <p class="summary">既定のカテゴリーは編集できません</p>
    </div>
    <div class="date-add-area" style="display: flex; justify-content: center;">
        <form action="category-add" method="POST" class="add-cate-btn">
            @csrf
            <div class="box add-btn box-link">
                <div class="icon-area"><!-- アイコンを収める -->
                    <i class="icon fa-solid fa-plus"></i>
                </div>
                <h3>追加</h3>
            </div>
            <input type="hidden" name="add_cate_kind" value="{{ $kind['id'] }}">
            <input type="submit" name="add_cate_submit" class="main_category_btn" value="">
        </form>
    </div>
    <div class="by-category-inner">
        <ul class="data-list">
            <!-- 所持金・収入 メインカテゴリーを表示-->
            @if($kind['id'] != 3)
                @foreach($main_cates as $main_cate)
                <li class="box">
                    <div class="info-area">
                        <div class="icon-area header-icon po-kind color5">
                            <i class="{{$main_cate['icon']}}"></i>
                        </div>
                        <div class="data-name">{{$main_cate['name']}}</div>
                    </div>
                    @if($main_cate['user_id'] != 0)
                    <div class="data-btn-area">
                        <form action="category-delete" method="POST" class="data-btn-form">
                            @csrf
                            <input type="hidden" name="id" value="{{ $main_cate['id'] }}">
                            <input type="submit" class="data-btn-submit trash-btn" value=""><i class="btn fa-solid fa-trash"></i></input>
                        </form>
                    </div>
                    @endif
                </li>
                @endforeach
            <!-- 支出 メインカテゴリーの表示とサブカテゴリーへのform -->
            @else
                @foreach($main_cates as $main_cate)
                <li class="box box-link" style="position: relative;">
                    <form action="sub-categories" method="POST" class="data-btn-form">
                        @csrf
                        <div class="info-area">
                            <div class="icon-area header-icon po-kind color5">
                                <i class="{{ $main_cate['icon'] }}"></i>
                            </div>
                            <div class="data-name">{{$main_cate['name']}}</div>
                            <input type="hidden" name="icon_id" value="{{ $main_cate['icon_id'] }}">
                            <input type="hidden" name="icon" value="{{ $main_cate['icon'] }}">
                        </div>
                        <input type="hidden" name="main_cate_id" value="{{ $main_cate['id'] }}">
                        <input type="hidden" name="main_cate_name" value="{{ $main_cate['name'] }}">
                        <input type="submit" name="main_cate_submit" class="main_category_btn" value="">
                    </form>
                    @if($main_cate['user_id'] != 0)
                    <div class="data-btn-area">
                        <form action="category-delete" method="POST" class="data-btn-form">
                            @csrf
                            <input type="hidden" name="id" value="{{ $main_cate['id'] }}">
                            <input type="submit" class="data-btn-submit trash-btn" value=""><i class="btn fa-solid fa-trash"></i></input>
                        </form>
                    </div>
                    @endif
                </li>
                @endforeach
            @endif
        </ul>
    </div><!-- .by-category-inner -->
</main>
@endsection