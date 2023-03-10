@extends('layouts.in')
@section('content')
<div class="contents content-center">
<div class="container">
<main>
    <div class="by-category-title" style="margin: 0 10%; height: 0;">
        @if($data['kind_id'] == 2)
        <div class="back">
            <a href="in-category">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
        </div>                
        @elseif($data['kind_id'] == 1)
        <div class="back">
            <a href="po-category">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
        </div>        
        @endif        
    </div>    
    <div class="form-area signup-form">
        <h2>編集の完了</h2>
        <form action="" method="post">
            <div class="form-summary">
                <p>収入の編集が完了しました。</p>
            </div>
            <div class="back-btn add-comp">
                <div><a href="income">収入画面へ戻る</a></div>
                <div><a href="/">HOMEへ</a></div>
            </div>
        </form>    
    </div><!-- form-area -->
</main>
@endsection