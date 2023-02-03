@extends('layouts.in')
@section('content')
<div class="contents">
<div class="container">
<main class="data-individual content-center">
    <div class="form-area login-form">
        <h2>振替</h2>
        <form action="po-add" method="post">
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
                    <input type="number" name="amount" id="amount" placeholder="0" value="<?php if (!empty($add_data['amount'])) { echo $add_data['amount']; } else { echo ""; } ?>">
                </li>
                <li>
                    <label for="ex_source" title="振替元"><i class="fa-solid fa-wallet"></i></label>
                    <select name="ex_source" id="ex-source">
                        @if (!empty($add_data['ex_source']))
                            <option value="{{ $add_data['ex_source'] }}">{{ $add_data["ex_source"] }}</option>;
                        @else
                            <option value="">振替元を選択</option>
                        @endif
                        @foreach ($ex_sources as $ex_source)
                            @if (!empty($add_data['ex_source']))
                                @if ($add_data['ex_source'] != $ex_source['name'])
                                    <option value="{{ $ex_source['name'] }}">{{ $ex_source["name"] }}</option>
                                @endif
                            @else
                                <option value="{{ $ex_source['name'] }}">{{ $ex_source["name"] }}</option>
                            @endif
                        @endforeach
                    </select>
                </li>
                <li>
                    <label for="in_to" title="振替元"><i class="fa-solid fa-wallet"></i></label>
                    <select name="in_to" id="ex-source">
                        @if (!empty($add_data['in_to']))
                            <option value="{{ $add_data['in_to'] }}">{{ $add_data["in_to"] }}</option>;
                        @else
                            <option value="">振替先を選択</option>
                        @endif
                        @foreach ($in_tos as $in_to)
                            @if (!empty($add_data['in_to']))
                                @if ($add_data['in_to'] != $in_to['name'])
                                    <option value="{{ $in_to['name'] }}">{{ $in_to["name"] }}</option>
                                @endif
                            @else
                                <option value="{{ $in_to['name'] }}">{{ $in_to["name"] }}</option>
                            @endif
                        @endforeach
                    </select>
                </li>
                <li>
                    <label for="date" title="日付"><i class="fa-regular fa-calendar"></i></label>
                    <label class="date-edit"><input type="date" name="date" id="date" value="<?php if (!empty($add_data['date'])) { echo $add_data['date']; } else { echo date('Y-m-d'); } ?>"></label>
                </li>
                <textarea name="comment" rows="3" placeholder="メモ"><?php if (!empty($add_data['comment'])) { echo $add_data['comment']; } else { echo ""; } ?></textarea>
            </ul>

            <div><input type="submit" class="submit" name="add_submit" value="確認"></div>
        </form>
    </div><!-- form-area -->
</main>
<div class="content-space"></div>
@endsection