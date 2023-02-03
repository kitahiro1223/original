@extends('layouts.in')
@section('content')
<div class="contents">
<div class="container">
<main class="by-category-main">
    <div class="by-category-title">
        <div class="icon-area color5""><!-- アイコンを収める -->
            <i class="{{ $page_top['icon'] }}"></i>
        </div>
        <h2>{{ $page_top['title'] }}</h2>
    </div>
    </div>
    <div class="by-category-inner">
        <ul class="data-list">
            <?php foreach ($users as $user): ?>
                <li class="box">
                    <div class="info-area">
                        <div class="user-id">{{ $user['id'] }}</div>
                        <div class="user-name">{{ $user['name'] }}</div>
                        <div class="user-email">{{ $user['email'] }}</div>
                    </div>
                    <!-- <div class="data-btn-area">
                        <form action="ex-detail" method="POST" class="data-btn-form">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $user['id'] }}">
                            <input type="submit" class="data-btn-submit" value=""><i class="btn fa-solid fa-circle-info"></i></input>
                        </form>
                        <form action="ex-edit" method="POST" class="data-btn-form">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $user['id'] }}">
                            <input type="submit" class="data-btn-submit" value=""><i class="btn fa-solid fa-pen"></i></input>
                        </form>
                        <a class="btn trash-btn"><i class="fa-solid fa-trash"></i></a>
                    </div> -->
                </li>
            <?php endforeach; ?>
        </ul>
    </div><!-- .by-category-inner -->
</main>
@endsection