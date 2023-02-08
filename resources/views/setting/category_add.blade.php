@extends('layouts.in')
@section('content')
<div class="contents content-center">
<div class="container">
<main class="data-individual" style="height: 400px;">
    <div class="by-category-title" style="margin: 0 10%; height: 0;">
        <div class="back">
            <a href="categories">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
        </div>
    </div>
    <div class="form-area login-form">
        <h2>カテゴリーの追加</h2>
        <form action="category-add" method="post">
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
                    <label for="name"><i class="fa-solid fa-tag" title="名前"></i></label>
                    @if(!empty($to_sub))
                    <input type="hidden" name="add_cate_main" id="cate_main" value="{{ $to_sub['id'] }}">
                    @endif
                    <input type="text" name="add_cate_name" id="name" placeholder="名前" style="border-bottom: 1px solid var(--accent-color);">
                </li>
            </ul>
            <div><input type="submit" class="submit submit-confirm" name="add_submit" value="追加"></div>
        </form>
    </div><!-- form-area -->
</main>
<div class="content-space"></div>
@endsection