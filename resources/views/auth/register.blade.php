@extends('layouts.app')
@section('content')
<div class="form-area signup-form">
    <h2>{{ __('新規登録') }}</h2>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-summary">
            <p>アカウントを新しく登録します。</p>
            <p>メールアドレスとパスワードを入力して下さい。</p>
        </div>
        <div>
            <h3>{{ __('氏名') }}</h3>
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="山田太郎">
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div>
            <h3>{{ __('メールアドレス') }}</h3>
            <div class="col-md-6">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="test@test.co.jp">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div>
            <h3>{{ __('パスワード') }}</h3>
            <div class="col-md-6">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <p style="padding-bottom: 6px;"></p>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="password 再入力">
            </div>
        </div>
        <div>
            <input type="submit" class="submit" name="submit" value="{{ __('確認') }}">
        </div>
    </form>    
</div><!-- form-area -->
@endsection
