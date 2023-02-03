@extends('layouts.in')
@section('content')
<div class="contents">
<div class="container">
<main class="data-individual content-center" style="height: auto;">
    <div class="form-area login-form">
        <h2>編集</h2>
        <form action="in-edit" method="post">
            @csrf
            @if (isset($error))
                <div class="alert alert-danger" style="padding: 0 6%;">
                    @foreach ($error as $er)
                        <p>{{ "※".$er }}</p>
                    @endforeach
                </div>
            @endif
            <ul>
                <li>
                    <label for="amount">¥</label>
                    <input type="number" name="amount" id="amount" placeholder="0" value="<?php if (!empty($edit_data['amount'])) { echo $edit_data['amount']; } else { echo ""; } ?>">
                </li>
                <li>
                    <label for="category"><i class="fa-solid fa-folder-open" title="カテゴリー"></i></label>
                    <div class="add_category_area">
                        <select name="main_category" id="add_main_category" style="width: auto; border-bottom: 1px solid var(--accent-color)">
                            @if (!empty($edit_data['main_category_id'])) 
                                <option value="{{ $edit_data['main_category_name'] }}">{{ $edit_data['main_category_name'] }}</option>
                            @endif
                            @foreach ($add_main_categories as $add_main_category)
                                @if (!empty($edit_data['sub_category_id'])) 
                                    @if ($edit_data['sub_category_name'] != $add_sub_category['name']) 
                                        <option value="{{ $add_main_category['name'] }}">{{ $add_main_category["name"] }}</option>
                                    @endif
                                @else
                                    <option value="{{ $add_main_category['name'] }}">{{ $add_main_category["name"] }}</option>
                                @endif    
                            @endforeach
                        </select>
                    </div>
                </li>
                <li>
                    <label for="date" title="日付"><i class="fa-regular fa-calendar"></i></label>
                    <label class="date-edit"><input type="date" name="date" id="date" value="<?php if (!empty($edit_data['date'])) { echo $edit_data['date']; } else { echo date('Y-m-d'); } ?>"></label>
                </li>
                <li>
                    <label for="in_to" title="収入先"><i class="fa-solid fa-wallet"></i></label>
                    <select name="in_to" id="in-to">
                        @if (!empty($edit_data['in_to']))
                            <option value="{{ $edit_data['in_to'] }}">{{ $edit_data["in_to"] }}</option>;
                        @else
                            <option value="">収入先を選択</option>
                        @endif
                        @foreach ($in_tos as $in_to)
                            @if (!empty($edit_data['in_to']))
                                @if ($edit_data['in_to'] != $in_to['name'])
                                    <option value="{{ $in_to['name'] }}">{{ $in_to["name"] }}</option>
                                @endif
                            @else
                                <option value="{{ $in_to['name'] }}">{{ $in_to["name"] }}</option>
                            @endif
                        @endforeach
                    </select>
                </li>
                <textarea name="comment" rows="3" placeholder="メモ"><?php if (!empty($edit_data['comment'])) { echo $edit_data['comment']; } else { echo ""; } ?></textarea>
            </ul>
            <div><input type="submit" class="submit" name="edit_submit" value="確認"></div>
        </form>
    </div><!-- form-area -->
</main>
<div class="content-space"></div>
@endsection