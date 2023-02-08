@extends('layouts.in')
@section('content')
<div class="contents">
<div class="container">
<main class="by-category-main">
    <div class="by-category-title">
        <div class="back">
            <a href="income">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
        </div>
        <div class="icon-area color5""><!-- アイコンを収める -->
            <i class="{{ $main['icon'] }}"></i>
        </div>
        <h2>{{ $main['title'] }}</h2>
    </div>
    <div class="box total-box">
        <h3>総計</h3>
        <p>{{ "¥".number_format($in_by_cate_sum) }}</p>
    </div>
    <div class="box-list date-add-area" style="justify-content: center;">
        <a href="in-add" class="add-btn-form box-link">
            <div class="box add-btn">
                <div class="icon-area"><!-- アイコンを収める -->
                    <i class="icon fa-solid fa-plus"></i>
                </div>
                <h3>追加</h3>
            </div>
        </a>
    </div>
    <div class="by-category-inner">
        <ul class="data-list">
            @if(!empty($by_cates))
            <?php foreach ($by_cates as $by_cate): ?>
                <li class="box">
                    <div class="info-area">
                        <div class="data-date">{{ $by_cate['date'] }}</div>
                        <div class="data-amount">{{ "¥".number_format($by_cate['amount']) }}</div>
                    </div>
                    <div class="data-btn-area">
                        <form action="in-detail" method="POST" class="data-btn-form">
                            @csrf
                            <input type="hidden" name="id" value="{{ $by_cate['id'] }}">
                            <input type="hidden" name="kind_id" value="2">
                            <input type="submit" class="data-btn-submit" value=""><i class="btn fa-solid fa-circle-info"></i></input>
                        </form>
                        <form action="in-edit" method="POST" class="data-btn-form">
                            @csrf
                            <input type="hidden" name="id" value="{{ $by_cate['id'] }}">
                            <input type="hidden" name="kind_id" value="2">
                            <input type="submit" class="data-btn-submit" value=""><i class="btn fa-solid fa-pen"></i></input>
                        </form>
                        <form action="in-delete" method="POST" class="data-btn-form">
                            @csrf
                            <input type="hidden" name="id" value="{{ $by_cate['id'] }}">
                            <input type="hidden" name="kind_id" value="2">
                            <input type="submit" class="data-btn-submit trash-btn" value=""><i class="btn fa-solid fa-trash"></i></input>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
            @endif
        </ul>
    </div><!-- .by-category-inner -->
</main>
@endsection