@extends('layouts.in')
@section('content')
<div class="contents">
<div class="container">
<main class="data-individual">
    <div class="form-area login-form">
        <h2>詳細</h2>
        <form>
            @csrf
            <ul>
                <li>
                    <label for="amount">¥</label>
                    <div class="data_one">{{ $data_one['amount'] }}</div>
                </li>
                <li>
                    <label for="category"><i class="fa-solid fa-folder-open" title="カテゴリー"></i></label>
                    <div class="add_category_area">
                        <div class="icon-area color5" style="margin: 0 6px 0 0;"><!-- アイコンを収める -->
                            <i class="{{ $data_one['icon'] }}"></i>
                        </div>
                        <p class="data_one">{{ $data_one['main_category_name'] }}</p>
                        <p style="padding: 0 24px;">></p>
                        <p class="data_one">{{ $data_one['sub_category_name'] }}</p>
                    </div>
                </li>
                <li>
                    <label for="date" title="日付"><i class="fa-regular fa-calendar"></i></label>
                    <div class="data_one">{{ $data_one['date'] }}</div>
                    <input type="hidden" name="date" id="date" value="{{ $data_one['date'] }}">
                </li>
                <li>
                    <label for="ex_source" title="支出元"><i class="fa-solid fa-wallet"></i></label>
                    <div class="data_one">{{ $data_one['ex_source'] }}</div>
                </li>
                <li>
                    <label for="notion" title="通知"><i class="fa-solid fa-bell"></i></label>
                    <div class="data_one">{{ $data_one['notice'] }}</div>
                </li>
                <li>
                    <label for="name"><i class="fa-solid fa-tag" title="カテゴリー"></i></label>
                    <div class="data_one">{{ $data_one['name'] }}</div>
                </li>
                @if($data_one['comment'] != '')
                <div class="add_data_textarea">{{ $data_one['comment'] }}</div>
                @endif
            </ul>
        </form>
        <div class="submit-area">
            <form action="ex-edit" method="post" style="padding-top: 0;">
                @csrf
                <input type="hidden" name="id" id="id" value="{{ $data_one['id'] }}">
                <input type="submit" name="submit" value="編集" class="submit">
            </form>
            <form action="ex-delete" method="post" style="padding-top: 0;">
                @csrf
                <input type="hidden" name="id" id="id" value="{{ $data_one['id'] }}">
                <input type="submit" name="delete" value="削除" class="submit trash-btn">
            </form>
        </div><!-- submit-area -->
    </div><!-- form-area -->
</main>
<div class="content-space"></div>
@endsection