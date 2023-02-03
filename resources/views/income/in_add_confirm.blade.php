@extends('layouts.in')
@section('content')
<div class="contents">
<div class="container">
<main class="data-individual">
    <div class="form-area login-form">
        <h2>追加の確認</h2>
        <form action="" method="post">
            @csrf
            <p class="add_confirm">以下の内容でよろしければ「追加」を押して下さい。</p>
            <ul>
                <li>
                    <label for="amount">¥</label>
                    <div class="add_data">{{ $add_data['amount'] }}</div>
                </li>
                <li>
                    <label for="category"><i class="fa-solid fa-folder-open" title="カテゴリー"></i></label>
                    <div class="add_category_area">
                        <!-- <div class="icon-area color5" style="margin: 0 6px 0 0;">
                            <i class="{ $add_data['icon'] }"></i>
                        </div> -->
                        <p class="add_data">{{ $add_data['main_category'] }}</p>
                    </div>
                </li>
                <li>
                    <label for="date" title="日付"><i class="fa-regular fa-calendar"></i></label>
                    <div class="add_data">{{ $add_data['date'] }}</div>
                    <input type="hidden" name="date" id="date" value="{{ $add_data['date'] }}">
                </li>
                <li>
                    <label for="in_to" title="収入先"><i class="fa-solid fa-wallet"></i></label>
                    <div class="add_data">{{ $add_data['in_to'] }}</div>
                </li>
                @if($add_data['comment'] != '')
                <div class="add_data_textarea">{{ $add_data['comment'] }}</div>
                @endif
            </ul>
        </form>
        <div class="submit-area">
            <form action="in-add" method="post" style="padding-top: 0;">
                @csrf
                <input type="submit" name="submit" value="戻る" class="submit back-btn">
            </form>
            <form action="in-add-comp" method="post" style="padding-top: 0;">
                @csrf
                <input type="submit" name="add_submit" value="追加" class="submit">
            </form>
        </div><!-- submit-area -->
    </div><!-- form-area -->
</main>
<div class="content-space"></div>
@endsection