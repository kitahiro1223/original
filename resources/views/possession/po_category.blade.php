@extends('layouts.in')
@section('content')
<div class="contents">
<div class="container">
<main class="by-category-main">
    <div class="by-category-title">
        <div class="icon-area color5""><!-- アイコンを収める -->
            <i class="{{ $main['icon'] }}"></i>
        </div>
        <h2>{{ $main['title'] }}</h2>
    </div>
    <div class="box total-box">
        <h3>総計</h3>
        <p>{{ "¥".number_format(array_sum(array_column($results, 'amount'))) }}</p>
    </div>
    <div class="by-category-inner">
        <ul class="data-list">
            @if(!empty($results))
            <?php foreach ($results as $result): ?>
                <li class="box">
                    <div class="info-area">
                        <div class="data-date">{{ $result['date'] }}</div>
                        @if($result['kind_id'] == 2)
                            <div class="icon-area header-icon po-kind color5">
                                <i class="icon icon-left icon-half fa-solid fa-right-long"></i>
                                <i class="icon icon-right fa-solid fa-wallet"></i>
                            </div>
                        @elseif($result['kind_id'] == 3)
                            <div class="icon-area header-icon po-kind color1">
                                <i class="icon icon-left fa-solid fa-wallet"></i>
                                <i class="icon icon-right icon-half fa-solid fa-right-long"></i>
                            </div>
                        @endif
                        <div class="data-amount">{{ "¥".number_format($result['amount']) }}</div>
                    </div>
                    <div class="data-btn-area">
                        @if($result['kind_id'] == 2)
                            <form action="in-detail" method="POST" class="data-btn-form">
                                @csrf
                                <input type="hidden" name="id" value="{{ $result['id'] }}">
                                <input type="submit" class="data-btn-submit" value=""><i class="btn fa-solid fa-circle-info"></i></input>
                            </form>
                            <form action="in-edit" method="POST" class="data-btn-form">
                                @csrf
                                <input type="hidden" name="id" value="{{ $result['id'] }}">
                                <input type="submit" class="data-btn-submit" value=""><i class="btn fa-solid fa-pen"></i></input>
                            </form>
                            <form action="in-delete" method="POST" class="data-btn-form">
                                @csrf
                                <input type="hidden" name="id" value="{{ $result['id'] }}">
                                <input type="submit" class="data-btn-submit trash-btn" value=""><i class="btn fa-solid fa-trash"></i></input>
                            </form>
                        @elseif($result['kind_id'] == 3)
                            <form action="ex-detail" method="POST" class="data-btn-form">
                                @csrf
                                <input type="hidden" name="id" value="{{ $result['id'] }}">
                                <input type="submit" class="data-btn-submit" value=""><i class="btn fa-solid fa-circle-info"></i></input>
                            </form>
                            <form action="ex-edit" method="POST" class="data-btn-form">
                                @csrf
                                <input type="hidden" name="id" value="{{ $result['id'] }}">
                                <input type="submit" class="data-btn-submit" value=""><i class="btn fa-solid fa-pen"></i></input>
                            </form>
                            <form action="ex-delete" method="POST" class="data-btn-form">
                                @csrf
                                <input type="hidden" name="id" value="{{ $result['id'] }}">
                                <input type="submit" class="data-btn-submit trash-btn" value=""><i class="btn fa-solid fa-trash"></i></input>
                            </form>
                        @endif
                    </div>
                </li>
            <?php endforeach; ?>
            @endif
        </ul>
    </div><!-- .by-category-inner -->
</main>
@endsection