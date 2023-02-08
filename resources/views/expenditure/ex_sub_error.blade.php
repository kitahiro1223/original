@extends('layouts.in')
@section('content')
<div class="contents content-center">
<div class="container">
<main class="by-category-main">
    <div class="by-category-title" style="margin: 0 10%; height: 0;">
        <div class="back">
            <a href="expenditure">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
        </div>
    </div>
    <div class="form-area signup-form">
        <h2>サブカテゴリーがありません</h2>
        <form action="" method="post">
            <div class="form-summary">
                <p>設定の「カテゴリー編集」から サブカテゴリーを追加して下さい。</p>
            </div>
            <div class="back-btn add-comp">
                <div><a href="category-kinds">カテゴリー編集</a></div>
                <div><a href="/">HOMEへ</a></div>
            </div>
        </form>    
    </div><!-- form-area -->
</main>
@endsection