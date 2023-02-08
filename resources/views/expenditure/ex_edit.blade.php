@extends('layouts.in')
@section('content')
<div class="contents">
<div class="container">
<main class="data-individual" style="height: auto;">
    <div class="by-category-title" style="margin: 0 10%;">
        @if($data['kind_id'] == 3 || empty($data['kind_id']))
        <div class="back">
            <a href="ex-by-category">
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
    <div class="form-area login-form" style="margin: 0 auto;">
        <h2>編集</h2>
        <form action="ex-edit" method="post">
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
                        @if (!empty($edit_data['icon']) && !empty($edit_data['main_category_name']))
                            <div class="icon-area color5" style="margin: 0 6px 0 0;"><!-- アイコンを収める -->
                                <i class="{{ $edit_data['icon'] }}"></i>
                                <input type="hidden" name="icon" value="{{ $edit_data['icon'] }}">
                            </div>
                            <p class="add_category_name">{{ $edit_data['main_category_name'] }}</p>
                            <input type="hidden" name="main_category" value="{{ $edit_data['main_category_name'] }}">
                        @else
                            <select name="main_category" id="add_main_category" style="width: auto; border-bottom: 1px solid var(--accent-color)">
                                @foreach ($add_main_categories as $add_main_category)
                                    <option value="{{ $add_main_category['name'] }}">{{ $add_main_category["name"] }}</option>
                                @endforeach
                            </select>
                        @endif
                        <p style="padding: 0 24px;">></p>
                        <select name="sub_category" id="add_sub_category" style="width: auto;">
                            @if (!empty($edit_data['sub_category_id'])) 
                                <option value="{{ $edit_data['sub_category_name'] }}">{{ $edit_data['sub_category_name'] }}</option>
                            @endif
                            @foreach ($add_sub_categories as $add_sub_category)
                                @if (!empty($edit_data['sub_category_id'])) 
                                    @if ($edit_data['sub_category_name'] != $add_sub_category['name']) 
                                        <option value="{{ $add_sub_category['name'] }}">{{ $add_sub_category["name"] }}</option>
                                    @endif
                                @else
                                    <option value="{{ $add_sub_category['name'] }}">{{ $add_sub_category["name"] }}</option>
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
                    <label for="ex_source" title="支出元"><i class="fa-solid fa-wallet"></i></label>
                    <select name="ex_source" id="ex-source">
                        @if (!empty($edit_data['ex_source']))
                            @foreach ($ex_sources as $ex_source)
                                @if ($edit_data['ex_source'] == $ex_source['id'])
                                    <option value="{{ $ex_source['name'] }}">{{ $ex_source["name"] }}</option>
                                @endif
                            @endforeach
                        @else
                            <option value="">支出元を選択</option>
                        @endif
                        @foreach ($ex_sources as $ex_source)
                            @if (!empty($edit_data['ex_source']))
                                @if ($edit_data['ex_source'] != $ex_source['name'])
                                    <option value="{{ $ex_source['name'] }}">{{ $ex_source["name"] }}</option>
                                @endif
                            @else
                                <option value="{{ $ex_source['name'] }}">{{ $ex_source["name"] }}</option>
                            @endif
                        @endforeach
                    </select>
                </li>
                <li>
                    <label for="name"><i class="fa-solid fa-tag" title="名前"></i></label>
                    <input type="text" name="name" id="name" placeholder="名前" style="border-bottom: 1px solid var(--accent-color);" value="<?php if (!empty($edit_data['name'])) { echo $edit_data['name']; } ?>">
                </li>
                <textarea name="comment" rows="3" placeholder="メモ"><?php if (!empty($edit_data['comment'])) { echo $edit_data['comment']; } else { echo ""; } ?></textarea>
            </ul>
            <div><input type="submit" class="submit" name="edit_submit" value="確認"></div>
        </form>
    </div><!-- form-area -->
</main>
<div class="content-space"></div>
@endsection