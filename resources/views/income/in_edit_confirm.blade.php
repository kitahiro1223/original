@extends('layouts.in')
@section('content')
<div class="contents">
<div class="container">
<main class="data-individual">
    <div class="by-category-title" style="margin:0 10%;">
    </div>    
    <div class="form-area login-form" style="margin: 0 auto;">
        <h2>編集の確認</h2>
        <form action="in-edit" method="post">
            @csrf
            <p class="add_confirm">以下の内容でよろしければ「編集」を押して下さい。</p>
            <ul>
                <li>
                    <label for="amount">¥</label>
                    <div class="add_data">{{ $edit_data['amount'] }}</div>
                </li>
                <li>
                    <label for="category"><i class="fa-solid fa-folder-open" title="カテゴリー"></i></label>
                    <div class="add_category_area">
                        <p class="add_data">{{ $edit_data['main_category'] }}</p>
                    </div>
                </li>
                <li>
                    <label for="date" title="日付"><i class="fa-regular fa-calendar"></i></label>
                    <div class="add_data">{{ $edit_data['date'] }}</div>
                    <input type="hidden" name="date" id="date" value="{{ $edit_data['date'] }}">
                </li>
                <li>
                    <label for="in_to" title="収入先"><i class="fa-solid fa-wallet"></i></label>
                    <div class="add_data">{{ $edit_data['in_to'] }}</div>
                </li>
                @if($edit_data['comment'] != '')
                <div class="add_data_textarea">{{ $edit_data['comment'] }}</div>
                @endif
            </ul>
        </form>
        <div class="submit-area">
            <form action="in-edit" method="post" style="padding-top: 0;">
                @csrf
                <input type="submit" name="submit" value="戻る" class="submit back-btn">
            </form>
            <form action="in-edit-comp" method="post" style="padding-top: 0;">
                @csrf
                <input type="submit" name="edit_submit" value="編集" class="submit">
            </form>
        </div><!-- submit-area -->
    </div><!-- form-area -->
</main>
<div class="content-space"></div>
@endsection