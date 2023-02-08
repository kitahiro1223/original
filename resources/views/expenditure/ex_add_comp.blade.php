@extends('layouts.in')
@section('content')
<div class="contents content-center">
<div class="container">
<main>
    <div class="by-category-title" style="margin: 0 10%; height: 0;">
        <div class="back">
            <a href="ex-by-category">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
        </div>
    </div>    
    <div class="form-area signup-form">
        <h2>追加の完了</h2>
        <form action="" method="post">
            <div class="form-summary">
                <p>支出の追加が完了しました。</p>
            </div>
            <div class="back-btn add-comp">
                <div><a href="expenditure">支出画面へ戻る</a></div>
                <div><a href="/">HOMEへ</a></div>
            </div>
        </form>    
    </div><!-- form-area -->
</main>
@endsection