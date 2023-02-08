@extends('layouts.in')
@section('content')
<div class="contents">
<div class="container">
<main>
    <div class="by-category-title" style="margin: 0 10%;">
        <div class="back">
            <a href="setting">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
        </div>
    </div>
    <div class="form-area">
        <h2>アカウント</h2>
        <form action="" method="post">
            <div class="form-summary">
                <p>以下のアカウントでログインしています。</p>
            </div>
            <div style="margin-bottom: 48px;">
                <div class="ac-info">
                    <h3>氏名</h3>
                    <p class="confirm-content">{{ $account['name'] }}</p>
                </div>
                <div class="ac-info">
                    <h3>メールアドレス</h3>
                    <p class="confirm-content">{{ $account['email'] }}</p>
                </div>
            </div>
            <div>
                <a href="logout_check" class="submit ac_logout" id="logout-btn">ログアウト</a>
            </div>
        </form>
    </div><!-- form-area -->
</main><!-- content-center -->
@endsection